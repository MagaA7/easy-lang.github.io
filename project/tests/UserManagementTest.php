<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../classes/admin_class.php';

class UserManagementTest extends TestCase
{
    private $admin;

    protected function setUp(): void
    {
        $this->admin = new Admin_Class();
        // Подготовка окружения для теста, например, создание тестовых данных.
    }

    public function testUserDeletion()
    {
        // Предположим, что у нас есть user_id для удаления.
        $testUserId = 123;

        // SQL запрос и страница для перенаправления после удаления.
        $sql = "DELETE FROM tbl_admin WHERE user_id = :id";
        $redirectPage = "admin-manage-user.php";

        // Вызов функции удаления
        $_GET['delete_user'] = true;
        $_GET['admin_id'] = $testUserId;
        $this->admin->delete_data_by_this_method($sql, $testUserId, $redirectPage);

        // Проверка, что пользователь был удален
        $result = $this->admin->check_if_user_exists($testUserId);
        $this->assertFalse($result, "Пользователь не был удален.");
    }

    protected function tearDown(): void
    {
        // Очистка тестовых данных
    }
}
