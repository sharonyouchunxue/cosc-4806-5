<?php

class Reminder {

    public function __construct() {
    }
    

    // Function to read and view a single reminder by id
    public function get_reminder($id) {
        $db = db_connect();
        $statement = $db->prepare("SELECT * FROM reminders WHERE id = :id AND deleted = FALSE;");
        $statement->execute([':id' => $id]);
        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    // Function to read and view all reminders
    public function get_all_reminders() {
        $db = db_connect();
        $statement = $db->prepare("SELECT * FROM reminders WHERE deleted = FALSE;");
        $statement->execute();
        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $rows;
    }

    // Function to allow user to create a reminder after login
    public function create_reminder($user_id, $subject, $reminder_time) {
        $db = db_connect();
        $statement = $db->prepare("INSERT INTO reminders (user_id, subject, create_at, reminder_time) VALUES (:user_id, :subject, NOW(), :reminder_time)");
        $statement->execute([':user_id' => $user_id, ':subject' => $subject, ':reminder_time' => $reminder_time]);
        return $db->lastInsertId();
    }


    // Function to allow user to update a reminder
    public function update_reminder($reminder_id, $update_data) {
        $db = db_connect();
        $query = "UPDATE reminders SET ";
        $params = [];

        foreach ($update_data as $key => $value) {
            $query .= "$key = :$key, ";
            $params[":$key"] = $value;
        }

        // Remove trailing comma and space
        $query = rtrim($query, ', ') . " WHERE id = :id;";
        $params[':id'] = $reminder_id; 

        // Ensure there are fields to update
        if (count($params) > 1) {
            $statement = $db->prepare($query);
            $statement->execute($params);
        } else {
            throw new Exception("No fields to update.");
        }
    }

    // Function to delete a reminder
    public function delete_reminder($reminder_id) {
        $this->update_reminder($reminder_id, ['deleted' => true]);
    }
}
?>
