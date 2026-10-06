<?php

// ============================================================
// Dependencies
// ============================================================

use App\Core\Router;
use App\Controllers\NewsController;

// ============================================================
// Public — News
// ============================================================

Router::get(
    '/',
    [NewsController::class, 'index']
);

Router::get(
    '/news',
    [NewsController::class, 'index']
);

Router::get(
    '/news/{slug}',
    [NewsController::class, 'show']
);

// ============================================================
// Admin — News
// ============================================================

Router::get(
    '/admin/news',
    [NewsController::class, 'adminIndex'],
    ['admin_only']
);

Router::get(
    '/admin/news/create',
    [NewsController::class, 'create'],
    ['admin_only']
);

Router::get(
    '/admin/news/{id}/edit',
    [NewsController::class, 'edit'],
    ['admin_only']
);

Router::post(
    '/api/news',
    [NewsController::class, 'store'],
    ['admin_only']
);

Router::post(
    '/api/news/{id}/update',
    [NewsController::class, 'update'],
    ['admin_only']
);

Router::post(
    '/api/admin/news/delete',
    [NewsController::class, 'destroy'],
    ['admin_only']
);

// ============================================================
// User — Favorites
// ============================================================

Router::get(
    '/favorites',
    [NewsController::class, 'favorites'],
    ['auth_only']
);

Router::post(
    '/api/news/{id}/favorite',
    [NewsController::class, 'favorite'],
    ['auth_only']
);
