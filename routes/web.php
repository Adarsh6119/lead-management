<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\AccountingController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\EmployeePerformanceController;
use App\Http\Controllers\CabTypeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Leads Module
    Route::get('/leads', [LeadController::class, 'index'])->name('leads.index');
    Route::get('/leads/create', [LeadController::class, 'create'])->name('leads.create');
    Route::post('/leads', [LeadController::class, 'store'])->name('leads.store');
    Route::get('/leads/check-duplicate', [LeadController::class, 'checkDuplicate'])->name('leads.check-duplicate');
    Route::get('/leads/{id}', [LeadController::class, 'show'])->name('leads.show');
    Route::post('/leads/{id}/remark', [LeadController::class, 'addRemark'])->name('leads.remark');
    Route::post('/leads/{id}/tl-note', [LeadController::class, 'addTlNote'])->name('leads.tl-note');
    Route::post('/leads/{id}/convert', [LeadController::class, 'convertToBooking'])->name('leads.convert');

    // Bookings Module
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/{id}', [BookingController::class, 'show'])->name('bookings.show');
    Route::get('/bookings/{id}/voucher', [BookingController::class, 'voucher'])->name('bookings.voucher');
    Route::put('/bookings/{id}', [BookingController::class, 'update'])->name('bookings.update');

    // Accounting & GST Module (Accountant & Admin Only - Restricted from Head/TL)
    Route::get('/accounting', [AccountingController::class, 'index'])->name('accounting.index');
    Route::post('/accounting/{id}/update', [AccountingController::class, 'updateStatus'])->name('accounting.update');

    // Employee & TL Monitoring Management
    Route::get('/employees', [EmployeeController::class, 'index'])->name('employees.index');
    Route::get('/employees/performance', [EmployeePerformanceController::class, 'index'])->name('employees.performance');
    Route::get('/employees/meeting-notes/{employeeId?}', [EmployeePerformanceController::class, 'meetingNotes'])->name('employees.meeting-notes');
    Route::post('/employees/meeting-notes/{employeeId}', [EmployeePerformanceController::class, 'storeMeetingNote'])->name('employees.store-meeting-note');
    Route::post('/employees/{id}/toggle-access', [EmployeeController::class, 'toggleAccess'])->name('employees.toggle-access');
    Route::post('/employees/{id}/target', [EmployeePerformanceController::class, 'updateTarget'])->name('employees.update-target');
    Route::get('/employees/create', [EmployeeController::class, 'create'])->name('employees.create');
    Route::post('/employees', [EmployeeController::class, 'store'])->name('employees.store');
    Route::get('/employees/{id}/edit', [EmployeeController::class, 'edit'])->name('employees.edit');
    Route::put('/employees/{id}', [EmployeeController::class, 'update'])->name('employees.update');

    // Cab Types Management (Admin Only)
    Route::get('/cab-types', [CabTypeController::class, 'index'])->name('cab-types.index');
    Route::post('/cab-types', [CabTypeController::class, 'store'])->name('cab-types.store');
    Route::post('/cab-types/{id}/toggle', [CabTypeController::class, 'toggle'])->name('cab-types.toggle');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
