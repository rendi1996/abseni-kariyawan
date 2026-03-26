<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    if (auth()->check()) {
        if (auth()->user()->role === 'admin') {
            return redirect()->route('admin.attendance.index');
        } else {
            return redirect()->route('attendance.index');
        }
    }
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    if (auth()->user()->role === 'admin') {
        return redirect()->route('admin.attendance.index');
    }

    return redirect()->route('attendance.index');
})->middleware(['auth'])->name('dashboard');

// User routes
Route::middleware(['auth'])->group(function () {
    Route::get('/attendance', [App\Http\Controllers\AttendanceController::class, 'index'])->name('attendance.index');
    Route::post('/attendance', [App\Http\Controllers\AttendanceController::class, 'store'])->name('attendance.store');
});

// Admin routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/attendance', [App\Http\Controllers\AdminAttendanceController::class, 'index'])->name('attendance.index');
    Route::get('/attendance/export', [App\Http\Controllers\AdminAttendanceController::class, 'exportCsv'])->name('attendance.export');
    Route::get('/attendance/print', [App\Http\Controllers\AdminAttendanceController::class, 'print'])->name('attendance.print');
    Route::get('/employees', [App\Http\Controllers\AdminEmployeeController::class, 'index'])->name('employees.index');
    Route::post('/employees', [App\Http\Controllers\AdminEmployeeController::class, 'store'])->name('employees.store');
    Route::put('/employees/{employee}', [App\Http\Controllers\AdminEmployeeController::class, 'update'])->name('employees.update');
    Route::delete('/employees/{employee}', [App\Http\Controllers\AdminEmployeeController::class, 'destroy'])->name('employees.destroy');
    Route::get('/employees/export', [App\Http\Controllers\AdminEmployeeController::class, 'export'])->name('employees.export');
});

require __DIR__.'/auth.php';
