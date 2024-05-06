<?php

use PHPUnit\Framework\TestCase;

class TaskTest extends TestCase {
    private $task;
    private $db;

    protected function setUp(): void {
        $this->db = $this->createMock(PDO::class);
        $this->task = new Task(); // Предполагается, что класс называется Task
        $this->task->setDb($this->db); // Предполагается, что есть метод setDb для инъекции зависимости
    }

    public function testAddNewTask() {
        $data = [
            'task_title' => 'New Task',
            'task_description' => 'Description',
            'translator_work_done' => 'Yes',
            't_start_time' => '2021-01-01 08:00:00',
            't_end_time' => '2021-01-01 17:00:00',
            'assign_to' => '1'
        ];

        $stmt = $this->createMock(PDOStatement::class);
        $this->db->expects($this->once())
                 ->method('prepare')
                 ->willReturn($stmt);

        $stmt->expects($this->exactly(6))
             ->method('bindparam')
             ->willReturn(true);

        $stmt->expects($this->once())
             ->method('execute')
             ->willReturn(true);

        $_SESSION = []; // Очистка сессии

        $this->task->add_new_task($data);

        $this->assertEquals('Task Add Successfully', $_SESSION['Task_msg']);
    }
}
