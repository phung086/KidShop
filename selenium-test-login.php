<?php
require __DIR__ . '/vendor/autoload.php';

use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;

$host = 'http://10.6.136.183:4444/';
$driver = RemoteWebDriver::create($host, [
    'browserName' => 'chrome',
    'goog:chromeOptions' => [
        'args' => [] // hoặc ['--headless', '--disable-gpu']
    ]
]);

// ---------- ĐĂNG KÝ ----------
$driver->get('http://localhost:8000/register');
sleep(1);
$driver->wait(10, 2000)->until(
    WebDriverExpectedCondition::elementToBeClickable(WebDriverBy::name('username'))
);
sleep(1);
$driver->wait(10, 2000)->until(
    WebDriverExpectedCondition::elementToBeClickable(WebDriverBy::name('password'))
);
sleep(1);
$driver->wait(10, 2000)->until(
    WebDriverExpectedCondition::elementToBeClickable(WebDriverBy::name('repassword'))
);
sleep(1);

$username = 'utest' . rand(100, 999); // Đảm bảo username không trùng
$password = 'password123';

$driver->findElement(WebDriverBy::name('username'))->clear();
sleep(1);
$driver->findElement(WebDriverBy::name('username'))->sendKeys($username);
sleep(1);
$driver->findElement(WebDriverBy::name('password'))->clear();
sleep(1);
$driver->findElement(WebDriverBy::name('password'))->sendKeys($password);
sleep(1);
$driver->findElement(WebDriverBy::name('repassword'))->clear();
sleep(1);
$driver->findElement(WebDriverBy::name('repassword'))->sendKeys($password);
sleep(1);

$driver->findElement(WebDriverBy::cssSelector('input[type=submit]'))->click();
sleep(2);
echo "Title after register: " . $driver->getTitle() . PHP_EOL;

// ---------- ĐĂNG NHẬP ----------
$driver->get('http://localhost:8000/login');
sleep(1);
$driver->wait(10, 2000)->until(
    WebDriverExpectedCondition::elementToBeClickable(WebDriverBy::name('username'))
);
sleep(1);
$driver->wait(10, 2000)->until(
    WebDriverExpectedCondition::elementToBeClickable(WebDriverBy::name('password'))
);
sleep(1);

$driver->findElement(WebDriverBy::name('username'))->clear();
sleep(1);
$driver->findElement(WebDriverBy::name('username'))->sendKeys($username);
sleep(1);
$driver->findElement(WebDriverBy::name('password'))->clear();
sleep(1);
$driver->findElement(WebDriverBy::name('password'))->sendKeys($password);
sleep(1);

$driver->findElement(WebDriverBy::cssSelector('input[type=submit]'))->click();
sleep(2);
echo "Title after login: " . $driver->getTitle() . PHP_EOL;

$driver->quit();
