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
    Route::get('/customization', [FireSafetyController::class, 'customization'])->name('fire-safety.customization');
    Route::get('/customization', [FireSafetyController::class, 'customization'])->name('fire-safety.customization');
    


    // System Configuration Routes
    Route::post('/config/{type}/order', [FireSafetyController::class, 'updateConfigOrder'])->name('fire-safety.config.order');
    Route::post('/config/{type}', [FireSafetyController::class, 'storeConfig'])->name('fire-safety.config.store');
    Route::put('/config/{type}/{id}', [FireSafetyController::class, 'updateConfig'])->name('fire-safety.config.update');
    Route::delete('/config/{type}/{id}', [FireSafetyController::class, 'deleteConfig'])->name('fire-safety.config.destroy');
    Route::get('/schools/export', [FireSafetyController::class, 'exportSchools'])->name('fire-safety.schools.export');

    // AJAX routes for dynamic loading
    Route::get('/school/{id}', [FireSafetyController::class, 'getSchoolDetails'])->name('fire-safety.school.details');
    Route::get('/school/{id}/issues', [FireSafetyController::class, 'getSchoolIssues'])->name('fire-safety.school.issues');

    Route::post('/fire-safety/school/store', [FireSafetyController::class, 'storeSchool'])
        ->name('fire-safety.school.store')
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
    Route::delete('/alarm/{id}', [FireSafetyController::class, 'destroyAlarm']);
    Route::get('/check-alarm-code/{code}', [FireSafetyController::class, 'checkAlarmCode']);

    // Building Routes
    Route::get('/building/{id}', [FireSafetyController::class, 'getBuilding']);
    Route::post('/building/store', [FireSafetyController::class, 'storeBuilding'])->name('fire-safety.building.store');
    Route::get('/inspections/{schoolId}', [FireSafetyController::class, 'getInspections']);
    Route::get('/compliance-stats/{schoolId}', [FireSafetyController::class, 'getComplianceStats']);
    Route::get('/sidebar-stats/{schoolId}', [FireSafetyController::class, 'getSidebarStats']);
    Route::get('/buildings-list/{schoolId}', [FireSafetyController::class, 'getBuildingsList']);
    Route::post('/inspection/schedule', [FireSafetyController::class, 'scheduleInspection'])->name('fire-safety.inspection.schedule');

    // Inspection Routes (used by Buildings page JS)
    Route::get('/inspection/{id}', [FireSafetyController::class, 'getInspection'])->name('fire-safety.inspection.show');
    Route::post('/inspection/{id}/cancel', [FireSafetyController::class, 'cancelInspection'])->name('fire-safety.inspection.cancel');
    Route::get('/inspection/{id}/checklist', [FireSafetyController::class, 'inspectionChecklist'])->name('fire-safety.inspection.checklist');

    // Room-based Fire Extinguisher Routes (AJAX)
    Route::get('/rooms/{buildingId}', [FireSafetyController::class, 'getRooms'])->name('fire-safety.rooms.list');
    Route::post('/room/store', [FireSafetyController::class, 'storeRoom'])->name('fire-safety.room.store');
    Route::post('/extinguisher/store', [FireSafetyController::class, 'storeExtinguisher'])->name('fire-safety.extinguisher.store');
    Route::post('/extinguisher/{id}/update', [FireSafetyController::class, 'updateExtinguisher'])->name('fire-safety.extinguisher.update');
    Route::get('/extinguisher/inspections/{schoolId}', [FireSafetyController::class, 'getRecentExtinguisherInspections'])->name('fire-safety.extinguisher.inspections');
});



//Typhoon/Flood Routes
Route::prefix('typhoon')->middleware(['auth', 'module.access:typhoon_flood'])->group(function () {
    Route::get('/dashboard', [TyphoonController::class, 'dashboard'])->name('typhoon.dashboard');
    // Add other typhoon routes here
});





//Incident Routes
Route::prefix('incidents')->middleware(['auth', 'module.access:incidents'])->group(function () {
    Route::get('/dashboard', [IncidentController::class, 'dashboard'])->name('incidents.dashboard');
    // Add other incident routes here
});
