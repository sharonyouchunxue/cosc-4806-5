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

        // Debugging: log the results
        error_log('Reminders: ' . print_r($reminders, true));
        error_log('User with most reminders: ' . print_r($mostRemindersUser, true));

        $data = [
            'reminders' => $reminders,
            'mostRemindersUser' => $mostRemindersUser
        ];

        $this->view('reports/index', $data);
    }
}
?>
