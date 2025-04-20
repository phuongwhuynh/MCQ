<?php
require_once "../app/models/User.php";

class UserController
{
    public static function loginAttempt($data)
    {
        $requiredFields = ['username', 'password'];
        foreach ($requiredFields as $field) {
            if (empty($data[$field])) {
                echo json_encode([
                    "success" => false,
                    "message" => ucfirst($field) . " is required."
                ]);
                return;
            }
        }

        $username = $data["username"];
        $password = $data["password"];
        $response = User::authenticate($username, $password);
        header('Content-Type: application/json');
        echo json_encode($response);
    }
    public static function logOut()
    {
        session_unset();
        session_destroy();
        echo json_encode(["success" => true]);
    }
    public static function registerAttempt($data)
    {
        $requiredFields = ['username', 'password', 'email', 'name', 'lastName', 'role'];
        foreach ($requiredFields as $field) {
            if (empty($data[$field])) {
                echo json_encode([
                    "success" => false,
                    "message" => ucfirst($field) . " is required."
                ]);
                return;
            }
        }

        $username = $data["username"];
        $password = $data["password"];
        $email = trim($data["email"]);
        $name = trim($data["name"]) . " " . trim($data["lastName"]);
        $role = $data['role'];
        $response = User::signUp($username, $password, $email, $name, $role);
        header('Content-Type: application/json');
        echo json_encode($response);
    }

    public static function googleLogin($data)
    {
        $email = $data['email'] ?? null;
        $google_id = $data['google_id'] ?? null;
        $name = $data['name'] ?? null;

        if (!$email || !$google_id) {
            echo json_encode(["success" => false, "message" => "Missing Google account data"]);
            return;
        }
        // Check if user already exists
        $user = User::getUserByGoogleId($google_id);
        if ($user) {
            // User exists, log them in
            $_SESSION['user_role'] = $user['role'];
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            header('Content-Type: application/json');
            echo json_encode(["success" => true, "user_role" => $data['role']]);
        } else {
            $userByEmail = User::getUserByEmail($email);
            if ($userByEmail) {
                $updateUser = User::updateLinkUserIfEmailExists($email, $google_id);
                // User exists, log them in
                if ($updateUser['success'] == true) {
                    $_SESSION['user_role'] = $userByEmail['role'];
                    $_SESSION['user_id'] = $userByEmail['user_id'];
                    $_SESSION['username'] = $userByEmail['username'];
                    header('Content-Type: application/json');
                    echo json_encode(["success" => true, "user_role" => $data['role']]);
                }
            }
            $response = User::createUser(explode("@", $email)[0], null, $email, $name, "user", $google_id);
            $_SESSION['user_role'] = $response['user']['role'];
            $_SESSION['user_id'] = $response['user']['user_id'];
            $_SESSION['username'] = $response['user']['username'];
            header('Content-Type: application/json');
            echo json_encode(["success" => true, "user_role" => $response['user']['role']]);
        }
    }
}