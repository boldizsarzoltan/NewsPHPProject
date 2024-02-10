<?php

namespace App\Utils;

use App\Utils\Exceptions\EnvironmentVariableDoesNotExist;

class EnvLoader
{
    /**
     * @param  string $envFilePath
     * @return void
     * @throws EnvironmentVariableDoesNotExist
     */
    public static function load(string $envFilePath): void
    {
        // Check if the file exists
        if (!file_exists($envFilePath)) {
            throw new EnvironmentVariableDoesNotExist($envFilePath);
        }

        // Read the contents of the .env file
        $contents = file_get_contents($envFilePath);

        // Parse the contents to extract key-value pairs
        $lines = explode("\n", $contents);
        foreach ($lines as $line) {
            if (!empty($line) && str_contains($line, '=') && !str_starts_with($line, '#')) {
                list($key, $value) = explode('=', $line, 2);
                $key = trim($key);
                $value = trim($value);
                putenv("$key=$value");
            }
        }
    }
}
