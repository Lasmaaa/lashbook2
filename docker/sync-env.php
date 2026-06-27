<?php

declare(strict_types=1);

function isValidLaravelAppKey(?string $key): bool
{
    if ($key === null || $key === '') {
        return false;
    }

    if (str_starts_with($key, 'base64:')) {
        $decoded = base64_decode(substr($key, 7), true);

        return $decoded !== false && strlen($decoded) === 32;
    }

    return strlen($key) === 32;
}

function generateAppKey(): string
{
    return 'base64:' . base64_encode(random_bytes(32));
}

function quoteEnvValue(string $value): string
{
    return preg_match('/[\s#="\'\\\\]/', $value)
        ? '"' . str_replace(['\\', '"'], ['\\\\', '\\"'], $value) . '"'
        : $value;
}

function setEnvLine(array &$lines, string $key, string $value): void
{
    $line = $key . '=' . quoteEnvValue($value);

    foreach ($lines as $index => $existing) {
        if (str_starts_with($existing, $key . '=')) {
            $lines[$index] = $line;

            return;
        }
    }

    $lines[] = $line;
}

$root = dirname(__DIR__);
$envFile = $root . '/.env';

if (! is_file($envFile)) {
    copy($root . '/.env.example', $envFile);
}

$lines = file($envFile, FILE_IGNORE_NEW_LINES);
if ($lines === false) {
    $lines = [];
}

$appKey = getenv('APP_KEY');
if ($appKey === false) {
    $appKey = '';
}

if (! isValidLaravelAppKey($appKey)) {
    $appKey = generateAppKey();
    fwrite(STDOUT, '[sync-env] Generated new APP_KEY' . PHP_EOL);
} else {
    fwrite(STDOUT, '[sync-env] Using valid APP_KEY' . PHP_EOL);
}

putenv('APP_KEY=' . $appKey);
$_ENV['APP_KEY'] = $appKey;
$_SERVER['APP_KEY'] = $appKey;

$keys = [
    'APP_KEY' => $appKey,
    'APP_ENV',
    'APP_DEBUG',
    'APP_NAME',
    'APP_URL',
    'LOG_CHANNEL',
    'DB_CONNECTION',
    'DATABASE_URL',
    'DB_URL',
    'SESSION_DRIVER',
    'CACHE_STORE',
    'QUEUE_CONNECTION',
];

$updated = [];

foreach ($keys as $index => $key) {
    if (is_int($index)) {
        $value = getenv($key);
        $envKey = $key;
    } else {
        $envKey = $index;
        $value = $key;
    }

    if ($value === false || $value === '') {
        continue;
    }

    setEnvLine($lines, $envKey, $value);
    putenv($envKey . '=' . $value);
    $_ENV[$envKey] = $value;
    $_SERVER[$envKey] = $value;
    $updated[] = $envKey;
}

file_put_contents($envFile, implode(PHP_EOL, $lines) . PHP_EOL);

$exportFile = $root . '/bootstrap/cache/render-env.sh';
if (! is_dir(dirname($exportFile))) {
    mkdir(dirname($exportFile), 0755, true);
}

$exportLines = [
    '#!/bin/sh',
    'export APP_KEY=' . escapeshellarg($appKey),
];

foreach ($updated as $key) {
    if ($key === 'APP_KEY') {
        continue;
    }

    $value = getenv($key);
    if ($value !== false && $value !== '') {
        $exportLines[] = 'export ' . $key . '=' . escapeshellarg($value);
    }
}

file_put_contents($exportFile, implode(PHP_EOL, $exportLines) . PHP_EOL);
chmod($exportFile, 0644);

fwrite(STDOUT, '[sync-env] Updated: ' . implode(', ', $updated) . PHP_EOL);
