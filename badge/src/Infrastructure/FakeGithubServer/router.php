<?php declare(strict_types=1);

\error_reporting(E_ALL);

$pathInfo = (string) $_SERVER['REQUEST_URI'];
$requestUriParts = \explode('?', $pathInfo);
/** @psalm-suppress RedundantCast */
$pathInfo = (string) \reset($requestUriParts);

if (\preg_match('#^/' . 'repos/badges/poser' . '$#', $pathInfo) > 0) {
    \header('Content-Type: application/json');
    echo \file_get_contents(__DIR__ . '/repository-badges-poser.json');
    exit;
}

if (\preg_match('#^/' . 'repos/doctrine/collections' . '$#', $pathInfo) > 0) {
    \header('Content-Type: application/json');
    echo \file_get_contents(__DIR__ . '/repository-doctrine-collections.json');
    exit;
}

if (\preg_match('#^/' . 'repos/seldaek/monolog' . '$#', $pathInfo) > 0) {
    \header('Content-Type: application/json');
    echo \file_get_contents(__DIR__ . '/repository-seldaek-monolog.json');
    exit;
}

if (\preg_match('#^/' . 'repos/sebastianbergmann/phpunit' . '$#', $pathInfo) > 0) {
    \header('Content-Type: application/json');
    echo \file_get_contents(__DIR__ . '/repository-sebastianbergmann-phpunit.json');
    exit;
}

if (\preg_match('#^/' . 'repos/orocrm/platform' . '$#', $pathInfo) > 0) {
    \header('Content-Type: application/json');
    echo \file_get_contents(__DIR__ . '/repository-orocrm-platform.json');
    exit;
}

\header('HTTP/1.0 404 Not Found');
echo 'Page not found';
