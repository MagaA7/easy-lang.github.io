<?php

class PasswordChangeTest extends TestCase
{
    protected function setUp(): void
    {
        $this->admin = new Admin_Class();
        $this->admin->db = $this->createMock(PDO::class);
    }

    public function testPasswordChangeSuccess()
    {
        $data = ['password' => 'newpass', 're_password' => 'newpass', 'user_id' => 1];
        $hashedPassword = md5($data['password']);

        $updateMock = $this->createMock(PDOStatement::class);
        $this->admin->db->method('prepare')->willReturn($updateMock);
        $updateMock->expects($this->once())->method('execute')->with([':x' => $hashedPassword, ':y' => '', ':id' => 1]);

        $stmtMock = $this->createMock(PDOStatement::class);
        $stmtMock->method('rowCount')->willReturn(1);
        $this->admin->db->method('prepare')->willReturn($stmtMock);

        $this->admin->change_password_for_employee($data);
        // Проверка, что редирект был вызван
    }

    public function testPasswordChangeFailure()
    {
        $data = ['password' => 'newpass', 're_password' => 'wrongpass', 'user_id' => 1];

        $result = $this->admin->change_password_for_employee($data);

        $this->assertEquals('Sorry !!
