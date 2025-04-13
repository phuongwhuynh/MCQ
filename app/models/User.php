<?php
require_once "Database.php";
class User {
    static public function authenticate($username,$password) {
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
    static public function signUp($username, $password, $email, $name, $role) {
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
            }
            else if (strtolower($role) === 'user') {
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
    
}
?>