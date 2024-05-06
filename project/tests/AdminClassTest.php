<?php
use PHPUnit\Framework\TestCase;

class AdminClassTest extends TestCase
{
    protected $adminClass;

    protected function setUp(): void
    {
        $this->adminClass = $this->createMock(Admin_Class::class);
        $this->adminClass->method('test_form_input_data')
                         ->will($this->returnCallback(function ($input) {
                             return trim(htmlspecialchars(stripslashes($input)));
                         }));

        $this->adminClass->method('manage_all_info')
                         ->will($this->returnCallback(function ($sql) {
                             if (strpos($sql, 'SELECT email FROM') !== false) {
                                 return new class {
                                     public function rowCount() {
                                         return 0; // No email found
                                     }
                                 };
                             } elseif (strpos($sql, 'SELECT username FROM') !== false) {
                                 return new class {
                                     public function rowCount() {
                                         return 0; // No username found
                                     }
                                 };
                             }
                             throw new Exception("SQL query not recognized");
                         }));
        $this->adminClass->db = $this->createMock(PDO::class);
    }

    public function testAddNewUser_Success()
    {
        $this->adminClass->db->method('prepare')
                            ->will($this->returnCallback(function ($sql) {
                                $mockStmt = $this->createMock(PDOStatement::class);
                                $mockStmt->method('execute')->willReturn(true);
                                $mockStmt->method('bindparam')->willReturn(true);
                                return $mockStmt;
                            }));

        $data = [
            'em_fullname' => 'John Doe',
            'em_username' => 'johndoe',
            'em_email' => 'johndoe@example.com'
        ];

        $result = $this->adminClass->add_new_user($data);
        $this->assertNull($result); // Assuming no return on success
    }

    public function testAddNewUser_EmailExists()
    {
        $this->adminClass->method('manage_all_info')
                         ->will($this->returnCallback(function ($sql) {
                             if (strpos($sql, 'SELECT email FROM') !== false) {
                                 return new class {
                                     public function rowCount() {
                                         return 1; // Email found
                                     }
                                 };
                             }
                             return new class {
                                 public function rowCount() {
                                     return 0;
                                 }
                             };
                         }));

        $data = [
            'em_fullname' => 'John Doe',
            'em_username' => 'johndoe',
            'em_email' => 'johndoe@example.com'
        ];

        $result = $this->adminClass->add_new_user($data);
        $this->assertEquals('Email Already Taken', $result);
    }

    // Add more tests for different scenarios like username exists, both email and username exist, etc.
}
