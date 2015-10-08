<?php

return [
    'adminEmail' => 'admin@example.com',
    'image_path' => 'web/images',
    'images'     => [
        'article' => [
            'prefix'        => 'article_',
            'allowed_types' => [IMAGETYPE_BMP, IMAGETYPE_JPEG, IMAGETYPE_PNG],
            'sizes'         => []
        ]
    ]
];
