<?php

class Login extends Controller
{
		// To render login view
		public function index()
		{
				$this->view('login/index');
		}

		// This function is to verify the user's credentials
		public function verify()
		{
				session_start();

				// Retrieve username, password, and admin checkbox from the POST request
				$username = $_POST['username'];
				$password = $_POST['password'];
				$isAdmin = isset($_POST['admin']) ? true : false;

				// Load the User model and call the authenticate method
				$userModel = $this->model('User');
				$user = $userModel->authenticate($username, $password, $isAdmin);

				// Check if the user is currently locked out because of too many failed attempts
				if (isset($_SESSION['lockout_time']) && time() < $_SESSION['lockout_time']) {
						// Calculate the remaining lockout time
						$remaining_time = $_SESSION['lockout_time'] - time();
						// Set an error message and redirect to the login page after the remaining lockout time
						$_SESSION['error'] = "Too many failed attempts. Please try again after " . $remaining_time . " seconds.";
						header('Location: /login');
						exit();
				}

				if ($user) {
						$_SESSION['auth'] = 1;
						$_SESSION['username'] = $user['username'];
						$_SESSION['userid'] = $user['id'];
						$_SESSION['role'] = $user['role'];

						// Redirect based on role
						if ($user['role'] === 'admin') {
								header('Location: /admin');
						} else {
								header('Location: /home');
						}
						exit();
				} else {
						// Handle login failure
						$_SESSION['error'] = "Invalid username or password.";
						header('Location: /login');
						exit();
				}
		}

		// This function is to create new user
		public function create()
		{
				$username = $_POST['username'];
				$email = $_POST['email'];
				$password = $_POST['password'];

				// Load the User model and call the create_user method
				$userModel = $this->model('User');
				$userModel->create_user($username, $email, $password);
		}
}
?>
