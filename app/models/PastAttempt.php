<?php
require_once "Database.php";
class PastAttempt {
    public static function getPastAttempt($attempt_id) {
        $db=Database::connect();
    
        $sql = "
            SELECT 
                t.test_name AS test_name,
                q.description,
                q.ans1,
                q.ans2,
                q.ans3,
                q.ans4,
                q.correct_answer,
                ca.answer AS chosen_answer,
                thq.question_number,
                tq.total_questions,
                ta.score
            FROM test_attempt ta
            JOIN test t ON ta.test_id = t.test_id
            JOIN test_have_question thq ON ta.test_id = thq.test_id
            JOIN question q ON thq.question_id = q.question_id
            LEFT JOIN chosen_answer ca 
                ON ca.question_id = q.question_id AND ca.attempt_id = ta.attempt_id
            JOIN (
                SELECT test_id, COUNT(*) AS total_questions
                FROM test_have_question
                GROUP BY test_id
            ) tq ON ta.test_id = tq.test_id
            WHERE ta.attempt_id = ?
            ORDER BY thq.question_number ASC
        ";
    
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $attempt_id);
        $stmt->execute();
        $result = $stmt->get_result();
    
        $questions = [];
        $score = 0;
        $total_questions = 0;
        $test_name = '';
    
        while ($row = $result->fetch_assoc()) {
            $score = (int) $row['score'];
            $total_questions = (int) $row['total_questions'];
            $test_name = $row['test_name'];
    
            $questions[] = [
                'question_number' => (int) $row['question_number'],
                'description'     => $row['description'],
                'ans1'            => $row['ans1'],
                'ans2'            => $row['ans2'],
                'ans3'            => $row['ans3'],
                'ans4'            => $row['ans4'],
                'correct_answer'  => $row['correct_answer'],
                'chosen_answer'   => $row['chosen_answer'] // may be null
            ];
        }
    
        return [
            'test_name' => $test_name,
            'score' => $score,
            'total_questions' => $total_questions,
            'questions' => $questions,
        ];
    }
    
            
}
?>