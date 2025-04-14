<?php
require_once "../app/models/Dashboard.php";
class DashboardController {
    public static function getDashboard(){
        $user_id=$_SESSION['user_id'];
        $response=Dashboard::getInProgress($user_id);
        echo json_encode($response);
    }
}
?>