<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: /login');
    exit();
}

include_once '../models/Reminder.php';
include_once '../models/User.php';

class ReportsController {
    public function index() {
        $reminderModel = new Reminder();
        $userModel = new User();

        $reminders = $reminderModel->get_all_reminders();
        $mostRemindersUser = $userModel->getUserWithMostReminders();
        $loginCounts = $userModel->getLoginCounts();

        include '../views/reports/index.php';
    }
}

$controller = new ReportsController();
$controller->index();
?>
