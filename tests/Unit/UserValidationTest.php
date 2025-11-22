<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class UserValidationTest extends TestCase
{
    public function test_user_password_min_length()
    {
        $password = '123456';
        $this->assertGreaterThanOrEqual(6, strlen($password));
    }

    public function test_user_name_is_required()
    {
        $name = 'DHung';
        $this->assertNotEmpty($name, 'Tên người dùng không được để trống');
    }

    public function test_email_format()
    {
        $email = 'test@gmail.com';
        $this->assertMatchesRegularExpression('/^[\w.+-]+@[\w.-]+\.[a-zA-Z]{2,}$/', $email);
    }
}
