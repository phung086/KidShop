<?php
require __DIR__ . '/vendor/autoload.php';

use Facebook\WebDriver\Remote\RemoteWebDriver;

$host = 'http://192.168.100.5:4444/';

$capabilities = [
    'browserName' => 'chrome',
    'goog:chromeOptions' => [
        'args' => ['--headless', '--disable-gpu']
    ]
];

$driver = RemoteWebDriver::create($host, $capabilities);

$driver->get('https://www.google.com');
echo "Title is: " . $driver->getTitle() . PHP_EOL;

$driver->quit();
