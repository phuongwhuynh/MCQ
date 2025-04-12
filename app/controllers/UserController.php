<?php
require_once "../app/models/User.php";
class UserController {
    public static function loginAttempt($data) {
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
        $username=$data["username"];
        $password=$data["password"];
        $email=$data["email"];
        $name=$data["name"];
        $response=User::signUp($username,$password,$email,$name);
        header('Content-Type: application/json');
        echo json_encode($response);

    }
}
?>