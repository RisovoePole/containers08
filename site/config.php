<?php

$config = [
    "db" => [
        // База создается в Docker volume /var/www/db и используется приложением и тестами.
        "path" => "/var/www/db/db.sqlite"
    ]
];