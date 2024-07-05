<?php
session_start();

class User
{
    public $username;
    public $password;
    public $auth = false;

    public function __construct()
    {
    }

    public function test()
    {
        $db = db_connect();
        $statement = $db->prepare("SELECT * FROM users;");
        $statement->execute();
        $rows = $statement->fetch(PDO::FETCH_ASSOC);
        return $rows;
    }

    public function authenticate($username, $password, $isAdmin = false)
    {
        $username = strtolower($username);
        $db = db_connect();

        // Check if user is locked out
        if (isset($_SESSION['lockout_time']) && time() < $_SESSION['lockout_time']) {
            $remaining_time = $_SESSION['lockout_time'] - time();
            $_SESSION['error'] = "Too many failed attempts. Please try again after " . $remaining_time . " seconds.";
            header('Location: /login');
            exit();
        }

        $statement = $db->prepare("SELECT * FROM users WHERE username = :name;");
        $statement->bindValue(':name', $username);
        $statement->execute();
        $rows = $statement->fetch(PDO::FETCH_ASSOC);

        // Check if password matches
        if ($rows && password_verify($password, $rows['password'])) {
            // Successful login
            if ($isAdmin && $rows['role'] !== 'admin') {
                $_SESSION['error'] = "You do not have admin privileges.";
                header('Location: /login');
                exit();
            }

            $_SESSION['auth'] = 1;
            $_SESSION['username'] = ucwords($username);
            $_SESSION['userid'] = $rows['id'];
            $_SESSION['role'] = $rows['role'];

            unset($_SESSION['failedAuth']);
            unset($_SESSION['lockout_time']);
            $this->log_attempt($username, 'good');
            return $rows;
        } else {
            // Failed login
            $this->log_attempt($username, 'bad');

            if (isset($_SESSION['failedAuth'])) {
                $_SESSION['failedAuth']++;
            } else {
                $_SESSION['failedAuth'] = 1;
            }

            // Check if failed attempts have reached 3
            if ($_SESSION['failedAuth'] >= 3) {
                $_SESSION['lockout_time'] = time() + 60; // Lockout duration in seconds
                $_SESSION['error'] = "Too many failed attempts. Please try again after 60 seconds.";
            } else {
                $_SESSION['error'] = "Invalid username or password.";
            }
            header('Location: /login');
            exit();
        }
    }

    public function create_user($username, $email, $password, $role = 'user')
    {
        $db = db_connect();

        // Check if username already exists
        if ($this->user_exists($username)) {
            $_SESSION['error'] = "Username already taken. Please choose another username.";
            header('Location: /create');
            exit();
        }

        // Validate password
        $password_validation = $this->validate_password($password);
        if ($password_validation !== true) {
            $_SESSION['error'] = $password_validation;
            header('Location: /create');
            exit();
        }

        // Create new user
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $statement = $db->prepare("INSERT INTO users (username, email, password, role) VALUES (:username, :email, :password, :role)");
        $statement->bindParam(':username', $username);
        $statement->bindParam(':email', $email);
        $statement->bindParam(':password', $hashed_password);
        $statement->bindParam(':role', $role);
        $statement->execute();

        // Success message and redirect
        $_SESSION['success'] = "Account created successfully!";
        header('Location: /login');
        exit();
    }

    public function user_exists($username)
    {
        $db = db_connect();
        $statement = $db->prepare("SELECT COUNT(*) FROM users WHERE username = :username");
        $statement->bindParam(':username', $username);
        $statement->execute();
        return $statement->fetchColumn() > 0;
    }

    public function validate_password($password)
    {
        if (strlen($password) < 8) {
            return "Password must be at least 8 characters long.";
        }
        if (!preg_match("#[0-9]+#", $password)) {
            return "Password must contain at least one number.";
        }
        if (!preg_match("#[A-Z]+#", $password)) {
            return "Password must contain at least one uppercase letter.";
        }
        if (!preg_match("#[a-z]+#", $password)) {
            return "Password must contain at least one lowercase letter.";
        }

        return true;
    }

    private function log_attempt($username, $attempt)
    {
        $db = db_connect();
        $statement = $db->prepare("INSERT INTO log (username, attempt, attempt_time) VALUES (:username, :attempt, NOW())");
        $statement->bindParam(':username', $username);
        $statement->bindParam(':attempt', $attempt);
        $statement->execute();
    }

    public static function getUserWithMostReminders() {
        $db = db_connect();
        $stmt = $db->query("SELECT username, COUNT(*) as reminder_count 
                            FROM reminders 
                            JOIN users ON reminders.user_id = users.id 
                            GROUP BY user_id 
                            ORDER BY reminder_count DESC 
                            LIMIT 1");
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function getLoginCounts() {
        $db = db_connect();
        $stmt = $db->query("SELECT username, COUNT(*) as login_count 
                            FROM log 
                            JOIN users ON log.user_id = users.id 
                            GROUP BY user_id");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
