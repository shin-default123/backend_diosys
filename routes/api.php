<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SchoolController;
use App\Http\Controllers\Api\ParishController;
use App\Http\Controllers\Api\AnnouncementController;
use App\Http\Controllers\Api\ScheduleController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\ParishRecordController;
use App\Http\Controllers\Api\PublicSearchController;
use App\Http\Controllers\Api\EmployeeController;
use App\Http\Controllers\Api\SettingController;
use App\Http\Controllers\Api\EmailTemplateController;
use App\Http\Controllers\Api\MinistryController;
use App\Http\Controllers\Api\VenueController;
use App\Http\Controllers\Api\IncomeController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController; 
use App\Http\Controllers\Api\MessageController;
use App\Http\Controllers\Api\NotificationController;

/* PUBLIC ROUTES */

// Authentication
Route::post('/login', [AuthController::class, 'login']);

// Public Info (Homepage Data)
Route::get('/schools', [SchoolController::class, 'index']);
Route::get('/parishes', [ParishController::class, 'index']);
Route::get('/announcements', [AnnouncementController::class, 'index']);
Route::get('/schedules', [ScheduleController::class, 'index']);
Route::get('/settings', [SettingController::class, 'index']);

// Public Features
Route::post('/bookings', [BookingController::class, 'store']);
Route::post('/public/search', [PublicSearchController::class, 'search']);

/* PROTECTED ADMIN ROUTES */

Route::middleware('auth:sanctum')->group(function () {

    // Messaging
    Route::get('/messages/users', [MessageController::class, 'users']);
    Route::get('/messages/unread', [MessageController::class, 'unreadCount']);
    Route::get('/messages/{userId}', [MessageController::class, 'index']);
    Route::post('/messages', [MessageController::class, 'store']);

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::put('/notifications/read-all', [NotificationController::class, 'markAllAsRead']);
    Route::put('/notifications/{id}', [NotificationController::class, 'markAsRead']);

    // Auth Management
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);

    // User Management (Admins/Staff Accounts)
    Route::apiResource('users', UserController::class); // <--- ADDED THIS

    // Dashboard
    Route::get('/dashboard/stats', [DashboardController::class, 'stats']);

    // School Management
    Route::post('/schools', [SchoolController::class, 'store']);
    Route::delete('/schools/{id}', [SchoolController::class, 'destroy']);

    // Parish Management
    Route::post('/parishes', [ParishController::class, 'store']);
    Route::delete('/parishes/{id}', [ParishController::class, 'destroy']);

    // Announcement Management
    Route::post('/announcements', [AnnouncementController::class, 'store']);
    Route::delete('/announcements/{id}', [AnnouncementController::class, 'destroy']);

    // Schedule Management
    Route::post('/schedules', [ScheduleController::class, 'store']);
    Route::put('/schedules/{id}', [ScheduleController::class, 'update']);
    Route::delete('/schedules/{id}', [ScheduleController::class, 'destroy']);

    // Booking Management
    Route::get('/bookings', [BookingController::class, 'index']);
    Route::put('/bookings/{id}', [BookingController::class, 'update']);

    // Parish Registers
    Route::get('/registers/{type}', [ParishRecordController::class, 'index']);
    Route::post('/registers/{type}', [ParishRecordController::class, 'store']);
    Route::put('/registers/{type}/{id}', [ParishRecordController::class, 'update']);
    Route::delete('/registers/{type}/{id}', [ParishRecordController::class, 'destroy']);

    // Employee Management
    Route::get('/employees', [EmployeeController::class, 'index']);
    Route::post('/employees', [EmployeeController::class, 'store']);
    Route::put('/employees/{id}', [EmployeeController::class, 'update']);
    Route::delete('/employees/{id}', [EmployeeController::class, 'destroy']);

    // Settings & Email Templates
    Route::post('/settings', [SettingController::class, 'update']);
    Route::get('/email-templates', [EmailTemplateController::class, 'index']);
    Route::put('/email-templates/{id}', [EmailTemplateController::class, 'update']);

    // Ministries
    Route::get('/ministries', [MinistryController::class, 'index']);
    Route::post('/ministries', [MinistryController::class, 'store']);
    Route::put('/ministries/{id}', [MinistryController::class, 'update']);
    Route::delete('/ministries/{id}', [MinistryController::class, 'destroy']);

    // Venues
    Route::get('/venues', [VenueController::class, 'index']);
    Route::post('/venues', [VenueController::class, 'store']);
    Route::put('/venues/{id}', [VenueController::class, 'update']);
    Route::delete('/venues/{id}', [VenueController::class, 'destroy']);

    // Accounting
    Route::get('/incomes', [IncomeController::class, 'index']);
    Route::post('/incomes', [IncomeController::class, 'store']);
    Route::delete('/incomes/{id}', [IncomeController::class, 'destroy']);
    Route::get('/incomes/total', [IncomeController::class, 'total']);
});