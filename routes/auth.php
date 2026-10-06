<?php

// ============================================================
// Dependencies
// ============================================================

use App\Core\Router;
use App\Controllers\AuthController;

// ============================================================
// Auth — Web
// ============================================================

Router::get(
    '/login',
    [AuthController::class, 'showLogin'],
    ['guest_only'],
    'login'
);

Router::get(
    '/register',
    [AuthController::class, 'showRegister'],
    ['guest_only'],
    'register'
);

// ============================================================
// Auth — API
// ============================================================

Router::post(
    '/api/auth/register',
    [AuthController::class, 'register'],
    ['guest_only']
);

Router::post(
    '/api/auth/login',
    [AuthController::class, 'login'],
    ['guest_only']
);

Router::post(
    '/api/auth/logout',
    [AuthController::class, 'logout'],
    ['auth_only']
);

// ============================================================
// Admin — Initial Admin Creation
// ============================================================

// Can only be executed once.
Router::get(
    '/myadmin',
    [AuthController::class, 'myadmin']
);
