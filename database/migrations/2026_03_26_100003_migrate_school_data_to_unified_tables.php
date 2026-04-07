<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * This migration consolidates data from the 4 source tables into the new `schools` table structure.
     */
    public function up(): void
    {
        DB::transaction(function () {
            // We'll track maps for module-specific IDs to the new unified IDs
            $fsMap = [];
            $cmprMap = [];
            $incMap = [];
            $ecMap = [];

            // 1. Migrate Fire Safety Schools (Primary Source)
            $fsSchools = DB::table('firesafety_school_information')->get();
            foreach ($fsSchools as $fs) {
                $newId = DB::table('schools')->insertGetId([
                    'school_id'               => $fs->school_id,
                    'school_id_number'        => $fs->school_id, // Syncing both ID columns initially
                    'school_name'             => $fs->school_name,
                    'address'                 => $fs->address,
                    'school_head'             => $fs->school_head,
                    'drrm_coordinator'        => $fs->school_drrm_coordinator,
                    'fire_safety_status'      => $fs->status,
                    'evacuation_map_layout'   => $fs->evacuation_map_layout,
                    'attached_evacuation_map' => $fs->attached_evacuation_map,
                    'alerts'                  => $fs->alerts,
                    'events'                  => $fs->events,
                    'replies'                 => $fs->replies,
                    'created_at'              => $fs->created_at,
                    'updated_at'              => $fs->updated_at,
                ]);

                $fsMap[$fs->id] = $newId;

                // Store original FS tracking info
                DB::table('school_specifics_information')->updateOrInsert([
                    'school_id'  => $newId,
                    'module'     => 'fire_safety',
                    'key'        => 'original_fire_safety_id',
                ], [
                    'value'      => (string)$fs->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // 2. Migrate Comprehensive Assessment Schools (Merge or Insert)
            $cmprSchools = DB::table('cmpr_schl_sfty_schools')->get();
            foreach ($cmprSchools as $cmpr) {
                $existing = DB::table('schools')
                    ->where('school_id', $cmpr->school_id_number)
                    ->orWhere('school_id_number', $cmpr->school_id_number)
                    ->orWhereRaw('UPPER(school_name) = ?', [trim(strtoupper($cmpr->name))])
                    ->first();

                if ($existing) {
                    // Update missing information in existing school record
                    DB::table('schools')->where('id', $existing->id)->update([
                        'school_id_number' => $existing->school_id_number ?? $cmpr->school_id_number,
                        'address'          => $existing->address ?? $cmpr->address,
                        'district'         => $existing->district ?? $cmpr->district,
                        'division'         => $existing->division ?? $cmpr->division,
                        'region'           => $existing->region ?? $cmpr->region,
                        'school_head'      => $existing->school_head ?? $cmpr->school_head,
                        'contact_number'   => $existing->contact_number ?? $cmpr->contact_number,
                        'updated_at'       => now(),
                    ]);
                    $currentSchoolId = $existing->id;
                } else {
                    $newId = DB::table('schools')->insertGetId([
                        'school_id_number' => $cmpr->school_id_number,
                        'school_name'      => $cmpr->name,
                        'address'          => $cmpr->address,
                        'district'         => $cmpr->district,
                        'division'         => $cmpr->division,
                        'region'           => $cmpr->region,
                        'school_head'      => $cmpr->school_head,
                        'contact_number'   => $cmpr->contact_number,
                        'created_at'       => $cmpr->created_at,
                        'updated_at'       => $cmpr->updated_at,
                    ]);
                    $currentSchoolId = $newId;
                }
                $cmprMap[$cmpr->id] = $currentSchoolId;

                // Specifics tracking
                DB::table('school_specifics_information')->updateOrInsert([
                    'school_id'  => $currentSchoolId,
                    'module'     => 'comprehensive',
                    'key'        => 'original_cmpr_school_id',
                ], [
                    'value'      => (string)$cmpr->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // 3. Migrate Incident Schools
            $incSchools = DB::table('incident_schools')->get();
            $legitIncidentSchools = [
                'OLONGAPO CITY NATIONAL HIGH SCHOOL',
                'Gordon Heights National High School'
            ];

            foreach ($incSchools as $inc) {
                $trimmedName = trim($inc->name);
                $existing = DB::table('schools')
                    ->whereRaw('UPPER(school_name) = ?', [strtoupper($trimmedName)])
                    ->first();

                $isLegit = in_array(strtoupper($trimmedName), array_map('strtoupper', $legitIncidentSchools));

                if ($existing) {
                    // Merge data
                    DB::table('schools')->where('id', $existing->id)->update([
                        'incident_count'     => $inc->incident_count,
                        'last_incident_date' => $inc->last_incident_date,
                        'district'           => $existing->district ?? $inc->district,
                        'division'           => $existing->division ?? $inc->division,
                        'region'             => $existing->region ?? $inc->region,
                        'updated_at'         => now(),
                    ]);
                    $currentSchoolId = $existing->id;
                } else {
                    // New record
                    $newId = DB::table('schools')->insertGetId([
                        'school_name'        => $inc->name,
                        'district'           => $inc->district,
                        'division'           => $inc->division,
                        'region'             => $inc->region,
                        'incident_count'     => $inc->incident_count,
                        'last_incident_date' => $inc->last_incident_date,
                        'created_at'         => $inc->created_at,
                        'updated_at'         => $inc->updated_at,
                    ]);
                    $currentSchoolId = $newId;

                    // Tag according to legit rule if not existing and not OCNHS/GHNHS
                    if (!$isLegit) {
                        DB::table('school_specifics_information')->updateOrInsert([
                            'school_id'  => $currentSchoolId,
                            'module'     => 'incident',
                            'key'        => 'marked_for_deletion',
                        ], [
                            'value'      => 'true',
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
                $incMap[$inc->id] = $currentSchoolId;

                // Specs
                DB::table('school_specifics_information')->updateOrInsert([
                    'school_id'  => $currentSchoolId,
                    'module'     => 'incident',
                    'key'        => 'original_incident_school_id',
                ], [
                    'value'      => (string)$inc->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // 4. Migrate Typhoon/Flood Evacuation Centers
            $centers = DB::table('typ_fld_evacuation_centers')->get();
            foreach ($centers as $ec) {
                // This table links to `firesafety_school_information.id`
                $mappedId = $fsMap[$ec->school_id] ?? null;

                if ($mappedId) {
                    // Update existing record
                    DB::table('schools')->where('id', $mappedId)->update([
                        'identification'                 => $ec->identification,
                        'evacuation_identification'      => $ec->identification,
                        'evacuation_location'            => $ec->location,
                        'evacuation_capacity'            => $ec->capacity,
                        'operational_status'             => $ec->operational_status,
                        'evacuation_status'              => $ec->usage_status,
                        'occupancy_safety'               => $ec->occupancy_safety,
                        'emergency_resources'            => $ec->emergency_resources,
                        'emergency_resources_status'     => $ec->emergency_resources_usage_status,
                        'needs_summary'                  => $ec->needs_summary,
                        'monitoring_status'              => $ec->monitoring_status,
                        'reports_status'                 => $ec->reports_status,
                        'updated_at'                     => now(),
                    ]);
                } else {
                    // Try matching by identification string
                    $identificationMatch = DB::table('schools')
                        ->where('school_id', $ec->identification)
                        ->orWhere('school_id_number', $ec->identification)
                        ->first();

                    if ($identificationMatch) {
                        DB::table('schools')->where('id', $identificationMatch->id)->update([
                            'identification'                 => $ec->identification,
                            'evacuation_identification'      => $ec->identification,
                            'evacuation_location'            => $ec->location,
                            'evacuation_capacity'            => $ec->capacity,
                            'operational_status'             => $ec->operational_status,
                            'evacuation_status'              => $ec->usage_status,
                            'occupancy_safety'               => $ec->occupancy_safety,
                            'emergency_resources'            => $ec->emergency_resources,
                            'emergency_resources_status'     => $ec->emergency_resources_usage_status,
                            'needs_summary'                  => $ec->needs_summary,
                            'monitoring_status'              => $ec->monitoring_status,
                            'reports_status'                 => $ec->reports_status,
                            'updated_at'                     => now(),
                        ]);
                        $mappedId = $identificationMatch->id;
                    } else {
                        // Create orphan center record
                        $newId = DB::table('schools')->insertGetId([
                            'school_name'                    => $ec->identification ?: ('EC ' . $ec->id),
                            'identification'                 => $ec->identification,
                            'evacuation_identification'      => $ec->identification,
                            'evacuation_location'            => $ec->location,
                            'evacuation_capacity'            => $ec->capacity,
                            'operational_status'             => $ec->operational_status,
                            'evacuation_status'              => $ec->usage_status,
                            'occupancy_safety'               => $ec->occupancy_safety,
                            'emergency_resources'            => $ec->emergency_resources,
                            'emergency_resources_status'     => $ec->emergency_resources_usage_status,
                            'needs_summary'                  => $ec->needs_summary,
                            'monitoring_status'              => $ec->monitoring_status,
                            'reports_status'                 => $ec->reports_status,
                            'created_at'                     => $ec->created_at,
                            'updated_at'                     => $ec->updated_at,
                        ]);
                        $mappedId = $newId;
                    }
                }
                $ecMap[$ec->id] = $mappedId;

                // Specifics
                DB::table('school_specifics_information')->updateOrInsert([
                    'school_id'  => $mappedId,
                    'module'     => 'typhoon_flood',
                    'key'        => 'original_evacuation_center_id',
                ], [
                    'value'      => (string)$ec->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Truncate the new tables - proceed with caution as this deletes data
        DB::table('school_specifics_information')->truncate();
        DB::table('schools')->truncate();
    }
};
