<?php
require_once "Database.php";
class History {
    public static function getFinishedAttempts($user_id, $page = 1, $limit = 10) {
        $db = Database::connect();
        $now = time();
        $offset = ($page - 1) * $limit;
    
        // Step 1: Check and finalize overdue attempts for the user
        $checkQuery = "SELECT ta.attempt_id, ta.start_time, t.total_time
                       FROM test_attempt ta
                       JOIN test t ON ta.test_id = t.test_id
                       WHERE ta.user_id = ? AND ta.status = 'IN_PROGRESS'";
        $checkStmt = $db->prepare($checkQuery);
        $checkStmt->bind_param("i", $user_id);
        $checkStmt->execute();
        $checkRes = $checkStmt->get_result();
    
        while ($row = $checkRes->fetch_assoc()) {
            $startTimestamp = strtotime($row['start_time']);
            $totalTimeInSeconds = $row['total_time'] * 60;
            $elapsed = $now - $startTimestamp;
            $remaining = max(0, $totalTimeInSeconds - $elapsed);
    
            if ($remaining === 0) {
                $attemptId = $row['attempt_id'];
    
                // Calculate score
                $scoreQuery = "SELECT COUNT(*) AS correct
                               FROM chosen_answer ca
                               JOIN question q ON ca.question_id = q.question_id
                               WHERE ca.attempt_id = ? AND ca.answer = q.correct_answer";
                $scoreStmt = $db->prepare($scoreQuery);
                $scoreStmt->bind_param("i", $attemptId);
                $scoreStmt->execute();
                $score = $scoreStmt->get_result()->fetch_assoc()['correct'] ?? 0;
    
                // Update to FINISHED
                $updateQuery = "UPDATE test_attempt SET status = 'FINISHED', score = ? WHERE attempt_id = ?";
                $updateStmt = $db->prepare($updateQuery);
                $updateStmt->bind_param("ii", $score, $attemptId);
                $updateStmt->execute();
            }
        }
    
        // Step 2: Count total FINISHED attempts
        $countQuery = "SELECT COUNT(*) AS total FROM test_attempt WHERE user_id = ? AND status = 'FINISHED'";
        $countStmt = $db->prepare($countQuery);
        $countStmt->bind_param("i", $user_id);
        $countStmt->execute();
        $total = $countStmt->get_result()->fetch_assoc()['total'] ?? 0;
    
        // Step 3: Fetch FINISHED attempts with pagination
        $fetchQuery = "SELECT ta.attempt_id, ta.score, ta.start_time, t.test_name, t.number_of_questions
                       FROM test_attempt ta
                       JOIN test t ON ta.test_id = t.test_id
                       WHERE ta.user_id = ? AND ta.status = 'FINISHED'
                       ORDER BY ta.start_time DESC
                       LIMIT ? OFFSET ?";
        $fetchStmt = $db->prepare($fetchQuery);
        $fetchStmt->bind_param("iii", $user_id, $limit, $offset);
        $fetchStmt->execute();
        $fetchRes = $fetchStmt->get_result();
    
        $results = [];
        while ($row = $fetchRes->fetch_assoc()) {
            $results[] = [
                'attempt_id' => $row['attempt_id'],
                'test_name' => $row['test_name'],
                'score' => (int) $row['score'],
                'total_questions' => (int) $row['number_of_questions'],
                'start_time' => $row['start_time']
            ];
        }
    
        return [
            'total' => (int) $total,
            'attempts' => $results
        ];
    }
            
}
?>