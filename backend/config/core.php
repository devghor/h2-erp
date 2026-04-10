<?php

return [
    'date' => [
        'format' => 'Y-m-d',
        'datetime_format' => 'Y-m-d H:i:s',
    ],

    'pagination' => [
        'default' => 20,
        'max' => 100,
    ],

    'upload' => [
        'max_size' => 2048,
        'allowed_types' => ['jpg', 'png', 'pdf'],
    ],
];
