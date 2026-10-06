<?php

// ============================================================
// Dependencies
// ============================================================

use App\Core\Router;
use App\Controllers\AiController;

// ============================================================
// AI — API
// ============================================================

Router::post(
    '/api/ai/chat',
    [AiController::class, 'chat'],
    ['auth_only']
);

// ============================================================
// AI — Test Routes
// ============================================================

Router::get(
    '/ai/test',
    [AiController::class, 'test'],
    ['admin_only']
);

Router::get(
    '/ai/context',
    [AiController::class, 'context'],
    ['admin_only']
);

// ============================================================
// AI — Chat Interface
// ============================================================

Router::get(
    '/assistente',
    [AiController::class, 'chatView'],
    ['auth_only'],
    'chat'
);
