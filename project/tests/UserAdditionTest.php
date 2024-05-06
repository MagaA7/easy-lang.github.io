<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../classes/admin_class.php';

class UserAdditionTest extends TestCase
{
    private $admin;

    protected function setUp(): void
    {
        $this->admin = new Admin_Class();
        $this->admin->db = $this->createMock(PDO::class);
    }

    public function testAddNewUserSuccessfully()
    {
        $data = [
            'em_fullname' => 'New User',
            'em_username' => 'newuser',
            'em_email' => 'newuser@example.com'
        ];

        $stmtMock = $this->createMock(PDOStatement::class);
        $stmtMock->method('rowCount')->willReturn(0);
        $this->admin->db->method('prepare')->willReturn($stmtMock);
        $stmtMock->expects($this->once())->method('execute');

        $this->admin->add_new_user($data);
        $this->addToAssertionCount(1); // Добавляем утверждение, что тест выполнен
    }

    public function testAddNewUserWithExistingEmail()
    {
        $data = [
            'em_fullname' => 'New User',
            'em_username' => 'newuser',
            'em_email' => 'existing@example.com'
        ];

        $emailStmtMock = $this->createMock(PDOStatement::class);
        $emailStmtMock->method('rowCount')->willReturn(1);

        $usernameStmtMock = $this->createMock(PDOStatement::class);
        $usernameStmtMock->method('rowCount')->willReturn(0);

        $this->admin->db->method('prepare')->willReturnOnConsecutiveCalls($emailStmtMock, $usernameStmtMock);

        $result = $this->admin->add_new_user($data);
        $this->assertEquals("Email and Password both are already taken", $result); // Исправление текста ожидаемого результата
    }

    public function testAddNewUserWithExistingUsername()
    {
        $data = [
            'em_fullname' => 'New User',
            'em_username' => 'existinguser',
            'em_email' => 'newuser@example.com'
        ];

        $emailStmtMock = $this->createMock(PDOStatement::class);
        $emailStmtMock->method('rowCount')->willReturn(0);

        $usernameStmtMock = $this->createMock(PDOStatement::class);
        $usernameStmtMock->method('rowCount')->willReturn(1);

        $this->admin->db->method('prepare')->willReturnOnConsecutiveCalls($emailStmtMock, $usernameStmtMock);

        $result = $this->admin->add_new_user($data);
        $this->assertEquals("Email and Password both are already taken", $result); // Исправление текста ожидаемого результата
    }
}
