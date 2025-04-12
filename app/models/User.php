<?php
require_once "Database.php";
class User {
    static public function authenticate($username,$password) {
        try {
            $db = Database::connect();
            $query = "SELECT user_id, password_hash,role FROM accounts WHERE username = ? and status='active'";
            $stmt = $db->prepare($query);
            $stmt->bind_param("s", $username);  
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();
            
            if ($user && password_verify($password, $user['password_hash'])) {
                $_SESSION['user_role'] = $user['role']; 
                $_SESSION['user_id']=$user['user_id'];
                $_SESSION['username']=$username;
                return ["success" => true,"user_role" => $user['role']];
            } else {
                return ["success" => false, "message" => "Incorrect username or password!"];
            }
        }
        catch (mysqli_sql_exception $e){
            return ["success" => false, "message" => $e->getMessage()];
        }
    }
    static public function signUp($username, $password,$email,$name) {
        try {
            $db = Database::connect();
    
            // Check if username already exists
            $query = "SELECT user_id FROM accounts WHERE username = ?";
            $stmt = $db->prepare($query);
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->fetch_assoc()) {
                return ["success" => false, "message" => "Username already exists!"];
            }
    
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
    
            $query = "INSERT INTO accounts (username, password_hash, email, name, role) VALUES (?, ?, ?, ?, 'user')";
            $stmt = $db->prepare($query);
            $stmt->bind_param("ssss", $username, $passwordHash,$email,$name);
            $stmt->execute();
    
            return ["success" => true, "message" => "Account created successfully!"];
        } catch (mysqli_sql_exception $e) {
            error_log("User::signUp error: " . $e->getMessage());
            return ["success" => false, "message" => "An error occurred. Please try again later."];

        }
    
    }
}
?>