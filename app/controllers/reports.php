<?php
session_start();

if (!isset($_SESSION['auth'])) {
    header('Location: /login');
    exit();
}

require_once __DIR__ . '/../models/Reminder.php';

class ReportsController
{
    public function index()
    {
        $reminderModel = new Reminder();
        //$userModel = new User();
        
        $reminders = $reminderModel->get_all_reminders();
        //$mostRemindersUser = $userModel->getUserWithMostReminders();
        

        include __DIR__ . '/../views/reports/index.php';
    }
}

// Create an instance of the controller and call the index method
$controller = new ReportsController();
$controller->index();
?>
