<?php

require_once 'app/models/Reminder.php';

class Reminders extends Controller {

    // Constructor to start session
    public function __construct() {
        session_start(); // Start session once in the constructor
    }

    // Function to display all reminder list.
    public function index() {
        // Reminder model instance creation
        $reminder = $this->model('Reminder');

        // Get all reminders from the model
        $list_of_reminders = $reminder->get_all_reminders();

        // Pass the reminder data to the view
        $this->view('reminders/index', ['reminders' => $list_of_reminders]);
    }

    // Function to create a new reminder
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_SESSION['userid'])) { // Use correct session variable
                $user_id = $_SESSION['userid']; // Get user id
                $subject = $_POST['subject']; 
                $date = $_POST['date']; // Calendar date
                $time = $_POST['time']; // Calendar time
                $reminder_time = $date . ' ' . $time; // Combine calendar date and time

                $reminder = $this->model('Reminder');
                $reminder->create_reminder($user_id, $subject, $reminder_time);

                // Set success message
                $_SESSION['success_message'] = "Reminder created successfully.";

                header('Location: /reminders/displayMessage');
            } else {
                // Handle error if user ID is not in session
                echo "User not logged in.";
            }
        } else {
            $this->view('reminders/create/index');
        }
    }

    // Helper function to display the success message and redirect to the reminders list
    public function displayMessage() {
        if (isset($_SESSION['success_message'])) {
            $message = $_SESSION['success_message'];
            unset($_SESSION['success_message']);
            $this->view('reminders/displayMessage/index', ['message' => $message]);
        } else {
            header('Location: /reminders');
        }
    }

    // Function to update an existing reminder
    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $subject = isset($_POST['subject']) ? $_POST['subject'] : null;
            $date = isset($_POST['date']) ? $_POST['date'] : null;
            $time = isset($_POST['time']) ? $_POST['time'] : null;
            $completed = isset($_POST['completed']) ? (bool)$_POST['completed'] : null;
            $deleted = isset($_POST['deleted']) ? (bool)$_POST['deleted'] : null;

            $update_data = [];

            if ($subject !== null) {
                $update_data['subject'] = $subject;
            }
            if ($date !== null && $time !== null) {
                $update_data['reminder_time'] = $date . ' ' . $time;
            }
            if ($completed !== null) {
                $update_data['completed'] = $completed;
            }
            if ($deleted !== null) {
                $update_data['deleted'] = $deleted;
            }

            if (!empty($update_data)) {
                $reminder = $this->model('Reminder');
                $reminder->update_reminder($id, $update_data);

                // Set success message
                $_SESSION['success_message'] = "Reminder updated successfully.";

                // Check if the request is AJAX
                if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                    echo json_encode(['success' => true, 'message' => "Reminder updated successfully."]);
                    return;
                }
            }

            header('Location: /reminders');
        } else {
            $reminder = $this->model('Reminder');
            $reminder_info = $reminder->get_reminder($id);
            $this->view('reminders/update', ['reminder' => $reminder_info]);
        }
    }

    // Function to delete a reminder
    public function delete($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $reminder = $this->model('Reminder');
            $reminder->delete_reminder($id);  
            header('Location: /reminders');
        }
    }
}
?>
