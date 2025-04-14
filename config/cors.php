<?php

return [
    'paths' => ['api/*'], // Allow CORS for all API routes
    'allowed_methods' => ['*'], // Allow all HTTP methods
    'allowed_origins' => ['http://localhost:3000', 'http://localhost:5173', 'https://gtts.comfarnet.org'], // ✅ Added port 5173
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'], // Allow all headers
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => false,
];
