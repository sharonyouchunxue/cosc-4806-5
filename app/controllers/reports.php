<?php

require_once __DIR__ . '/../models/Reminder.php';
require_once __DIR__ . '/../models/User.php';

class Reports extends Controller
{
    private $reminderModel;
    private $userModel;

    public function __construct() {
        $this->reminderModel = new Reminder();
        $this->userModel = new User();
    }

    public function index()
    {
        $reminders = $this->reminderModel->get_all_reminders();
        $mostRemindersUser = $this->userModel->getUserWithMostReminders();

        // Fetch reminders for the user with most reminders
        $userReminders = [];
        if ($mostRemindersUser && isset($mostRemindersUser['id'])) {
            $userReminders = $this->userModel->getRemindersByUserId($mostRemindersUser['id']);
        }

        // Fetch total logins by username
        $totalLogins = $this->userModel->getTotalLoginsByUsername();

        $data = [
            'reminders' => $reminders,
            'mostRemindersUser' => $mostRemindersUser,
            'userReminders' => $userReminders,
            'totalLogins' => $totalLogins
        ];

        $this->view('reports/index', $data);
    }
}
?>
