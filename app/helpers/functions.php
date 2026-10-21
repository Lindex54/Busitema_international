<?php
// Shared helper functions.

if (!function_exists('slugify')) {
    function slugify(string $value): string
    {
        return trim(preg_replace('/[^a-z0-9]+/', '-', strtolower($value)), '-');
    }
}

if (!function_exists('store_uploaded_image')) {
    // Validates and saves an uploaded image ($_FILES[...] entry) into $destDir,
    // returning ['ok' => true, 'filename' => string|null] on success (filename
    // is null when no file was submitted) or ['ok' => false, 'error' => string].
    function store_uploaded_image(array $file, string $destDir, array $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'], int $maxBytes = 2097152): array
    {
        if (!isset($file['error']) || $file['error'] === UPLOAD_ERR_NO_FILE) {
            return ['ok' => true, 'filename' => null];
        }

        if ($file['error'] !== UPLOAD_ERR_OK) {
            return ['ok' => false, 'error' => 'The upload failed. Please try again.'];
        }

        if ($file['size'] > $maxBytes) {
            return ['ok' => false, 'error' => 'The image is too large (max 2MB).'];
        }

        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if (!in_array($extension, $allowedExtensions, true)) {
            return ['ok' => false, 'error' => 'Please upload a JPG, PNG or WEBP image.'];
        }

        $mimeType = mime_content_type($file['tmp_name']);
        $allowedMimes = ['image/jpeg', 'image/png', 'image/webp'];

        if (!in_array($mimeType, $allowedMimes, true)) {
            return ['ok' => false, 'error' => 'The uploaded file is not a valid image.'];
        }

        $expectedExtension = [
            'image/jpeg' => ['jpg', 'jpeg'],
            'image/png' => ['png'],
            'image/webp' => ['webp'],
        ][$mimeType];

        if (!in_array($extension, $expectedExtension, true) || @getimagesize($file['tmp_name']) === false) {
            return ['ok' => false, 'error' => 'The image contents do not match the file extension.'];
        }

        if (!is_dir($destDir) && !mkdir($destDir, 0755, true) && !is_dir($destDir)) {
            return ['ok' => false, 'error' => 'Could not prepare the upload folder.'];
        }

        $filename = bin2hex(random_bytes(8)) . '.' . $extension;
        $destination = rtrim($destDir, '/') . '/' . $filename;

        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            return ['ok' => false, 'error' => 'Could not save the uploaded image.'];
        }

        return ['ok' => true, 'filename' => $filename];
    }
}

if (!function_exists('remove_managed_file')) {
    // Removes only a regular file contained by the explicitly supplied base
    // directory. Stored database paths are never trusted as filesystem paths.
    function remove_managed_file(string $baseDir, ?string $relativePath): bool
    {
        $relativePath = str_replace('\\', '/', trim((string) $relativePath));

        if ($relativePath === '' || str_contains($relativePath, "\0") || preg_match('#(^|/)\.\.(/|$)#', $relativePath)) {
            return false;
        }

        $baseReal = realpath($baseDir);
        $fileReal = realpath(rtrim($baseDir, '/\\') . DIRECTORY_SEPARATOR . ltrim($relativePath, '/'));

        if ($baseReal === false || $fileReal === false || !is_file($fileReal)) {
            return false;
        }

        $basePrefix = rtrim(str_replace('\\', '/', $baseReal), '/') . '/';
        $normalizedFile = str_replace('\\', '/', $fileReal);

        return str_starts_with($normalizedFile, $basePrefix) && @unlink($fileReal);
    }
}

if (!function_exists('database_write_error')) {
    function database_write_error(Throwable $exception): string
    {
        error_log(sprintf(
            'Admin database write failed [%s]: %s',
            $exception->getCode(),
            $exception->getMessage()
        ));

        if ($exception instanceof PDOException && (string) $exception->getCode() === '23000') {
            return 'That value is already in use. Please change the unique name or email and try again.';
        }

        return 'The changes could not be saved. Please check the database connection and try again.';
    }
}
