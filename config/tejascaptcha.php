<?php
// Place a copy of this  file in app/config if you want to modify the default values
return [
    'config_section_key' => 'standard',
    'standard' => [
        'length' => 5,
        'width' => 230,
        'height' => 50,
        'quality' => 90,
        'sensitive' => false,
    ],
    'flat' => [
        'length' => 5,
        'width' => 230,
        'height' => 50,
        'quality' => 90,
        'lines' => 6,
        'bgImage' => false,
        'bgColor' => '#ecf2f4',
        'fontColors' => ['#2c3e50', '#c0392b', '#16a085', '#c0392b', '#8e44ad', '#303f9f', '#f57c00', '#795548'],
        'contrast' => -5,
    ],
    'mini' => [
        'length' => 3,
        'width' => 60,
        'height' => 32,
    ],
    'inverse' => [
        'length' => 5,
        'width' => 200,
        'height' => 50,
        'quality' => 90,
        'sensitive' => true,
        'angle' => 12,
        'sharpen' => 10,
        'blur' => 2,
        'invert' => true,
        'contrast' => -5,
    ],
    'audio' => [
        'storeAudioInSession' => false,
        'osAudioDirectory' => '/storage/audio',
        'audioFilePrefix' => 'final'
    ]
];

// The osAudioDirectory must exist and requires r/w permissions set for your
// web user. The web user for Apache2 is www-data
// e.g.
// For a Linux server running Apache2 and using the defult osAudioDirectory:
// From the root directory of your laravel project, issue the following command:
//   mkdir storage/audio; chown -R www-data storage/audio
// Now verify that the correct web user has rwx permisions with:
//   ls -las storage/audio/
//     drwxr-xr-x 2 www-data root 4096 Apr 14 08:41 .
