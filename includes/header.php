<?php
if (!isset($pageTitle)) {
    $pageTitle = "Busitema Internationalization";
}

$scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
$publicMarkerPosition = strpos($scriptName, '/public/');
$siteBase = $publicMarkerPosition === false
    ? ''
    : substr($scriptName, 0, $publicMarkerPosition + strlen('/public'));
$assetBase = $siteBase . '/assets';
$assetVersion = static function (string $relativePath): string {
    $assetPath = __DIR__ . '/../public/assets/' . ltrim($relativePath, '/');

    return is_file($assetPath) ? '?v=' . filemtime($assetPath) : '';
};
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?></title>
    <link rel="stylesheet" href="<?= htmlspecialchars($assetBase) ?>/css/tailwind.output.css">
    <link rel="stylesheet" href="<?= htmlspecialchars($assetBase) ?>/css/index.css<?= $assetVersion('css/index.css') ?>">
    <link rel="stylesheet" href="<?= htmlspecialchars($assetBase) ?>/css/main.css<?= $assetVersion('css/main.css') ?>">
    <link rel="stylesheet" href="<?= htmlspecialchars($assetBase) ?>/css/components.css<?= $assetVersion('css/components.css') ?>">
    <link rel="stylesheet" href="<?= htmlspecialchars($assetBase) ?>/css/responsive.css<?= $assetVersion('css/responsive.css') ?>">
    <link rel="stylesheet" href="<?= htmlspecialchars($assetBase) ?>/css/app.css<?= $assetVersion('css/app.css') ?>">
</head>
<body class="app">
