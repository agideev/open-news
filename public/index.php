<?php

// =====================================================
// INITIAL CONFIGURATION
// =====================================================

declare(strict_types=1);

session_start();

define('BASE_PATH', dirname(__DIR__));
define('PUBLIC_PATH', __DIR__);

// =====================================================
// END INITIAL CONFIGURATION
// =====================================================


// =====================================================
// LOAD CORE HELPERS
// =====================================================

require_once BASE_PATH . '/app/Helpers/autoload.php';
require_once BASE_PATH . '/app/Helpers/filesystem.php';

// =====================================================
// END LOAD CORE HELPERS
// =====================================================


// =====================================================
// LOAD ENVIRONMENT VARIABLES
// =====================================================

require_once BASE_PATH . '/app/Helpers/env.php';

load_env(BASE_PATH . '/.env');

// =====================================================
// END LOAD ENVIRONMENT VARIABLES
// =====================================================


// =====================================================
// LOAD APPLICATION CONFIGURATION
// =====================================================

require_once BASE_PATH . '/config/ai.php';
require_once BASE_PATH . '/config/database.php';

// =====================================================
// END LOAD APPLICATION CONFIGURATION
// =====================================================


// =====================================================
// AUTOLOAD ALL HELPERS
// =====================================================

requireDirectory(BASE_PATH . '/app/Helpers', [
    '.env',
    'autoload.php',
    'filesystem.php'
]);

// =====================================================
// END AUTOLOAD ALL HELPERS
// =====================================================


// =====================================================
// AUTOLOAD ROUTES
// =====================================================

requireDirectory(BASE_PATH . '/routes');

// =====================================================
// END AUTOLOAD ROUTES
// =====================================================


// =====================================================
// IMPORT CORE CLASSES
// =====================================================

use App\Core\Router;
use App\Core\JsonResponse;

// =====================================================
// END IMPORT CORE CLASSES
// =====================================================


// =====================================================
// CONFIGURE 404 ERROR HANDLER
// =====================================================

Router::setNotFound(function (): void {

    // Return a JSON response for API requests
    if (is_api_request()) {
        JsonResponse::error('Página não encontrada.', 404);
    }

    // Set the HTTP response status code
    http_response_code(404);

    // Render the 404 error page
    view('errors.404', [
        'title' => 'Página não encontrada'
    ]);
});

// =====================================================
// END CONFIGURE 404 ERROR HANDLER
// =====================================================


// =====================================================
// RUN ROUTER
// =====================================================

Router::run();

// =====================================================
// END RUN ROUTER
// =====================================================
