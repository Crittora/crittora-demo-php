<?php

function loadEnvironmentVariables($directory) {
    $dotenv = Dotenv\Dotenv::createImmutable($directory);
    $dotenv->load();

    foreach ($_ENV as $key => $value) {
        putenv("$key=$value");
    }
}