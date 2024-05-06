<?php

use PHPUnit\Framework\TestCase;

class TaskUpdateTest extends TestCase {
    private $task;
    private $db;

    protected function setUp(): void {
        $this->db = $this->createMock(PDO::class);
        $this->task = new Task(); // Предполагается, что ваш класс называется Task
        $this->task->setDb($this->db); // Предполагается метод для установки мок-объекта базы данных
    }

    public function testUpdateTaskInfo() {
        $data = [
            'task_title' => 'Updated Task',
            'task_description' => 'Updated Description',
            'translator_work_done' => 'No',
            't_start_time' => '2021-01-02 08:00:00',
            't_end_time' => '2021-01-02 17:00:00',
            'status' => 'Completed'
        ];
        $task_id = 123;
        $user_role = 1;

        $stmt = $this->createMock(PDOStatement::class);
        $this->db->expects($this->once())
                 ->method('prepare')
                 ->willReturn($stmt);

        $stmt->expects($this->exactly(7))
             ->method('bindparam')
             ->willReturn(true);

        $stmt->expects($this->once())
             ->method('execute')
             ->willReturn(true);

        $_SESSION = []; // Очистка сессии

        $this->task->update_task_info($data, $task_id, $user_role);

        $this->assertEquals('Task Update Successfully', $_SESSION['Task_msg']);
    }
}
