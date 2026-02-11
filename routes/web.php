<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FireSafetyController;
use App\Http\Controllers\TyphoonController;
use App\Http\Controllers\IncidentController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Main dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Global User Management (Admin Only)
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/users', [DashboardController::class, 'getUsers'])->name('users.index');
    Route::post('/users', [DashboardController::class, 'storeUser'])->name('users.store');
    Route::get('/users/{id}', [DashboardController::class, 'getUser'])->name('users.show');
    Route::delete('/users/{id}', [DashboardController::class, 'deleteUser'])->name('users.destroy');
});

// Subsystem dashboards
// Fire Safety Routes
Route::prefix('fire-safety')->middleware(['auth', 'module.access:fire_safety'])->group(function () {
    Route::get('/dashboard', [FireSafetyController::class, 'dashboard'])->name('fire-safety.dashboard');
    Route::get('/alarm-systems', [FireSafetyController::class, 'alarmSystems'])->name('fire-safety.alarm-systems');
    Route::get('/extinguishers', [FireSafetyController::class, 'extinguishers'])->name('fire-safety.extinguishers');
    Route::get('/buildings', [FireSafetyController::class, 'buildings'])->name('fire-safety.buildings');
    Route::get('/evacuation-plans', [FireSafetyController::class, 'evacuationPlans'])->name('fire-safety.evacuation-plans');
    Route::post('/evacuation-plan/store', [FireSafetyController::class, 'storeEvacuationPlan'])->name('fire-safety.evacuation-plan.store');
    Route::get('/evacuation-plan/{id}', [FireSafetyController::class, 'getEvacuationPlan']);
    Route::get('/evacuation-plan/{id}/details', [FireSafetyController::class, 'getEvacuationPlanDetails']);
    Route::put('/evacuation-plan/{id}', [FireSafetyController::class, 'updateEvacuationPlan']);
    Route::delete('/evacuation-plan/{id}', [FireSafetyController::class, 'deleteEvacuationPlan']);
    Route::get('/building/{buildingId}/has-plan', [FireSafetyController::class, 'checkBuildingPlan']);
    Route::get('/building/{buildingId}/evacuation-data', [FireSafetyController::class, 'getBuildingEvacuationData']);
    Route::get('/school/{schoolId}/map-data', [FireSafetyController::class, 'getSchoolMapData']);
    Route::post('/school/{schoolId}/map-save', [FireSafetyController::class, 'saveMapLayout']);

    // Drill routes
    Route::get('/drill-history/{schoolId}', [FireSafetyController::class, 'getDrillHistory']);
    Route::get('/drill-buildings/{schoolId}', [FireSafetyController::class, 'getDrillBuildings']);
    Route::post('/drill/schedule', [FireSafetyController::class, 'scheduleDrill']);
    Route::get('/drill/{id}', [FireSafetyController::class, 'getDrill']);
    Route::delete('/drill/{id}/cancel', [FireSafetyController::class, 'cancelDrill']);

    // Stats routes
    Route::get('/plan-stats/{schoolId}', [FireSafetyController::class, 'getPlanStats']);
    Route::get('/evacuation-sidebar-stats/{schoolId}', [FireSafetyController::class, 'getEvacuationSidebarStats']);

    Route::get('/customization', [FireSafetyController::class, 'customization'])->name('fire-safety.customization');
    Route::get('/customization', [FireSafetyController::class, 'customization'])->name('fire-safety.customization');



    // System Configuration Routes
    Route::post('/config/{type}/order', [FireSafetyController::class, 'updateConfigOrder'])->name('fire-safety.config.order');
    Route::post('/config/{type}', [FireSafetyController::class, 'storeConfig'])->name('fire-safety.config.store');
    Route::put('/config/{type}/{id}', [FireSafetyController::class, 'updateConfig'])->name('fire-safety.config.update');
    Route::delete('/config/{type}/{id}', [FireSafetyController::class, 'deleteConfig'])->name('fire-safety.config.destroy');

    // Backup & Restore (Fire Safety module)
    Route::get('/backup/list', [FireSafetyController::class, 'listFireSafetyBackups'])->name('fire-safety.backup.list');
    Route::post('/backup/create', [FireSafetyController::class, 'createFireSafetyBackup'])->name('fire-safety.backup.create');
    Route::post('/backup/restore', [FireSafetyController::class, 'restoreFireSafetyBackup'])->name('fire-safety.backup.restore');
    Route::get('/schools/export', [FireSafetyController::class, 'exportSchools'])->name('fire-safety.schools.export');

    // AJAX routes for dynamic loading (specific /school/history before /school/{id})
    Route::get('/school/history', [FireSafetyController::class, 'getSchoolHistory'])->name('fire-safety.school.history')->middleware('auth');
    Route::get('/school/{id}', [FireSafetyController::class, 'getSchoolDetails'])->name('fire-safety.school.details');
    Route::get('/school/{id}/issues', [FireSafetyController::class, 'getSchoolIssues'])->name('fire-safety.school.issues');
    Route::post('/school/alert', [FireSafetyController::class, 'storeAlert'])->name('fire-safety.school.alert.store');
    Route::post('/school/event', [FireSafetyController::class, 'storeEvent'])->name('fire-safety.school.event.store');
    Route::post('/set-school/{id}', [FireSafetyController::class, 'setActiveSchool'])->name('fire-safety.set-school');

    // Report Printing Routes
    Route::get('/reports/school-summary', [FireSafetyController::class, 'printSchoolSummary'])->name('fire-safety.report.school-summary');
    Route::get('/reports/building-summary/{schoolId}', [FireSafetyController::class, 'printBuildingSummary'])->name('fire-safety.report.building-summary');
    Route::get('/reports/alarm-details/{schoolId}', [FireSafetyController::class, 'printAlarmDetails'])->name('fire-safety.report.alarm-details');
    Route::get('/reports/extinguisher-details/{schoolId}', [FireSafetyController::class, 'printExtinguisherDetails'])->name('fire-safety.report.extinguisher-details');

    // School management (Customization page)
    Route::post('/school', [FireSafetyController::class, 'storeSchool'])
        ->name('fire-safety.school.store')
        ->middleware('auth');
    Route::put('/school/{id}', [FireSafetyController::class, 'updateSchool'])
        ->name('fire-safety.school.update')
        ->middleware('auth');
    Route::put('/my-school/update', [FireSafetyController::class, 'updateSchool'])
        ->name('fire-safety.school.my.update')
        ->middleware('auth');
    Route::delete('/school/{id}', [FireSafetyController::class, 'destroySchool'])
        ->name('fire-safety.school.destroy')
        ->middleware('auth');

    // Add middleware to protect AJAX routes
    Route::middleware(['auth'])->group(function () {
        // Your AJAX routes here if needed
    });

    // Alarm System Routes
    Route::get('/buildings/{schoolId}', [FireSafetyController::class, 'getBuildings']);
    Route::get('/alarm/{id}', [FireSafetyController::class, 'getAlarm']);
    Route::post('/alarm/store', [FireSafetyController::class, 'storeAlarm'])->name('fire-safety.alarm.store');
    Route::put('/alarm/{id}', [FireSafetyController::class, 'updateAlarm']);
    Route::post('/alarm/{id}/test', [FireSafetyController::class, 'testAlarm']);
    Route::post('/alarm/{id}/remove', [FireSafetyController::class, 'removeAlarm'])->name('fire-safety.alarm.remove');
    Route::get('/alarm/history/{schoolId}', [FireSafetyController::class, 'getAlarmHistory'])->name('fire-safety.alarm.history');
    Route::get('/check-alarm-code/{schoolId}/{code}', [FireSafetyController::class, 'checkAlarmCode']);

    // Building Routes
    Route::get('/building/{id}', [FireSafetyController::class, 'getBuilding']);
    Route::post('/building/store', [FireSafetyController::class, 'storeBuilding'])->name('fire-safety.building.store');
    Route::post('/building/{id}/update', [FireSafetyController::class, 'updateBuilding'])->name('fire-safety.building.update');
    Route::get('/inspections/{schoolId}', [FireSafetyController::class, 'getInspections']);
    Route::get('/compliance-stats/{schoolId}', [FireSafetyController::class, 'getComplianceStats']);
    Route::get('/sidebar-stats/{schoolId}', [FireSafetyController::class, 'getSidebarStats']);
    Route::get('/buildings-list/{schoolId}', [FireSafetyController::class, 'getBuildingsList']);
    Route::get('/building/history/{schoolId}', [FireSafetyController::class, 'getBuildingHistory'])->name('fire-safety.building.history');
    Route::post('/inspection/schedule', [FireSafetyController::class, 'scheduleInspection'])->name('fire-safety.inspection.schedule');

    // Inspection Routes (used by Buildings page JS)
    Route::get('/inspection/{id}', [FireSafetyController::class, 'getInspection'])->name('fire-safety.inspection.show');
    Route::delete('/inspection/{id}/cancel', [FireSafetyController::class, 'cancelInspection'])->name('fire-safety.inspection.cancel');
    Route::get('/inspection/{id}/checklist', [FireSafetyController::class, 'inspectionChecklist'])->name('fire-safety.inspection.checklist');

    // Room-based Fire Extinguisher Routes (AJAX)
    Route::get('/rooms/{buildingId}', [FireSafetyController::class, 'getRooms'])->name('fire-safety.rooms.list');
    Route::post('/room/store', [FireSafetyController::class, 'storeRoom'])->name('fire-safety.room.store');
    Route::get('/room/{id}', [FireSafetyController::class, 'getRoom'])->name('fire-safety.room.show');
    Route::post('/room/{id}/update', [FireSafetyController::class, 'updateRoom'])->name('fire-safety.room.update');
    Route::get('/room/{id}/candidates', [FireSafetyController::class, 'getNearestCandidateRooms'])->name('fire-safety.room.candidates');
    Route::post('/extinguisher/store', [FireSafetyController::class, 'storeExtinguisher'])->name('fire-safety.extinguisher.store');
    Route::post('/extinguisher/{id}/update', [FireSafetyController::class, 'updateExtinguisher'])->name('fire-safety.extinguisher.update');
    Route::post('/extinguisher/{id}/remove', [FireSafetyController::class, 'removeExtinguisher'])->name('fire-safety.extinguisher.remove');
    Route::get('/extinguisher/history/{schoolId}', [FireSafetyController::class, 'getExtinguisherHistory'])->name('fire-safety.extinguisher.history');
    Route::get('/extinguisher/inspections/{schoolId}', [FireSafetyController::class, 'getRecentExtinguisherInspections'])->name('fire-safety.extinguisher.inspections');
});



//Typhoon/Flood Routes
Route::prefix('typhoon')->middleware(['auth', 'module.access:typhoon_flood'])->group(function () {
    Route::get('/dashboard', [TyphoonController::class, 'dashboard'])->name('typhoon.dashboard');
    // Add other typhoon routes here
});





//Incident Routes
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/incidents/dashboard', [IncidentController::class, 'dashboard'])->name('incidents.dashboard');
    Route::get('/incidents/print', [IncidentController::class, 'printMonth'])->name('incidents.print');
    Route::post('/incidents/store', [IncidentController::class, 'store'])->name('incidents.store');
    Route::get('/incidents/date/{date}', [IncidentController::class, 'getDateIncidents'])->name('incidents.date');
    Route::delete('/incidents/{id}', [IncidentController::class, 'destroy'])->name('incidents.destroy');
    Route::get('/incidents/search-schools', [IncidentController::class, 'searchSchools'])->name('incidents.search-schools');
    Route::get('/incidents/export', [IncidentController::class, 'export'])->name('incidents.export');
    Route::post('/incidents/import', [IncidentController::class, 'import'])->name('incidents.import');
    Route::post('/incidents/types', [IncidentController::class, 'storeIncidentType'])->name('incidents.types.store');
    Route::post('/incidents/statuses', [IncidentController::class, 'storeIncidentStatus'])->name('incidents.statuses.store');
    // Checklist APIs
    Route::get('/incidents/checklist', [IncidentController::class, 'getChecklist'])->name('incidents.checklist.index');
    Route::post('/incidents/checklist', [IncidentController::class, 'storeChecklistItem'])->name('incidents.checklist.store');
    Route::put('/incidents/checklist/{id}', [IncidentController::class, 'updateChecklistItem'])->name('incidents.checklist.update');
    Route::delete('/incidents/checklist/{id}', [IncidentController::class, 'destroyChecklistItem'])->name('incidents.checklist.destroy');
});

// Comprehensive School Safety (placeholder – replace with real controller when module is ready)
Route::middleware(['auth'])->group(function () {
    Route::get('/comprehensive-school-safety/dashboard', function () {
        return redirect()->route('dashboard')->with('info', 'Comprehensive School Safety module is under development.');
    })->name('comprehensive-school-safety.dashboard');
});

// Hazard Mapping (placeholder – replace with real controller when module is ready)
Route::middleware(['auth'])->group(function () {
    Route::get('/hazard-mapping/dashboard', function () {
        return redirect()->route('dashboard')->with('info', 'Hazard Mapping module is under development.');
    })->name('hazard-mapping.dashboard');
});
