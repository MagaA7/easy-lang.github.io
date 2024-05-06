<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../classes/admin_class.php';

class AdminLogoutTest extends TestCase
{
    private $admin;

    protected function setUp(): void
    {
        $this->admin = new Admin_Class();
    }

    public function testAdminLogout()
    {
        $_SESSION['admin_id'] = 123;  // Установим сессию
        $this->admin->admin_logout();  // Вызываем метод выхода

        $this->assertFalse(isset($_SESSION['admin_id']));  // Проверяем, что сессия очищена
    }
}
