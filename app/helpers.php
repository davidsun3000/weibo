<?php
function get_db_config() {
    if (getenv('IS_IN_HEROKU')) {
        $url = parse_url(getenv("DATABASE_URL"));
        return $db_config = [
            'connection' => 'pgsql',
            'host' => $url["host"],
            'database' => substr($url["patch"], 1),
            'username' => $url["user"],
            'password' => $url["pass"],
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
        ];
    } else {
        return $db_config = [
            'connection' => env('DB_CONNECTION', 'mysql'),
            'host' => env('DB_HOST', 'localhost'),
            'database' => env('DB_DATABASE', 'forge'),
            'username' => env('DB_USERNAME', 'forge'),
            'password' => env('DB_PASSWORD', ''),
            'charset' => 'utf8',
        ];
    }
}