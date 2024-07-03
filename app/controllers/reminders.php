<?php

require_once 'app/models/Reminder.php';

class Reminders extends Controller {

    //Function to display all reminder list.
    public function index() {
        //reminder model instance creation
        $reminder = $this->model('Reminder');

        //get all reminders from the model
        $list_of_reminders = $reminder->get_all_reminders();

        //pass the reminder data to the view
        $this->view('reminders/index', ['reminders' => $list_of_reminders]);
    }

    //Function to create a new reminder
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            session_start(); 
            $user_id = $_SESSION['user_id']; //get user id
            $subject = $_POST['subject']; 
            $date = $_POST['date']; //calendar date
            $time = $_POST['time']; //calandar time
            $reminder_time = $date . ' ' . $time; //combine calendar
            
            $reminder = $this->model('Reminder');
            $reminder->create_reminder($user_id, $subject, $reminder_time);

            // Set success message
            $_SESSION['success_message'] = "Reminder created successfully.";

            header('Location: /reminders/displayMessage');
            
        } else {
            $this->view('reminders/create/index');
        }
    }

    //helper function to display the success message and redirect to the reminders list
    public function displayMessage() {
        session_start();
        if (isset($_SESSION['success_message'])) {
            $message = $_SESSION['success_message'];
            unset($_SESSION['success_message']);
            $this->view('reminders/displayMessage/index', ['message' => $message]);
        } else {
            header('Location: /reminders');
        }
    }

    //function to update an existing reminder
    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            session_start();
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

    //function to delete a reminder
    public function delete($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $reminder = $this->model('Reminder');
            $reminder->delete_reminder($id);  
            header('Location: /reminders');
        }
    }
}
?>
