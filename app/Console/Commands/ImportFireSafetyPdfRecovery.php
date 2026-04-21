<?php

namespace App\Console\Commands;

use App\Models\FireSafetyAlarmSystem;
use App\Models\FireSafetyBuilding;
use App\Models\FireSafetyExtinguisher;
use App\Models\FireSafetyRoom;
use App\Models\School;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ImportFireSafetyPdfRecovery extends Command
{
    protected $signature = 'fire-safety:import-pdf-recovery
                            {--file=storage/app/recovery/fire_safety_pdf_recovery.json : Recovery JSON path}
                            {--dry-run : Preview without writing changes}
                            {--replace-existing : Remove existing fire safety child records for each imported school before insert}';

    protected $description = 'Import recovered fire safety records parsed from full-school PDF export reports.';

    public function handle(): int
    {
        $path = base_path((string) $this->option('file'));
        $dryRun = (bool) $this->option('dry-run');
        $replaceExisting = (bool) $this->option('replace-existing');

        if (!file_exists($path)) {
            $this->error("Recovery file not found: {$path}");
            return self::FAILURE;
        }

        $json = file_get_contents($path);
        $payload = json_decode((string) $json, true);

        if (!is_array($payload) || !isset($payload['reports']) || !is_array($payload['reports'])) {
            $this->error('Invalid recovery payload format.');
            return self::FAILURE;
        }

        $totalSchools = 0;
        $totalBuildings = 0;
        $totalAlarms = 0;
        $totalExts = 0;
        $totalRooms = 0;

        DB::beginTransaction();

        try {
            foreach ($payload['reports'] as $report) {
                $schoolData = Arr::get($report, 'school', []);
                $schoolName = trim((string) Arr::get($schoolData, 'school_name', ''));
                $schoolIdCode = trim((string) Arr::get($schoolData, 'school_id', ''));

                if ($schoolName === '' || $schoolIdCode === '') {
                    $this->warn('Skipping report with missing school name or school ID.');
                    continue;
                }

                $school = School::query()
                    ->where('school_id', $schoolIdCode)
                    ->orWhere('school_id_number', $schoolIdCode)
                    ->orWhere('school_name', $schoolName)
                    ->first();

                if (!$school) {
                    $school = new School();
                }

                $school->school_name = $schoolName;
                $school->school_id = $schoolIdCode;
                $school->school_id_number = $school->school_id_number ?: $schoolIdCode;
                $school->fire_safety_status = $school->fire_safety_status ?: 'unconfigured';
                $school->save();

                if ($replaceExisting) {
                    $this->replaceSchoolFireSafetyChildren((int) $school->id);
                }

                $totalSchools++;

                $buildingByCode = [];
                foreach ((array) Arr::get($report, 'buildings', []) as $b) {
                    $buildingCode = strtoupper(str_replace(' ', '', (string) Arr::get($b, 'building_no', '')));
                    if ($buildingCode === '') {
                        continue;
                    }

                    $building = FireSafetyBuilding::query()
                        ->where('unified_school_id', $school->id)
                        ->where('building_no', $buildingCode)
                        ->first();

                    if (!$building) {
                        $building = new FireSafetyBuilding();
                        $building->unified_school_id = $school->id;
                        $building->building_no = $buildingCode;
                    }

                    $name = Arr::get($b, 'building_name');
                    $floors = max(1, (int) Arr::get($b, 'floors', 1));
                    $rooms = max($floors, (int) Arr::get($b, 'rooms', 1));

                    $building->building_name = is_string($name) && trim($name) !== '' ? trim($name) : ($building->building_name ?: $buildingCode);
                    $building->floors = $floors;
                    $building->rooms = $rooms;
                    $building->max_floors = max((int) ($building->max_floors ?: 0), $floors);
                    $building->max_rooms = max((int) ($building->max_rooms ?: 0), $rooms);

                    $building->save();

                    $buildingByCode[$buildingCode] = $building;
                    $totalBuildings++;
                }

                foreach ((array) Arr::get($report, 'alarms', []) as $a) {
                    $code = trim((string) Arr::get($a, 'code', ''));
                    if ($code === '') {
                        continue;
                    }

                    $alarm = FireSafetyAlarmSystem::query()
                        ->where('unified_school_id', $school->id)
                        ->where('code', $code)
                        ->first();

                    if (!$alarm) {
                        $alarm = new FireSafetyAlarmSystem();
                        $alarm->unified_school_id = $school->id;
                        $alarm->code = $code;
                    }

                    $location = (string) Arr::get($a, 'location', '');
                    $statusRaw = strtolower(trim((string) Arr::get($a, 'status_raw', Arr::get($a, 'status', 'maintenance'))));
                    $status = $this->mapAlarmStatusToSchema($statusRaw);
                    $alarmType = (string) Arr::get($a, 'alarm_type', 'Bell');
                    $remarks = trim((string) Arr::get($a, 'remarks', ''));

                    $alarm->location = trim($location) !== ''
                        ? trim($location)
                        : ($alarm->location ?: 'Recovered location from PDF report');
                    $alarm->status = $status;
                    $alarm->alarm_type = $alarmType;
                    $alarm->last_test = $this->parseDate(Arr::get($a, 'last_test'));
                    $alarm->next_test_due = $this->parseDate(Arr::get($a, 'next_test_due'));
                    $alarm->notes = trim(collect([
                        $remarks !== '' ? "Remarks: {$remarks}" : null,
                        $statusRaw !== '' ? "PDF status: {$statusRaw}" : null,
                    ])->filter()->implode(' | ')) ?: null;

                    // Assign to first mentioned building code in location if present.
                    if (preg_match('/\b(ANC\s*\d+|\d{1,3})\b/i', $location, $m)) {
                        $bCode = strtoupper(str_replace(' ', '', $m[1]));
                        if (isset($buildingByCode[$bCode])) {
                            $alarm->building_id = $buildingByCode[$bCode]->id;
                        }
                    }

                    $alarm->save();
                    $totalAlarms++;
                }

                foreach ((array) Arr::get($report, 'rooms', []) as $r) {
                    $buildingCode = strtoupper(str_replace(' ', '', (string) Arr::get($r, 'building_no', '')));
                    if ($buildingCode === '' || !isset($buildingByCode[$buildingCode])) {
                        continue;
                    }

                    $building = $buildingByCode[$buildingCode];

                    $roomCode = trim((string) Arr::get($r, 'room_code', ''));
                    $roomName = trim((string) Arr::get($r, 'room_name', ''));

                    if ($roomCode === '' && $roomName === '') {
                        continue;
                    }

                    $query = FireSafetyRoom::query()
                        ->where('unified_school_id', $school->id)
                        ->where('building_id', $building->id)
                        ->where('room_code', $roomCode !== '' ? $roomCode : $roomName);

                    $room = $query->first();
                    if (!$room) {
                        $room = new FireSafetyRoom();
                        $room->unified_school_id = $school->id;
                        $room->building_id = $building->id;
                        $room->room_code = $roomCode !== '' ? $roomCode : $roomName;
                    }

                    $room->room_name = $roomName !== '' ? $roomName : ($room->room_name ?: $room->room_code);
                    $room->room_type = (string) Arr::get($r, 'room_type', 'others');
                    $room->floor_no = max(1, (int) Arr::get($r, 'floor_no', 1));

                    if (array_key_exists('has_secondary_exit', $r)) {
                        $room->has_secondary_exit = Arr::get($r, 'has_secondary_exit');
                    }
                    if (array_key_exists('has_smoke_detector', $r)) {
                        $room->has_smoke_detector = Arr::get($r, 'has_smoke_detector');
                    }
                    if ($room->has_secondary_exit === null) {
                        $room->has_secondary_exit = false;
                    }
                    if ($room->has_smoke_detector === null) {
                        $room->has_smoke_detector = false;
                    }

                    $room->save();

                    $totalRooms++;
                }

                foreach ((array) Arr::get($report, 'extinguishers', []) as $e) {
                    $code = trim((string) Arr::get($e, 'code', ''));
                    if ($code === '') {
                        continue;
                    }

                    $ext = FireSafetyExtinguisher::query()
                        ->where('unified_school_id', $school->id)
                        ->where('code', $code)
                        ->first();

                    if (!$ext) {
                        $ext = new FireSafetyExtinguisher();
                        $ext->unified_school_id = $school->id;
                        $ext->code = $code;
                    }

                    $ext->status = (string) Arr::get($e, 'status', 'maintenance');
                    $ext->status = strtolower(trim((string) Arr::get($e, 'status_raw', $ext->status)));
                    $ext->type = (string) Arr::get($e, 'type', 'ABC');
                    $ext->date_checked = $this->parseDate(Arr::get($e, 'date_checked')) ?: now()->format('Y-m-d');
                    $ext->evaluation_result = $ext->evaluation_result ?: 'Recovered from PDF report';
                    $ext->remarks = trim((string) Arr::get($e, 'remarks', '')) ?: $ext->remarks;

                    $buildingCode = strtoupper(str_replace(' ', '', (string) Arr::get($e, 'building_no', '')));
                    if ($buildingCode !== '' && isset($buildingByCode[$buildingCode])) {
                        $ext->building_id = $buildingByCode[$buildingCode]->id;
                    }

                    $location = (string) Arr::get($e, 'location', '');
                    $roomId = $this->resolveRoomIdFromLocation($school->id, $ext->building_id, $location);
                    if ($roomId) {
                        $ext->room_id = $roomId;
                    }

                    $ext->save();
                    $totalExts++;
                }

                $this->line("Recovered school: {$schoolName} ({$schoolIdCode})");
            }

            if ($dryRun) {
                DB::rollBack();
            } else {
                DB::commit();
            }

            $mode = $dryRun ? 'DRY RUN' : 'COMMITTED';
            $this->info("Import {$mode}: schools={$totalSchools}, buildings={$totalBuildings}, alarms={$totalAlarms}, extinguishers={$totalExts}, rooms={$totalRooms}");

            return self::SUCCESS;
        } catch (\Throwable $e) {
            DB::rollBack();
            $this->error('Import failed: ' . $e->getMessage());
            return self::FAILURE;
        }
    }

    private function parseDate(mixed $value): ?string
    {
        if (!is_string($value) || trim($value) === '') {
            return null;
        }

        try {
            return Carbon::parse($value)->format('Y-m-d');
        } catch (\Throwable) {
            return null;
        }
    }

    private function mapAlarmStatusToSchema(string $statusRaw): string
    {
        return match ($statusRaw) {
            'active' => 'active',
            'functional' => 'functional',
            'online' => 'online',
            'broken' => 'broken',
            'defective' => 'under_repair',
            'offline' => 'offline',
            'maintenance' => 'maintenance',
            default => 'maintenance',
        };
    }

    private function replaceSchoolFireSafetyChildren(int $schoolId): void
    {
        $extIds = FireSafetyExtinguisher::query()
            ->where('unified_school_id', $schoolId)
            ->pluck('id')
            ->all();

        $roomIds = FireSafetyRoom::query()
            ->where('unified_school_id', $schoolId)
            ->pluck('id')
            ->all();

        $alarmIds = FireSafetyAlarmSystem::query()
            ->where('unified_school_id', $schoolId)
            ->pluck('id')
            ->all();

        $buildingIds = FireSafetyBuilding::query()
            ->where('unified_school_id', $schoolId)
            ->pluck('id')
            ->all();

        if (!empty($extIds)) {
            if (Schema::hasTable('fire_safety_extinguisher_inspections')) {
                DB::table('fire_safety_extinguisher_inspections')->whereIn('extinguisher_id', $extIds)->delete();
            }
            if (Schema::hasTable('fire_safety_extinguisher_room_coverage')) {
                DB::table('fire_safety_extinguisher_room_coverage')->whereIn('extinguisher_id', $extIds)->delete();
            }
        }
        if (!empty($roomIds)) {
            if (Schema::hasTable('fire_safety_extinguisher_room_coverage')) {
                DB::table('fire_safety_extinguisher_room_coverage')->whereIn('room_id', $roomIds)->delete();
            }
        }
        if (!empty($alarmIds)) {
            if (Schema::hasTable('fire_safety_alarm_building')) {
                DB::table('fire_safety_alarm_building')->whereIn('alarm_id', $alarmIds)->delete();
            }
        }

        DB::table('firesafety_evacuationplans')->where('unified_school_id', $schoolId)->delete();
        DB::table('fire_safety_rooms')->where('unified_school_id', $schoolId)->delete();
        DB::table('firesafety_fire_extinguishers')->where('unified_school_id', $schoolId)->delete();
        DB::table('firesafety_alarm_systems')->where('unified_school_id', $schoolId)->delete();

        if (!empty($buildingIds)) {
            if (Schema::hasTable('fire_safety_alarm_building')) {
                DB::table('fire_safety_alarm_building')->whereIn('building_id', $buildingIds)->delete();
            }
        }
        DB::table('firesafety_buildings')->where('unified_school_id', $schoolId)->delete();
    }

    private function resolveRoomIdFromLocation(int $schoolId, ?int $buildingId, string $location): ?int
    {
        if (trim($location) === '') {
            return null;
        }

        if (preg_match('/\b(?:ROOM\s*)?([A-Z]?\d{2,3})\b/i', $location, $m)) {
            $code = strtoupper($m[1]);

            $query = FireSafetyRoom::query()
                ->where('unified_school_id', $schoolId)
                ->where(function ($q) use ($code) {
                    $q->where('room_code', $code)
                        ->orWhere('room_name', $code);
                });

            if ($buildingId) {
                $query->where('building_id', $buildingId);
            }

            $room = $query->first();
            if ($room) {
                return $room->id;
            }
        }

        return null;
    }
}
