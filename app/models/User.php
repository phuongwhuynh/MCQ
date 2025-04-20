<?php
require_once "Database.php";
class User
{
    static public function authenticate($username, $password)
    {
        try {
            $db = Database::connect();
            $query = "SELECT user_id, password_hash,role FROM users WHERE username = ? ";
            $stmt = $db->prepare($query);
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();

            if ($user && password_verify($password, $user['password_hash'])) {
                $_SESSION['user_role'] = $user['role'];
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['username'] = $username;
                return ["success" => true, "user_role" => $user['role']];
            } else {
                return ["success" => false, "message" => "Incorrect username or password!"];
            }
        } catch (mysqli_sql_exception $e) {
            return ["success" => false, "message" => $e->getMessage()];
        }
    }
    static public function signUp($username, $password, $email, $name, $role)
    {
        try {
            $db = Database::connect();

            $query = "SELECT user_id FROM users WHERE username = ?";
            $stmt = $db->prepare($query);
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->fetch_assoc()) {
                return ["success" => false, "message" => "Username already exists!"];
            }

            // Hash password
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);

            // Insert user
            $query = "INSERT INTO users (username, password_hash, email, name, role) VALUES (?, ?, ?, ?, ?)";
            $stmt = $db->prepare($query);
            $stmt->bind_param("sssss", $username, $passwordHash, $email, $name, $role);
            $stmt->execute();

            $user_id = $stmt->insert_id;

            if (strtolower($role) === 'admin') {
                $query = "INSERT INTO admin (user_id) VALUES (?)";
                $stmt = $db->prepare($query);
                $stmt->bind_param("i", $user_id);
                $stmt->execute();
            } else if (strtolower($role) === 'user') {
                $query = "INSERT INTO user (user_id) VALUES (?)";
                $stmt = $db->prepare($query);
                $stmt->bind_param("i", $user_id);
                $stmt->execute();
            }

            return ["success" => true, "message" => "Account created successfully!"];
        } catch (mysqli_sql_exception $e) {
            error_log("User::signUp error: " . $e->getMessage());
            return ["success" => false, "message" => $e->getMessage()];
        }
    }
    static public function getUserByGoogleId($google_id)
    {
        try {
            error_log("Google ID passed to getUserByGoogleId: " . $google_id);
            $db = Database::connect();
            $query = "SELECT * FROM users WHERE google_id = ?";
            $stmt = $db->prepare($query);
            $stmt->bind_param("s", $google_id);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->fetch_assoc();
        } catch (mysqli_sql_exception $e) {
            error_log("User::getUserByGoogleId error: " . $e->getMessage());
            return null;
        }
    }
    static public function createUser($username, $password, $email, $name, $role, $google_id)
    {
        try {
            $db = Database::connect();

            // Insert into users
            $query = "INSERT INTO users (username, password_hash, email, google_id, name, role) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $db->prepare($query);
            $stmt->bind_param("ssssss", $username, $password, $email, $google_id, $name, $role);
            $stmt->execute();

            error_log("Inserting user: $username | $email | $google_id");
            $user_id = $stmt->insert_id;

            // Insert into role-specific table
            if (strtolower($role) === 'admin') {
                $query = "INSERT INTO admin (user_id) VALUES (?)";
                $stmt = $db->prepare($query);
                $stmt->bind_param("i", $user_id);
                $stmt->execute();
            } else if (strtolower($role) === 'user') {
                $query = "INSERT INTO user (user_id) VALUES (?)";
                $stmt = $db->prepare($query);
                $stmt->bind_param("i", $user_id);
                $stmt->execute();
            }

            // Return user object
            return [
                "success" => true,
                "message" => "Account created successfully!",
                "user" => [
                    "id" => $user_id,
                    "username" => $username,
                    "email" => $email,
                    "name" => $name,
                    "role" => $role,
                    "google_id" => $google_id,
                ]
            ];
        } catch (mysqli_sql_exception $e) {
            error_log("User::createUser error: " . $e->getMessage());
            return [
                "success" => false,
                "message" => "An error occurred while creating the user.",
                "error" => $e->getMessage()
            ];
        }
    }
}