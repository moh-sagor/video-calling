<?php

return [
    'github_token' => env('GITHUB_TOKEN', ''),
    'github_username' => env('GITHUB_USERNAME', 'moh-sagor'),
    'github_repo_link' => env('GITHUB_REPO_LINK', 'github.com/moh-sagor/video-calling.git'),
    'artisan_commands' => env('ARTISAN_COMMANDS', 'php artisan migrate --force, php artisan db:seed'),
];


