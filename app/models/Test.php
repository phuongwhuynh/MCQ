<?php
require_once "Database.php";

class Test {
    public static function getPaginated($page, $limit, $sort, $categories, $searchTerm = '', $creator_id) {
        $db = Database::connect();
        $offset = ($page - 1) * $limit;
    
        // Default sorting
        $orderBy = "created_time DESC";
    
        // Category filter
        $inCate = !empty($categories)
            ? "cate IN (" . implode(',', array_map(fn($x) => "'$x'", $categories)) . ")"
            : "1=1";
    
        // Search condition
        $searchCondition = '';
        if (!empty($searchTerm)) {
            $searchCondition = " AND description LIKE ?";
        }
    
        // Sorting logic
        if ($sort === "description_desc") {
            $orderBy = "description DESC";
        }
        elseif ($sort === "description_asc") {
            $orderBy = "description ASC";
        } 
        elseif ($sort === "created_time_asc") {
            $orderBy = "created_time ASC";
        }
    
        // Updated query without the join with admin_cur_test_have_questions
        $query = "
            SELECT 
                q.* 
            FROM question q
            WHERE q.creator = ? AND q.status = 'active' AND $inCate $searchCondition
            ORDER BY $orderBy
            LIMIT ?, ?
        ";
    
        $stmt = $db->prepare($query);
    
        // Prepare the parameters
        $types = "iii"; 
        $params = [$creator_id, $offset, $limit];
    
        // If there's a search term, adjust the query and parameters
        if (!empty($searchTerm)) {
            $searchTerm = "%$searchTerm%";
            $query = "
                SELECT 
                    q.* 
                FROM question q
                WHERE q.creator = ? AND q.status = 'active' AND $inCate AND description LIKE ?
                ORDER BY $orderBy
                LIMIT ?, ?
            ";
    
            $stmt = $db->prepare($query);
            $types = "issii"; // q.creator, searchTerm, offset, limit
            $params = [$creator_id, $searchTerm, $offset, $limit];
        }
    
        // Bind parameters and execute the query
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $result = $stmt->get_result();
    
        // Collect the results
        $items = [];
        while ($row = $result->fetch_assoc()) {
            $items[] = $row;
        }
    
        return $items;
    }
    
    
    public static function countAllQuestions($categories = [], $searchTerm = '', $creatorId) {
        $db = Database::connect();
    
        $query = "SELECT COUNT(*) as total FROM question WHERE creator = ?";
    
        if (!empty($categories)) {
            $query .= " AND cate IN (" . implode(",", array_fill(0, count($categories), "?")) . ")";
        }
    
        if (!empty($searchTerm)) {
            $query .= " AND description LIKE ?";
        }
    
        $stmt = $db->prepare($query);
    
        $types = "i";
        $params = [$creatorId];
    
        if (!empty($categories)) {
            $types .= str_repeat("s", count($categories)); 
            $params = array_merge($params, $categories);
        }
    
        if (!empty($searchTerm)) {
            $searchTerm = "%$searchTerm%";
            $types .= "s"; 
            $params[] = $searchTerm;
        }
    
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
    
        return $result['total'] ?? 0;
    }
    
    // public static function cacheTest($creator, $total_time, $image_tmp = null, $fileExt = null, $question_ids = []) {
    //     $conn = Database::connect();
    //     $creator = (int)$creator;
    //     $total_time = (int)$total_time;
    
    //     $conn->begin_transaction();
    
    //     try {
    //         // Check if a test already exists
    //         $checkQuery = "SELECT creator FROM admin_cur_test WHERE creator = ?";
    //         $checkStmt = $conn->prepare($checkQuery);
    //         $checkStmt->bind_param("i", $creator);
    //         $checkStmt->execute();
    //         $result = $checkStmt->get_result();
    //         $exists = $result && $result->num_rows > 0;
    
    //         // Delete old image if overwriting test
    //         if ($exists) {
    //             $deleteOld = $conn->prepare("DELETE FROM admin_cur_test WHERE creator = ?");
    //             $deleteOld->bind_param("i", $creator);
    //             $deleteOld->execute();
    
    //             $deleteQuestions = $conn->prepare("DELETE FROM admin_cur_test_have_questions WHERE creator = ?");
    //             $deleteQuestions->bind_param("i", $creator);
    //             $deleteQuestions->execute();
    //         }
    
    //         // Insert test metadata (image_path handled later)
    //         $insertQuery = "INSERT INTO admin_cur_test (creator, total_time) VALUES (?, ?)";
    //         $insertStmt = $conn->prepare($insertQuery);
    //         $insertStmt->bind_param("ii", $creator, $total_time);
    //         if (!$insertStmt->execute()) {
    //             throw new Exception("Failed to insert/update test data: " . $insertStmt->error);
    //         }
    
    //         // Image handling
    //         if ($image_tmp && $fileExt) {
    //             $fileExt = strtolower($fileExt);
    //             $uploadPath = "images/current_tests/" . $creator . "." . $fileExt;
    //             $safePath = $conn->real_escape_string($uploadPath);
    
    //             $updateQuery = "UPDATE admin_cur_test SET image_path = ? WHERE creator = ?";
    //             $updateStmt = $conn->prepare($updateQuery);
    //             $updateStmt->bind_param("si", $safePath, $creator);
    //             if (!$updateStmt->execute()) {
    //                 throw new Exception("Failed to update image path: " . $updateStmt->error);
    //             }
    
    //             if (!copy($image_tmp, $uploadPath)) {
    //                 throw new Exception("Failed to copy image to $uploadPath.");
    //             }
    //         }
    
    //         if (!empty($question_ids)) {
    //             $insertQ = $conn->prepare("INSERT INTO admin_cur_test_have_questions (creator, question_id) VALUES (?, ?)");
    //             foreach ($question_ids as $qid) {
    //                 $qid = (int)$qid;
    //                 $insertQ->bind_param("ii", $creator, $qid);
    //                 if (!$insertQ->execute()) {
    //                     throw new Exception("Failed to link question ID $qid: " . $insertQ->error);
    //                 }
    //             }
    //             $insertQ->close();
    //         }
    
    //         $conn->commit();
    //         return [
    //             "success" => true,
    //             "message" => "Temporary test cached successfully."
    //         ];
    //     } catch (Exception $e) {
    //         $conn->rollback();
    //         return [
    //             "success" => false,
    //             "message" => "Error caching test: " . $e->getMessage()
    //         ];
    //     }
    // }
    // public static function getCachedTest($creator) {
    //     $conn = Database::connect();
    //     $creator = (int)$creator;
    
    //     // Fetch test metadata
    //     $query = "SELECT total_time, image_path FROM admin_cur_test WHERE creator = ? LIMIT 1";
    //     $stmt = $conn->prepare($query);
    //     $stmt->bind_param("i", $creator);
    //     $stmt->execute();
    //     $result = $stmt->get_result();
    
    //     if ($result && $result->num_rows > 0) {
    //         $testData = $result->fetch_assoc();
    
    //         // Fetch associated question IDs
    //         $questionQuery = "SELECT question_id FROM admin_cur_test_have_questions WHERE creator = ?";
    //         $questionStmt = $conn->prepare($questionQuery);
    //         $questionStmt->bind_param("i", $creator);
    //         $questionStmt->execute();
    //         $questionResult = $questionStmt->get_result();
    
    //         $questionIds = [];
    //         while ($row = $questionResult->fetch_assoc()) {
    //             $questionIds[] = (int)$row['question_id'];
    //         }
    
    //         return [
    //             "success" => true,
    //             "data" => [
    //                 "total_time" => (int)$testData['total_time'],
    //                 "image_path" => $testData['image_path'],
    //                 "question_ids" => $questionIds
    //             ]
    //         ];
    //     } else {
    //         return [
    //             "success" => false,
    //             "message" => "No cached test found for this creator."
    //         ];
    //     }
    //}
    public static function submitTest($total_time, $test_name, $creator, $image_tmp = null, $fileExt = null, $question_ids = [], $category) {
        $conn = Database::connect();
    
        // Sanitize inputs
        $total_time = (int)$total_time;
        $creator = (int)$creator;
        $image_path = null;
    
        $query = "INSERT INTO test (total_time, test_name, creator, image_path, cate) 
                  VALUES (?, ?, ?, ?, ?)";
        
        $stmt = $conn->prepare($query);
        $stmt->bind_param("isiss", $total_time, $test_name, $creator, $image_path, $category);
    
        if (!$stmt->execute()) {
            $conn->rollback();
            return [
                "success" => false,
                "message" => "Error adding test: " . $stmt->error
            ];
        }
    
        // Get the inserted test ID
        $testId = $conn->insert_id;
    
        // Step 2: Handle image upload if provided
        if ($image_tmp && $fileExt) {
            $fileExt = strtolower($fileExt);
            $uploadPath = "images/tests/" . $testId . "." . $fileExt;
            $image_path = $conn->real_escape_string($uploadPath); // Now set the image_path
    
            // Update the test record with the image path
            $updateQuery = "UPDATE test SET image_path = ? WHERE test_id = ?";
            $updateStmt = $conn->prepare($updateQuery);
            $updateStmt->bind_param("si", $image_path, $testId);
    
            if (!$updateStmt->execute()) {
                $conn->rollback();
                return [
                    "success" => false,
                    "message" => "Test created but error saving image path: " . $updateStmt->error
                ];
            }
    
            // Move the uploaded image to the correct directory
            if (!move_uploaded_file($image_tmp, $uploadPath)) {
                $conn->rollback();
                return [
                    "success" => false,
                    "message" => "Test created but could not save image. Changes have been discarded."
                ];
            }
        }
    
        // Step 3: Associate questions with the test in the 'test_have_question' table
        $number_of_questions = 0; // Initialize the counter for the number of questions
        if (!empty($question_ids)) {
            $stmt = $conn->prepare("INSERT INTO test_have_question (test_id, question_id,question_number) VALUES (?, ?, ?)");
    
            foreach ($question_ids as $question_id) {
                $question_number= $number_of_questions+1;
                $stmt->bind_param("iii", $testId, $question_id,$question_number);
                if (!$stmt->execute()) {
                    $conn->rollback();
                    return [
                        "success" => false,
                        "message" => "Error associating question with test: " . $stmt->error
                    ];
                }
                $number_of_questions++; // Increment the counter for each associated question
            }
    
            // Step 4: Update the test's number_of_questions field
            $updateQuery = "UPDATE test SET number_of_questions = ? WHERE test_id = ?";
            $updateStmt = $conn->prepare($updateQuery);
            $updateStmt->bind_param("ii", $number_of_questions, $testId);
            if (!$updateStmt->execute()) {
                $conn->rollback();
                return [
                    "success" => false,
                    "message" => "Error updating number_of_questions: " . $updateStmt->error
                ];
            }
        }
    
        // Step 5: Commit the transaction
        $conn->commit();
    
    
        return [
            "success" => true,
            "message" => "Test added successfully!",
            "test_id" => $testId
        ];
    }
        
    public static function getTest($page, $limit, $creator_id, $sort = 'created_time_desc', $categories = [], $searchTerm = '') {
        $db = Database::connect();
        $offset = ($page - 1) * $limit;
    
        // Default sorting
        $orderBy = "t.created_time DESC";
    
        // Category filter
        $inCate = !empty($categories)
            ? "AND t.cate IN (" . implode(',', array_map(fn($x) => "'$x'", $categories)) . ")"
            : "";
    
        // Search condition
        $searchCondition = '';
        $searchParams = [];
        if (!empty($searchTerm)) {
            $searchCondition = "AND t.test_name LIKE ?";
            $searchParams[] = "%$searchTerm%";
        }
    
        // Sorting
        if ($sort === "created_time_asc") {
            $orderBy = "t.created_time ASC";
        } elseif ($sort === "created_time_desc") {
            $orderBy = "t.created_time DESC";
        } 
    
        $query = "
            SELECT 
                t.test_id,
                t.total_time,
                t.cate AS test_category,
                t.number_of_questions,
                t.creator AS test_creator,
                t.created_time,
                t.status AS test_status,
                t.image_path AS test_image,
                t.test_name AS test_name,
                t.total_attempts as total_attempts
            FROM test t
            WHERE t.creator = ?
            AND t.status != 'deleted'
            $inCate
            $searchCondition
            ORDER BY $orderBy
            LIMIT ?, ?
        ";
    
        // Prepare parameters
        $stmt = $db->prepare($query);
        $types = "i" . (count($searchParams) ? "s" : "") . "ii";
        $params = array_merge([$creator_id], $searchParams, [$offset, $limit]);
    
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $result = $stmt->get_result();
    
        $tests = [];
        while ($row = $result->fetch_assoc()) {
            $tests[] = [
                'test_id' => $row['test_id'],
                'total_time' => $row['total_time'],
                'test_category' => $row['test_category'],
                'number_of_questions' => $row['number_of_questions'],
                'creator' => $row['test_creator'],
                'created_time' => $row['created_time'],
                'status' => $row['test_status'],
                'image_path' => $row['test_image'],
                'test_name' => $row['test_name'],
                'total_attempts' => $row['total_attempts']
            ];
        }
    
        return $tests;
    }
    
    public static function countTotalTests($creatorId, $categories = [], $searchTerm = '') {
        $db = Database::connect();
    
        $inCate = !empty($categories)
            ? "AND t.cate IN (" . implode(',', array_map(fn($x) => "'$x'", $categories)) . ")"
            : "";
    
        $searchCondition = !empty($searchTerm) ? "AND t.test_name LIKE ?" : "";
    
        $query = "
            SELECT COUNT(*) as total_tests
            FROM test t
            WHERE t.creator = ? AND t.status != 'deleted' $inCate $searchCondition
        ";
    
        $stmt = $db->prepare($query);
    
        $params = [$creatorId];
        $types = "i";
    
        if (!empty($searchTerm)) {
            $searchTerm = "%$searchTerm%";
            $params[] = $searchTerm;
            $types .= "s";
        }
    
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
    
        return $row['total_tests'];
    }
    
    public static function publish($test_id) {
        $db = Database::connect();
        
        $query = "UPDATE test SET status = 'public' WHERE test_id = ?";
        
        $stmt = $db->prepare($query);
        $stmt->bind_param("i", $test_id);
        
        if ($stmt->execute()) {
            return ['success' => true, 'message' => 'Test published successfully.'];
        } else {
            return ['success' => false, 'message' => 'Failed to publish the test.'];
        }
    }
    public static function privatize($test_id) {
        $db = Database::connect();
        
        $query = "UPDATE test SET status = 'private' WHERE test_id = ?";
        
        $stmt = $db->prepare($query);
        $stmt->bind_param("i", $test_id);
        
        if ($stmt->execute()) {
            return ['success' => true, 'message' => 'Test privatized successfully.'];
        } else {
            return ['success' => false, 'message' => 'Failed to privatized the test.'];
        }
    }
    public static function delete($test_id) {
        $db = Database::connect();
        
        $query = "UPDATE test SET status = 'deleted' WHERE test_id = ?";
        
        $stmt = $db->prepare($query);
        $stmt->bind_param("i", $test_id);
        
        if ($stmt->execute()) {
            return ['success' => true, 'message' => 'Test deleted successfully.'];
        } else {
            return ['success' => false, 'message' => 'Failed to deleted the test.'];
        }
    }

    public static function getQuestionsOfTest($test_id) {
        $db = Database::connect();
    
        $query = "
            SELECT tq.question_number, q.description, q.ans1, q.ans2, q.ans3, q.ans4, q.correct_answer, q.image_path
            FROM test_have_question tq
            JOIN question q ON tq.question_id = q.question_id
            WHERE tq.test_id = ?";
    
        // Prepare and execute the query
        if ($stmt = $db->prepare($query)) {
            $stmt->bind_param("i", $test_id);  // Bind test_id as an integer parameter
            $stmt->execute();
            $result = $stmt->get_result();
    
            // Fetch the questions and store them in an array
            $questions = [];
            while ($row = $result->fetch_assoc()) {
                $questions[] = [
                    'question_number' => $row['question_number'],
                    'description' => $row['description'],
                    'ans1' => $row['ans1'],
                    'ans2' => $row['ans2'],
                    'ans3' => $row['ans3'],
                    'ans4' => $row['ans4'],
                    'correct_answer' => $row['correct_answer'],
                    'imagePath' => $row['image_path']
                ];
            }
    
            $stmt->close();
    
            return $questions;
        } else {
            return false;
        }
    }
    public static function getPreviewQuestionsOfTest($test_id) {
        $db = Database::connect();
    
        // First, get test information
        $testQuery = "SELECT test_name, number_of_questions FROM test WHERE test_id = ?";
        $testInfo = null;
    
        if ($stmtTest = $db->prepare($testQuery)) {
            $stmtTest->bind_param("i", $test_id);
            $stmtTest->execute();
            $resultTest = $stmtTest->get_result();
            $testInfo = $resultTest->fetch_assoc();
            $stmtTest->close();
        }
    
        // If test not found, return false or null
        if (!$testInfo) {
            return false;
        }
    
        // Then get the preview questions
        $query = "
            SELECT tq.question_number, q.description, q.ans1, q.ans2, q.ans3, q.ans4, q.image_path
            FROM test_have_question tq
            JOIN question q ON tq.question_id = q.question_id
            WHERE tq.test_id = ?
            ORDER BY tq.question_number ASC
        ";
    
        $questions = [];
    
        if ($stmt = $db->prepare($query)) {
            $stmt->bind_param("i", $test_id);
            $stmt->execute();
            $result = $stmt->get_result();
    
            while ($row = $result->fetch_assoc()) {
                $questions[] = [
                    'question_number' => $row['question_number'],
                    'description' => $row['description'],
                    'ans1' => $row['ans1'],
                    'ans2' => $row['ans2'],
                    'ans3' => $row['ans3'],
                    'ans4' => $row['ans4'],
                    'imagePath' => $row['image_path']
                ];
            }
    
            $stmt->close();
        }
    
        return [
            'test_name' => $testInfo['test_name'],
            'number_of_questions' => $testInfo['number_of_questions'],
            'questions' => $questions
        ];
    }
    public static function startTestAttempt($test_id, $user_id) {
        $db = Database::connect();
        
        // Insert a new record into the test_attempt table
        $query = "INSERT INTO test_attempt (status, start_time, current_question, score, user_id, test_id) 
                  VALUES ('IN_PROGRESS', NOW(), 1, 0, ?, ?)";
        $stmt = $db->prepare($query);
        $stmt->bind_param("ii", $user_id, $test_id);
        $stmt->execute();
        
        $attempt_id = $stmt->insert_id;
        
        return $attempt_id;
    }
    public static function getAttempt($attempt_id) {
        $db = Database::connect();
        if (!$db) return false;
    
        // Step 1: Get attempt metadata
        $metaQuery = "SELECT ta.start_time, test.total_time, ta.current_question, ta.test_id
                      FROM test_attempt ta
                      JOIN test ON ta.test_id = test.test_id
                      WHERE ta.attempt_id = ?";
        $stmt = $db->prepare($metaQuery);
        if (!$stmt) return false;
        $stmt->bind_param("i", $attempt_id);
        $stmt->execute();
        $metaResult = $stmt->get_result();
        if (!$metaResult || !$metaRow = $metaResult->fetch_assoc()) return false;
    
        $start_time = $metaRow['start_time'];
        $total_time = (int) $metaRow['total_time'];
        $current_question = (int) $metaRow['current_question'];
        $test_id = (int) $metaRow['test_id'];
        $stmt->close();
    
        // Step 2: Count total number of questions
        $countQuery = "SELECT COUNT(*) AS total FROM test_have_question WHERE test_id = ?";
        $stmt = $db->prepare($countQuery);
        if (!$stmt) return false;
        $stmt->bind_param("i", $test_id);
        $stmt->execute();
        $countResult = $stmt->get_result();
        $total_questions = ($countResult && $row = $countResult->fetch_assoc()) ? (int) $row['total'] : 0;
        $stmt->close();
    
        if ($total_questions === 0) return false;
    
        // Step 3: If test is finished
        if ($current_question > $total_questions) {
            $scoreQuery = "SELECT COUNT(*) AS correct 
                           FROM chosen_answer ta
                           JOIN question q ON ta.question_id = q.question_id
                           WHERE ta.attempt_id = ? AND ta.answer = q.correct_answer";
            $stmt = $db->prepare($scoreQuery);
            if (!$stmt) return false;
            $stmt->bind_param("i", $attempt_id);
            $stmt->execute();
            $scoreResult = $stmt->get_result();
            $correct = ($scoreResult && $row = $scoreResult->fetch_assoc()) ? (int) $row['correct'] : 0;
            $stmt->close();
    
            // Update attempt status and score
            $updateQuery = "UPDATE test_attempt SET status = 'finished', score = ? WHERE attempt_id = ?";
            $stmt = $db->prepare($updateQuery);
            if ($stmt) {
                $stmt->bind_param("ii", $correct, $attempt_id);
                $stmt->execute();
                $stmt->close();
            }
    
            return [
                "finished" => true,
                "correct_answers" => $correct,
                "total_questions" => $total_questions
            ];
        }
    
        // Step 4: Fetch current question
        $query = "SELECT tq.question_id, q.description, q.ans1, q.ans2, q.ans3, q.ans4, q.image_path, q.correct_answer
                  FROM test_have_question tq
                  JOIN question q ON tq.question_id = q.question_id
                  WHERE tq.test_id = ? AND tq.question_number = ?";
        $stmt = $db->prepare($query);
        if (!$stmt) return false;
        $stmt->bind_param("ii", $test_id, $current_question);
        $stmt->execute();
        $result = $stmt->get_result();
    
        if ($row = $result->fetch_assoc()) {
            $elapsed_time = time() - strtotime($start_time);
            $remaining_time = max(0, ($total_time * 60) - $elapsed_time);
            $stmt->close();
    
            return [
                'remaining_time' => $remaining_time,
                'current_question' => $current_question,
                'total_questions' => $total_questions,
                'question' => [
                    'description' => $row['description'] ?? '',
                    'ans1' => $row['ans1'] ?? '',
                    'ans2' => $row['ans2'] ?? '',
                    'ans3' => $row['ans3'] ?? '',
                    'ans4' => $row['ans4'] ?? '',
                    'image_path' => $row['image_path'] ?? '',
                    'correct_answer' => $row['correct_ans'] ?? null
                ]
            ];
        }
    
        return false;
    }
            
    public static function nextQuestion($attempt_id) {
        $db = Database::connect();
        
        $query = "SELECT current_question, test_id
                  FROM test_attempt
                  WHERE attempt_id = ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param("i", $attempt_id);
        $stmt->execute();
        $result = $stmt->get_result();
    
        if ($row = $result->fetch_assoc()) {
            $current_question = $row['current_question'];
    
            $next_question = $current_question + 1;
    
            $query = "UPDATE test_attempt
                      SET current_question = ?
                      WHERE attempt_id = ?";
            $stmt = $db->prepare($query);
            $stmt->bind_param("ii", $next_question, $attempt_id);
            $stmt->execute();
    
            return self::getAttempt($attempt_id); 
        }
        
        return false; 
    }
    
    public static function saveAnswer($attempt_id, $answer) {
        $db = Database::connect();
    
        $query = "SELECT current_question, test_id 
                  FROM test_attempt 
                  WHERE attempt_id = ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param("i", $attempt_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if (!$row = $result->fetch_assoc()) {
            return ["status" => "error", "message" => "Attempt not found."];
        }
    
        $current_question = $row['current_question'];
        $test_id = $row['test_id'];
    
        $query = "SELECT question_id 
                  FROM test_have_question 
                  WHERE test_id = ? AND question_number = ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param("ii", $test_id, $current_question);
        $stmt->execute();
        $result = $stmt->get_result();
    
        if (!$row = $result->fetch_assoc()) {
            return ["status" => "error", "message" => "Question not found."];
        }
    
        $question_id = $row['question_id'];
    
        $query = "INSERT INTO chosen_answer (question_id, attempt_id, answer)
                  VALUES (?, ?, ?)
                  ON DUPLICATE KEY UPDATE answer = VALUES(answer)";
        $stmt = $db->prepare($query);
        $stmt->bind_param("iis", $question_id, $attempt_id, $answer);
        $stmt->execute();
    
        return ["status" => "success", "question_id" => $question_id];
    }
    
    

    

}    
?>