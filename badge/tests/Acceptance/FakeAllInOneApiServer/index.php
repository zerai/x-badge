<?php declare(strict_types=1);

\error_reporting(E_ALL);

$pathInfo = (string) $_SERVER['REQUEST_URI'];
$requestUriParts = \explode('?', $pathInfo);
/** @psalm-suppress RedundantCast */
$pathInfo = (string) \reset($requestUriParts);

if (\preg_match('#^/' . 'packages/badges/poser' . '\.json$#', $pathInfo) > 0) {
    \header('Content-Type: application/json');
    echo \file_get_contents(__DIR__ . '/package-badges-poser.json');
    exit;
}

if (\preg_match('#^/' . 'packages/doctrine/collections' . '\.json$#', $pathInfo) > 0) {
    \header('Content-Type: application/json');
    echo \file_get_contents(__DIR__ . '/package-doctrine-collections.json');
    exit;
}

if (\preg_match('#^/' . 'packages/monolog/monolog' . '\.json$#', $pathInfo) > 0) {
    \header('Content-Type: application/json');
    echo \file_get_contents(__DIR__ . '/package-monolog-monolog.json');
    exit;
}

if (\preg_match('#^/' . 'packages/phpunit/phpunit' . '\.json$#', $pathInfo) > 0) {
    \header('Content-Type: application/json');
    echo \file_get_contents(__DIR__ . '/package-phpunit-phpunit.json');
    exit;
}

if (\preg_match('#^/' . 'packages/oro/platform' . '\.json$#', $pathInfo) > 0) {
    \header('Content-Type: application/json');
    echo \file_get_contents(__DIR__ . '/package-oro-platform.json');
    exit;
}

/**
 *  Suggesters usecase responses
 */

if (\preg_match('#^/' . 'packages/irrelevantvendor/package-with-zero-suggesters' . '\.json$#', $pathInfo) > 0) {
    \header('Content-Type: application/json');
    echo \file_get_contents(__DIR__ . '/package-with-zero-suggesters.json');
    exit;
}

/**
 *  Dependents usecase responses
 */

if (\preg_match('#^/' . 'packages/irrelevantvendor/package-with-zero-dependents' . '\.json$#', $pathInfo) > 0) {
    \header('Content-Type: application/json');
    echo \file_get_contents(__DIR__ . '/package-with-zero-dependents.json');
    exit;
}

/**
 *  TotalDownloads usecase responses
 */

if (\preg_match('#^/' . 'packages/irrelevantvendor/package-with-zero-total-downloads' . '\.json$#', $pathInfo) > 0) {
    \header('Content-Type: application/json');
    echo \file_get_contents(__DIR__ . '/package-with-zero-total-downloads.json');
    exit;
}

/**
 *  MonthlyDownloads usecase responses
 */

if (\preg_match('#^/' . 'packages/irrelevantvendor/package-with-zero-monthly-downloads' . '\.json$#', $pathInfo) > 0) {
    \header('Content-Type: application/json');
    echo \file_get_contents(__DIR__ . '/package-with-zero-monthly-downloads.json');
    exit;
}


\header('HTTP/1.0 404 Not Found');
echo 'Page not found';
