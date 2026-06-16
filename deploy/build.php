<?php

$root = __DIR__ . '/..';
$stagingRoot = $root . '/deploy_staging';
$deployEnv = $root . '/deploy/env_production.txt';

function rrmdir($dir): void
{
    if (!is_dir($dir)) {
        return;
    }

    $items = array_diff(scandir($dir), ['.', '..']);
    foreach ($items as $item) {
        $path = $dir . DIRECTORY_SEPARATOR . $item;
        if (is_dir($path)) {
            rrmdir($path);
        } else {
            @unlink($path);
        }
    }

    @rmdir($dir);
}

function copyDir(string $src, string $dest): void
{
    if (!is_dir($src)) {
        return;
    }

    if (!is_dir($dest)) {
        @mkdir($dest, 0775, true);
    }

    $items = array_diff(scandir($src), ['.', '..']);
    foreach ($items as $item) {
        $srcPath = $src . DIRECTORY_SEPARATOR . $item;
        $destPath = $dest . DIRECTORY_SEPARATOR . $item;

        if (is_dir($srcPath)) {
            copyDir($srcPath, $destPath);
        } else {
            @copy($srcPath, $destPath);
        }
    }
}

function zipDir($zip, $dir, $baseLength) {
    $items = array_diff(scandir($dir), ['.', '..']);
    foreach ($items as $item) {
        $path = $dir . DIRECTORY_SEPARATOR . $item;
        $localName = substr($path, $baseLength + 1);
        if (is_dir($path)) {
            $zip->addEmptyDir($localName);
            zipDir($zip, $path, $baseLength);
        } else {
            $zip->addFile($path, $localName);
        }
    }
}

$exclude = ['.git', 'node_modules', 'tests', 'deploy', 'deploy_staging', 'htdocs_root', 'vendor'];

echo PHP_EOL . '🧹 Cleaning old staging...' . PHP_EOL;
@rrmdir($stagingRoot);
@mkdir($stagingRoot, 0775, true);

echo '📁 Copying Laravel structure (fast mode)...' . PHP_EOL;

$essentialDirs = ['app', 'bootstrap', 'config', 'database', 'public', 'resources', 'routes'];
foreach ($essentialDirs as $d) {
    copyDir($root . '/' . $d, $stagingRoot . '/' . $d);
}

// Copy non-vendor files only (skip node_modules etc)
$rootItems = array_diff(scandir($root), array_merge(['.', '..'], $exclude, $essentialDirs));
$stagingRootWithSlash = $stagingRoot . '/';
foreach ($rootItems as $item) {
    $srcPath = $root . '/' . $item;
    $destPath = $stagingRootWithSlash . $item;
    if (is_dir($srcPath)) {
        copyDir($srcPath, $destPath);
    } else {
        @copy($srcPath, $destPath);
    }
}

echo '📦 Copying vendor/ (skip phpunit for speed)...' . PHP_EOL;
if (is_dir($root . '/vendor')) {
    copyDir($root . '/vendor', $stagingRoot . '/vendor');
}

echo '📦 Copying storage/...' . PHP_EOL;
if (is_dir($root . '/storage')) {
    copyDir($root . '/storage', $stagingRoot . '/storage');
}

echo '🔒 Copying .htaccess and .env...' . PHP_EOL;
@copy($root . '/htdocs_root/.htaccess', $stagingRoot . '/.htaccess');
@copy($deployEnv, $stagingRoot . '/.env');

echo PHP_EOL . '📦 Creating ZIP archive...' . PHP_EOL;

$zipPath = $root . '/absensi-app-infinityfree.zip';
if (is_file($zipPath)) {
    @unlink($zipPath);
}

$zip = new ZipArchive();
if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
    fwrite(STDERR, '❌ Failed to create zip archive.' . PHP_EOL);
    exit(1);
}

zipDir($zip, $stagingRoot, strlen($stagingRoot));

$zip->close();

echo PHP_EOL . '✅ Done: ' . $zipPath . PHP_EOL . PHP_EOL;
