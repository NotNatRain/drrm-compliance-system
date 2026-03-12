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

// Password Reset Code Verification
Route::get('password/verify', [\App\Http\Controllers\Auth\PasswordResetCodeController::class, 'showVerifyForm'])->name('password.verify-form');
Route::post('password/verify', [\App\Http\Controllers\Auth\PasswordResetCodeController::class, 'verifyCode'])->name('password.verify-code');

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Main dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// User account management
Route::middleware(['auth'])->group(function () {
    // Admins see all users; contributors/viewers see only themselves.
    Route::get('/users', [DashboardController::class, 'getUsers'])->name('users.index');
    Route::get('/users/{id}', [DashboardController::class, 'getUser'])->name('users.show');
    Route::put('/users/{id}', [DashboardController::class, 'updateUser'])->name('users.update');
});

// Global User Management (Admin Only actions)
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::post('/users', [DashboardController::class, 'storeUser'])->name('users.store');
    Route::post('/users/{id}/toggle-status', [DashboardController::class, 'toggleUserStatus'])->name('users.toggle-status');
    Route::post('/users/{id}/assign', [DashboardController::class, 'assignAccess'])->name('users.assign');
    Route::delete('/users/{id}', [DashboardController::class, 'deleteUser'])->name('users.destroy');
});

// Announcement Routes (Admin Only)
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::post('/announcements', [DashboardController::class, 'storeAnnouncement'])->name('announcements.store');
    Route::delete('/announcements/{id}', [DashboardController::class, 'deleteAnnouncement'])->name('announcements.destroy');
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
    Route::post('/school/{schoolId}/map-notify', [FireSafetyController::class, 'notifyMapUpdate'])->name('fire-safety.map.notify');

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

    // SPECIFIC ROUTES FIRST - Put these BEFORE the general school routes
    Route::get('/extinguisher/{id}', [FireSafetyController::class, 'getExtinguisher'])->name('fire-safety.extinguisher.show');
    Route::get('/building/{buildingId}/rooms-with-coverage', [FireSafetyController::class, 'getBuildingRoomsWithCoverage'])->name('fire-safety.building.rooms-with-coverage');

    // THEN the AJAX routes for dynamic loading (specific /school/history before /school/{id})
    Route::get('/school/history', [FireSafetyController::class, 'getSchoolHistory'])->name('fire-safety.school.history')->middleware('auth');
    Route::get('/school/{id}', [FireSafetyController::class, 'getSchoolDetails'])->name('fire-safety.school.details');
    Route::get('/school/{id}/issues', [FireSafetyController::class, 'getSchoolIssues'])->name('fire-safety.school.issues');
    Route::post('/school/alert', [FireSafetyController::class, 'storeAlert'])->name('fire-safety.school.alert.store');
    Route::post('/school/event', [FireSafetyController::class, 'storeEvent'])->name('fire-safety.school.event.store');
    Route::post('/notification/reply', [FireSafetyController::class, 'replyToNotification'])->name('fire-safety.notification.reply');
    Route::get('/notifications', [FireSafetyController::class, 'getNotifications'])->name('fire-safety.notifications');
    Route::get('/notifications-page', [FireSafetyController::class, 'notificationsPage'])->name('fire-safety.notifications.page');
    Route::post('/notification/{id}/mark-read', [FireSafetyController::class, 'markNotificationRead'])->name('fire-safety.notification.mark-read');
    Route::post('/notifications/mark-all-read', [FireSafetyController::class, 'markAllNotificationsRead'])->name('fire-safety.notifications.mark-all-read');
    Route::post('/set-school/{id}', [FireSafetyController::class, 'setActiveSchool'])->name('fire-safety.set-school');

    // Report Printing Routes
    Route::get('/reports/school-summary', [FireSafetyController::class, 'printSchoolSummary'])->name('fire-safety.report.school-summary');
    Route::get('/reports/building-summary/{schoolId}', [FireSafetyController::class, 'printBuildingSummary'])->name('fire-safety.report.building-summary');
    Route::get('/reports/alarm-details/{schoolId}', [FireSafetyController::class, 'printAlarmDetails'])->name('fire-safety.report.alarm-details');
    Route::get('/reports/extinguisher-details/{schoolId}', [FireSafetyController::class, 'printExtinguisherDetails'])->name('fire-safety.report.extinguisher-details');
    Route::get('/reports/evacuation-plans/{schoolId}', [FireSafetyController::class, 'printEvacuationPlans'])->name('fire-safety.report.evacuation-plans');

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
    Route::get('/alarm/{id}', [FireSafetyController::class, 'getAlarm'])->name('fire-safety.alarm.show');
    Route::post('/alarm/store', [FireSafetyController::class, 'storeAlarm'])->name('fire-safety.alarm.store');
    Route::post('/alarm/{id}/update', [FireSafetyController::class, 'updateAlarm'])->name('fire-safety.alarm.update');
    Route::post('/alarm/{id}/test', [FireSafetyController::class, 'testAlarm'])->name('fire-safety.alarm.test');
    Route::post('/alarm/{id}/remove', [FireSafetyController::class, 'removeAlarm'])->name('fire-safety.alarm.remove');
    Route::get('/alarm/history/{schoolId}', [FireSafetyController::class, 'getAlarmHistory'])->name('fire-safety.alarm.history');
    Route::get('/check-alarm-code/{schoolId}/{code}', [FireSafetyController::class, 'checkAlarmCode']);

    // Building Routes
    Route::get('/building/{id}', [FireSafetyController::class, 'getBuilding'])->name('fire-safety.building.show');
    Route::post('/building/store', [FireSafetyController::class, 'storeBuilding'])->name('fire-safety.building.store');
    Route::post('/building/{id}/update', [FireSafetyController::class, 'updateBuilding'])->name('fire-safety.building.update');
    Route::post('/building/{id}/remove', [FireSafetyController::class, 'removeBuilding'])->name('fire-safety.building.remove');
    Route::get('/inspections/{schoolId}', [FireSafetyController::class, 'getInspections']);
    Route::get('/compliance-stats/{schoolId}', [FireSafetyController::class, 'getComplianceStats']);
    Route::get('/sidebar-stats/{schoolId}', [FireSafetyController::class, 'getSidebarStats']);
    Route::get('/buildings-list/{schoolId}', [FireSafetyController::class, 'getBuildingsList']);
    Route::get('/building/history/{schoolId}', [FireSafetyController::class, 'getBuildingHistory'])->name('fire-safety.building.history');
    Route::get('/building/removed-history/{schoolId}', [FireSafetyController::class, 'getRemovedBuildingsHistory'])->name('fire-safety.building.removed-history');
    // Inspection Routes (School-wide Drills)
    Route::get('/inspection/{id}', [FireSafetyController::class, 'getInspection'])->name('fire-safety.inspection.show');
    Route::post('/inspection/store', [FireSafetyController::class, 'storeInspection'])->name('fire-safety.inspection.store');
    Route::put('/inspection/{id}/update', [FireSafetyController::class, 'updateInspection'])->name('fire-safety.inspection.update');
    Route::get('/inspection/{id}/checklist', [FireSafetyController::class, 'inspectionChecklist'])->name('fire-safety.inspection.checklist');
    Route::get('/inspection/{id}/print', [FireSafetyController::class, 'printInspection'])->name('fire-safety.inspection.print');

    // Room-based Fire Extinguisher Routes (AJAX)
    Route::get('/rooms/{buildingId}', [FireSafetyController::class, 'getRooms'])->name('fire-safety.rooms.list');
    Route::post('/room/store', [FireSafetyController::class, 'storeRoom'])->name('fire-safety.room.store');
    Route::get('/room/{id}', [FireSafetyController::class, 'getRoom'])->name('fire-safety.room.show');
    Route::post('/room/{id}/update', [FireSafetyController::class, 'updateRoom'])->name('fire-safety.room.update');
    Route::get('/room/{id}/candidates', [FireSafetyController::class, 'getNearestCandidateRooms'])->name('fire-safety.room.candidates');
    Route::post('/room/{id}/approve', [FireSafetyController::class, 'approveRoom'])->name('fire-safety.room.approve');
    Route::post('/room/{id}/reject', [FireSafetyController::class, 'rejectRoom'])->name('fire-safety.room.reject');
    Route::post('/room/{id}/remove', [FireSafetyController::class, 'removeRoom'])->name('fire-safety.room.remove');
    Route::post('/extinguisher/store', [FireSafetyController::class, 'storeExtinguisher'])->name('fire-safety.extinguisher.store');
    Route::get('/extinguisher/{id}', [FireSafetyController::class, 'getExtinguisher'])->name('fire-safety.extinguisher.show');
    Route::post('/extinguisher/{id}/update', [FireSafetyController::class, 'updateExtinguisher'])->name('fire-safety.extinguisher.update');
    Route::post('/extinguisher/{id}/unassign', [FireSafetyController::class, 'unassignExtinguisher'])->name('fire-safety.extinguisher.unassign');
    Route::post('/extinguisher/{id}/remove', [FireSafetyController::class, 'removeExtinguisher'])->name('fire-safety.extinguisher.remove');
    Route::post('/extinguisher/{id}/transfer', [FireSafetyController::class, 'transferExtinguisher'])->name('fire-safety.extinguisher.transfer');
    Route::get('/extinguisher/history/{schoolId}', [FireSafetyController::class, 'getExtinguisherHistory'])->name('fire-safety.extinguisher.history');
    Route::get('/room/history/{schoolId}', [FireSafetyController::class, 'getRoomHistory'])->name('fire-safety.room.history');
    Route::get('/extinguisher/inspections/{schoolId}', [FireSafetyController::class, 'getRecentExtinguisherInspections'])->name('fire-safety.extinguisher.inspections');
    Route::get('/building/{buildingId}/rooms-with-coverage', [FireSafetyController::class, 'getBuildingRoomsWithCoverage'])->name('fire-safety.building.rooms-with-coverage');
    Route::get('/recent-room-updates/{schoolId}', [FireSafetyController::class, 'getRecentRoomUpdates']);
});



//Typhoon/Flood Routes
Route::prefix('typhoon')->middleware(['auth', 'module.access:typhoon_flood'])->group(function () {
    Route::get('/dashboard', [TyphoonController::class, 'dashboard'])->name('typhoon.dashboard');
    Route::get('/choose-school', [TyphoonController::class, 'chooseSchool'])->name('typhoon.choose-school');
    Route::post('/set-school/{id}', [TyphoonController::class, 'setActiveSchool'])->name('typhoon.set-school');
    Route::post('/families', [TyphoonController::class, 'storeFamily'])->name('typhoon.families.store');
    Route::get('/evacuation-center/{id}', [TyphoonController::class, 'showEvacuationCenter'])->name('typhoon.evacuation-center.show');
    Route::post('/evacuation-center', [TyphoonController::class, 'storeEvacuationCenter'])->name('typhoon.evacuation-center.store');
    Route::put('/evacuation-center/{id}', [TyphoonController::class, 'updateEvacuationCenter'])->name('typhoon.evacuation-center.update');
    Route::get('/realtime', [TyphoonController::class, 'realtime'])->name('typhoon.realtime');
    // Add other typhoon routes here
});




//Incident Routes
Route::middleware(['auth', 'module.access:incident_checklist'])->group(function () {
    Route::get('/incidents/dashboard', [IncidentController::class, 'dashboard'])->name('incidents.dashboard');
    Route::get('/incidents/print', [IncidentController::class, 'printMonth'])->name('incidents.print')->middleware('role:admin');
    Route::post('/incidents/store', [IncidentController::class, 'store'])->name('incidents.store')->middleware('role:admin');
    Route::get('/incidents/date/{date}', [IncidentController::class, 'getDateIncidents'])->name('incidents.date');
    Route::delete('/incidents/{id}', [IncidentController::class, 'destroy'])->name('incidents.destroy')->middleware('role:admin');
    Route::get('/incidents/search-schools', [IncidentController::class, 'searchSchools'])->name('incidents.search-schools');
    Route::get('/incidents/export', [IncidentController::class, 'export'])->name('incidents.export')->middleware('role:admin');
    Route::post('/incidents/import', [IncidentController::class, 'import'])->name('incidents.import')->middleware('role:admin');
    Route::post('/incidents/types', [IncidentController::class, 'storeIncidentType'])->name('incidents.types.store')->middleware('role:admin');
    Route::put('/incidents/types/{id}', [IncidentController::class, 'updateIncidentType'])->name('incidents.types.update')->middleware('role:admin');
    Route::post('/incidents/statuses', [IncidentController::class, 'storeIncidentStatus'])->name('incidents.statuses.store')->middleware('role:admin');
    Route::put('/incidents/statuses/{id}', [IncidentController::class, 'updateIncidentStatus'])->name('incidents.statuses.update')->middleware('role:admin');
    // Checklist APIs (view/update only for assigned users)
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
