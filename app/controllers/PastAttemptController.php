<?php
require_once "../app/models/PastAttempt.php";
class PastAttemptController {
    public static function getPastAttempt($data){
        $attempt_id=$data['attempt_id'];
        $response=PastAttempt::getPastAttempt($attempt_id);
        echo json_encode($response);
    }
}
?>