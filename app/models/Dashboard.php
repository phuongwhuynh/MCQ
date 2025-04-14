<?php
require_once "Database.php";
class Dashboard {
    public static function getInProgress($user_id) {
        $db = Database::connect();
        $now = time();
        $results = [];
    
        $query = "SELECT ta.attempt_id, ta.start_time, t.total_time, t.test_name, t.image_path, t.test_id 
                  FROM test_attempt ta
                  JOIN test t ON ta.test_id = t.test_id
                  WHERE ta.user_id = ? AND ta.status = 'IN_PROGRESS'               
                  ORDER BY ta.start_time DESC";

        $stmt = $db->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $res = $stmt->get_result();
    
        while ($row = $res->fetch_assoc()) {
            $startTimestamp = strtotime($row['start_time']);
            $totalTimeInSeconds = $row['total_time'] * 60;
            $elapsedTime = $now - $startTimestamp;
            $remainingTime = max(0, $totalTimeInSeconds - $elapsedTime);
    
            // If overdue, finish attempt
            if ($remainingTime === 0) {
                // Calculate score
                $scoreQuery = "SELECT COUNT(*) AS correct
                               FROM chosen_answer ta
                               JOIN question q ON ta.question_id = q.question_id
                               WHERE ta.attempt_id = ? AND ta.answer = q.correct_answer";
                $scoreStmt = $db->prepare($scoreQuery);
                $scoreStmt->bind_param("i", $row['attempt_id']);
                $scoreStmt->execute();
                $score = $scoreStmt->get_result()->fetch_assoc()['correct'] ?? 0;
    
                // Finalize attempt
                $updateQuery = "UPDATE test_attempt SET status = 'FINISHED', score = ? WHERE attempt_id = ?";
                $updateStmt = $db->prepare($updateQuery);
                $updateStmt->bind_param("ii", $score, $row['attempt_id']);
                $updateStmt->execute();
            } else {
                // Still in progress
                $results[] = [
                    'attempt_id' => $row['attempt_id'],
                    'remaining_time' => $remainingTime,
                    'test_name' => $row['test_name'],
                    'image_path' => $row['image_path']
                ];
            }
        }
    
        return $results;
    }
    
}
?>