<?php
require_once "../app/models/History.php";
class HistoryController {
    public static function getHistory($data){
        $user_id=$_SESSION['user_id'];
        $limit=$data["limit"];
        $page=$data["page"];
        $response=History::getFinishedAttempts($user_id,$page,$limit);
        echo json_encode($response);
    }
}
?>