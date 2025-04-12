<?php
require_once "../app/models/User.php";
class UserController {
    public static function loginAttempt($data) {
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

        $username=$data["username"];
        $password=$data["password"];
        $response=User::authenticate($username,$password);
        header('Content-Type: application/json');
        echo json_encode($response);

    }
    public static function logOut() {
        session_unset(); 
        session_destroy(); 
        echo json_encode(["success" => true]); 
    }
    public static function registerAttempt($data) {
        $requiredFields = ['username', 'password', 'email', 'name', 'lastName'];
        foreach ($requiredFields as $field) {
            if (empty($data[$field])) {
                echo json_encode([
                    "success" => false,
                    "message" => ucfirst($field) . " is required."
                ]);
                return;
            }
        }
    
        $username=$data["username"];
        $password=$data["password"];
        $email=trim($data["email"]);
        $name = trim($data["name"]) . " " . trim($data["lastName"]);
        $response=User::signUp($username,$password,$email,$name);
        header('Content-Type: application/json');
        echo json_encode($response);

    

    }
}
?>