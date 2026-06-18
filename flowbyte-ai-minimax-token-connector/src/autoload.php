<?php

/**
 * PSR-4 autoloader for the 808 AI MiniMax Token Plan Provider package.
 *
 * @since 1.0.0
 *
 * @package FlowByte\EightMinimaxToken
 */

declare(strict_types=1);

spl_autoload_register(static function (string $class): void {
    $prefix = 'FlowByte\EightMinimaxToken\\';
    $baseDir = __DIR__ . '/';

    $len = strlen($prefix);

    if (strncmp($class, $prefix, $len) !== 0) {
        return;
    }

    $relativeClass = substr($class, $len);
    $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});