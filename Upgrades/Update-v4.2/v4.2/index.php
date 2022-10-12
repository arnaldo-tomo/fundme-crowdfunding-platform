<?php

// Install Controller
$version = '4.2';
$upgradeController = __DIR__.'/UpgradeController.php';
$newUpgradeController = __DIR__.'/../app/Http/Controllers/UpgradeController.php';

// Move Install Controller
if(is_readable($upgradeController)) {
  rename($upgradeController, $newUpgradeController);
}

// Redirect to URL for Install
function url() {
  return sprintf(
    "%s://%s%s",
    isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
    $_SERVER['SERVER_NAME'],
    $_SERVER['REQUEST_URI']
  );
}

$url = preg_replace("/v$version\//", '', url());

header('Location: '.$url.'update/'.$version);
