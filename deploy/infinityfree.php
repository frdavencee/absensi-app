<?php

$root = dirname(__DIR__);
$stagingRoot = $root . DIRECTORY_SEPARATOR . 'deploy_staging';
$htdocsRoot = $root . DIRECTORY_SEPARATOR . 'htdocs_root';
$publicDir = $root . DIRECTORY_SEPARATOR . 'public';
$storageDir = $root . DIRECTORY_SEPARATOR . 'storage';
$bootstrapCache = $root . DIRECTORY_SEPARATOR . 'bootstrap' . DIRECTORY_SEPARATOR . 'cache';
$envSource = $root . DIRECTORY_SEPARATOR . 'deploy' . DIRECTORY_SEPARATOR . 'env_production.txt';
$envExample = $root . DIRECTORY_SEPARATOR . 'deploy' . DIRECTORY_SEPARATOR . 'infinityfree.env.example';
$zipPath = $root . DIRECTORY_SEPARATOR . 'absensi-app-infinityfree.zip';

function output(string $message): void
{
    echo $message . PHP_EOL;
}

function fail(string $message): never
{
    fwrite(STDERR, 'ERROR: ' . $message . PHP_EOL);
    exit(1);
}

function removePath(string $path): void
{
    if (!file_exists($path)) {
        return;
    }

    if (is_dir($path)) {
        $items = array_diff(scandir($path), ['.', '..']);
        foreach ($items as $item) {
            removePath($path . DIRECTORY_SEPARATOR . $item);
        }
        @rmdir($path);

        return;
    }

    @unlink($path);
}

function makeDirectory(string $path): void
{
    if (!is_dir($path)) {
        @mkdir($path, 0775, true);
    }
}

function copyDirectory(string $source, string $destination, array $exclude = []): void
{
    if (!is_dir($source)) {
        return;
    }

    makeDirectory($destination);

    $items = array_diff(scandir($source), ['.', '..']);
    foreach ($items as $item) {
        if (in_array($item, $exclude, true)) {
            continue;
        }

        $sourcePath = $source . DIRECTORY_SEPARATOR . $item;
        $destinationPath = $destination . DIRECTORY_SEPARATOR . $item;

        if (is_dir($sourcePath)) {
            copyDirectory($sourcePath, $destinationPath, $exclude);
        } else {
            @copy($sourcePath, $destinationPath);
        }
    }
}

function copyFileIfExists(string $source, string $destination): void
{
    if (is_file($source)) {
        makeDirectory(dirname($destination));
        @copy($source, $destination);
    }
}

function syncPublicStorage(): void
{
    global $root, $storageDir, $publicDir;

    $source = $storageDir . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'public';
    $destination = $publicDir . DIRECTORY_SEPARATOR . 'storage';

    makeDirectory($destination);

    $sourceRealPath = realpath($source);
    $destinationRealPath = realpath($destination);
    if ($sourceRealPath && $destinationRealPath && $sourceRealPath === $destinationRealPath) {
        makeDirectory($destination . DIRECTORY_SEPARATOR . 'foto');

        return;
    }

    copyDirectory($source, $destination);
    makeDirectory($destination . DIRECTORY_SEPARATOR . 'foto');
}

function addDirectoryToZip(ZipArchive $zip, string $directory, string $baseDirectory): void
{
    $items = array_diff(scandir($directory), ['.', '..']);

    foreach ($items as $item) {
        $path = $directory . DIRECTORY_SEPARATOR . $item;
        $localName = str_replace('\\', '/', substr($path, strlen($baseDirectory) + 1));

        if (is_dir($path)) {
            $zip->addEmptyDir($localName);
            addDirectoryToZip($zip, $path, $baseDirectory);
            continue;
        }

        $zip->addFile($path, $localName);
    }
}

function hasPlaceholderEnv(string $content): bool
{
    $placeholders = [
        'GENERATE_KALAU_BISA_LOKAL_DAHULU',
        'namadomainmu',
        'sqlXXX.infinityfree.com',
        'namadb_kamu',
        'username_db_kamu',
        'password_db_kamu',
    ];

    foreach ($placeholders as $placeholder) {
        if (str_contains($content, $placeholder)) {
            return true;
        }
    }

    return false;
}

if (PHP_VERSION_ID < 80300) {
    fail('Project ini membutuhkan PHP 8.3 atau lebih baru. PHP saat ini: ' . PHP_VERSION);
}

if (!extension_loaded('zip')) {
    fail('Ekstensi PHP zip tidak aktif. Aktifkan zip extension sebelum menjalankan build.');
}

if (!is_dir($root . DIRECTORY_SEPARATOR . 'vendor')) {
    fail('Folder vendor tidak ditemukan. Jalankan composer install terlebih dahulu.');
}

if (!is_file($publicDir . DIRECTORY_SEPARATOR . 'build' . DIRECTORY_SEPARATOR . 'manifest.json')) {
    fail('public/build/manifest.json tidak ditemukan. Jalankan npm run build terlebih dahulu.');
}

if (!is_file($envSource)) {
    fail('deploy/env_production.txt tidak ditemukan. Salin deploy/infinityfree.env.example lalu isi konfigurasi InfinityFree.');
}

output('Cleaning old deployment package...');
removePath($stagingRoot);
removePath($zipPath);
makeDirectory($stagingRoot);

output('Copying InfinityFree htdocs entrypoint...');
copyDirectory($htdocsRoot, $stagingRoot);

output('Copying Laravel runtime structure...');
foreach (['app', 'bootstrap', 'config', 'database', 'resources', 'routes'] as $directory) {
    copyDirectory($root . DIRECTORY_SEPARATOR . $directory, $stagingRoot . DIRECTORY_SEPARATOR . $directory);
}

makeDirectory($bootstrapCache);
@file_put_contents($bootstrapCache . DIRECTORY_SEPARATOR . '.gitignore', "*\n!.gitignore\n");

output('Copying public assets...');
copyDirectory($publicDir, $stagingRoot . DIRECTORY_SEPARATOR . 'public', ['hot']);
syncPublicStorage();

output('Copying runtime dependencies...');
copyDirectory($root . DIRECTORY_SEPARATOR . 'vendor', $stagingRoot . DIRECTORY_SEPARATOR . 'vendor', ['bin']);
copyFileIfExists($root . DIRECTORY_SEPARATOR . 'artisan', $stagingRoot . DIRECTORY_SEPARATOR . 'artisan');
copyFileIfExists($root . DIRECTORY_SEPARATOR . 'composer.json', $stagingRoot . DIRECTORY_SEPARATOR . 'composer.json');
copyFileIfExists($root . DIRECTORY_SEPARATOR . 'composer.lock', $stagingRoot . DIRECTORY_SEPARATOR . 'composer.lock');

output('Preparing writable folders...');
foreach ([
    $storageDir . DIRECTORY_SEPARATOR . 'framework' . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . 'data',
    $storageDir . DIRECTORY_SEPARATOR . 'framework' . DIRECTORY_SEPARATOR . 'sessions',
    $storageDir . DIRECTORY_SEPARATOR . 'framework' . DIRECTORY_SEPARATOR . 'testing',
    $storageDir . DIRECTORY_SEPARATOR . 'framework' . DIRECTORY_SEPARATOR . 'views',
    $storageDir . DIRECTORY_SEPARATOR . 'logs',
    $storageDir . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'foto',
    $stagingRoot . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'framework' . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . 'data',
    $stagingRoot . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'framework' . DIRECTORY_SEPARATOR . 'sessions',
    $stagingRoot . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'framework' . DIRECTORY_SEPARATOR . 'testing',
    $stagingRoot . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'framework' . DIRECTORY_SEPARATOR . 'views',
    $stagingRoot . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'logs',
    $stagingRoot . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'foto',
] as $directory) {
    makeDirectory($directory);
    $gitignorePath = $directory . DIRECTORY_SEPARATOR . '.gitignore';
    if (!is_file($gitignorePath)) {
        @file_put_contents($gitignorePath, "*\n!.gitignore\n");
    }
}

output('Copying environment files...');
copyFileIfExists($envSource, $stagingRoot . DIRECTORY_SEPARATOR . '.env');
copyFileIfExists($envExample, $stagingRoot . DIRECTORY_SEPARATOR . '.env.example');

$envContent = file_get_contents($envSource);
if (hasPlaceholderEnv($envContent)) {
    output('WARNING: deploy/env_production.txt masih berisi placeholder. Edit file tersebut sebelum upload ke InfinityFree.');
}

output('Creating ZIP archive...');
$zip = new ZipArchive();
if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
    fail('Gagal membuat ZIP archive.');
}

addDirectoryToZip($zip, $stagingRoot, $stagingRoot);
$zip->close();

output('Deployment package ready: ' . $zipPath);
output('Upload isi ZIP ini ke folder public_html/htdocs InfinityFree, lalu jalankan php artisan migrate --force di hosting jika database masih kosong.');
