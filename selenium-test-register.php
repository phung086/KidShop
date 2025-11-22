<?php
require __DIR__ . '/vendor/autoload.php';

use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;

$host = 'http://10.6.136.183:4444/';
$driver = RemoteWebDriver::create($host, [
    'browserName' => 'chrome',
    'goog:chromeOptions' => [
        'args' => [] // hoặc ['--headless', '--disable-gpu'] nếu muốn chạy ẩn
    ]
]);

// Truy cập trang đăng ký
$driver->get('http://localhost:8000/register');

// Chờ các trường input sẵn sàng
$driver->wait(10, 500)->until(
    WebDriverExpectedCondition::elementToBeClickable(WebDriverBy::name('username'))
);
$driver->wait(10, 500)->until(
    WebDriverExpectedCondition::elementToBeClickable(WebDriverBy::name('password'))
);
$driver->wait(10, 500)->until(
    WebDriverExpectedCondition::elementToBeClickable(WebDriverBy::name('repassword'))
);

// Điền thông tin
$driver->findElement(WebDriverBy::name('username'))->clear();
$driver->findElement(WebDriverBy::name('username'))->sendKeys('Test User');
$driver->findElement(WebDriverBy::name('password'))->clear();
$driver->findElement(WebDriverBy::name('password'))->sendKeys('password123');
$driver->findElement(WebDriverBy::name('repassword'))->clear();
$driver->findElement(WebDriverBy::name('repassword'))->sendKeys('password123');

// Gửi form
$driver->findElement(WebDriverBy::cssSelector('input[type=submit]'))->click();

// Kiểm tra kết quả
sleep(2); // chờ load trang
echo "Title after register: " . $driver->getTitle() . PHP_EOL;

$driver->quit();
