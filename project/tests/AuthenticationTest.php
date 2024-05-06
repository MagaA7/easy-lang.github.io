<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../classes/admin_class.php';

class AuthenticationTest extends TestCase
{
    private $admin;

    protected function setUp(): void
    {
        $this->admin = new Admin_Class();
        // Мокаем объект db, чтобы предотвратить реальное взаимодействие с базой данных
        $this->admin->db = $this->createMock(PDO::class);
    }

    public function testAdminLoginCheckWithCorrectCredentials()
    {
        // Подготавливаем данные и ожидаемые результаты
        $data = ['username' => 'testuser', 'admin_password' => 'password'];
        $hashedPassword = md5($data['admin_password']);
        
        // Подготовка мока запроса
        $stmtMock = $this->createMock(PDOStatement::class);
        $stmtMock->method('fetch')->willReturn(['user_id' => 1, 'fullname' => 'Test User', 'user_role' => 1, 'temp_password' => null]);
        $stmtMock->method('rowCount')->willReturn(1);

        $this->admin->db->method('prepare')->willReturn($stmtMock);
        $stmtMock->expects($this->once())->method('execute')->with([':uname' => 'testuser', ':upass' => $hashedPassword]);

        // Выполняем функцию
        $this->admin->admin_login_check($data);

        // Проверяем, что сессия установлена
        $this->assertEquals($_SESSION['admin_id'], 1);
    }

    public function testAdminLoginCheckWithIncorrectCredentials()
    {
        $data = ['username' => 'wronguser', 'admin_password' => 'wrongpassword'];
        $hashedPassword = md5($data['admin_password']);

        $stmtMock = $this->createMock(PDOStatement::class);
        $stmtMock->method('rowCount')->willReturn(0);

        $this->admin->db->method('prepare')->willReturn($stmtMock);
        $stmtMock->expects($this->once())->method('execute')->with([':uname' => 'wronguser', ':upass' => $hashedPassword]);

        $result = $this->admin->admin_login_check($data);

        $this->assertEquals('Invalid user name or Password', $result);
    }
}
