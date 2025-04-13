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
    
        // Updated query: LEFT JOIN with admin_cur_test_have_questions
        $query = "
            SELECT 
                q.*, 
                COALESCE(act.creator, 0) AS is_selected
            FROM question q
            LEFT JOIN admin_cur_test_have_questions act
                ON q.question_id = act.question_id AND act.creator = ?
            WHERE q.creator = ? AND q.status = 'active' AND $inCate $searchCondition
            ORDER BY $orderBy
            LIMIT ?, ?
        ";
    
        $stmt = $db->prepare($query);
    
        // Prepare the parameters
        $types = "iiii"; 
        $params = [$creator_id, $creator_id, $offset, $limit];
    
        // If there's a search term, adjust the query and parameters
        if (!empty($searchTerm)) {
            $searchTerm = "%$searchTerm%";
            $query = "
                SELECT 
                    q.*, 
                    COALESCE(act.creator, 0) AS is_selected
                FROM question q
                LEFT JOIN admin_cur_test_have_questions act
                    ON q.question_id = act.question_id AND act.creator = ?
                WHERE q.creator = ? AND q.status = 'active' AND $inCate AND description LIKE ?
                ORDER BY $orderBy
                LIMIT ?, ?
            ";
    
            $stmt = $db->prepare($query);
            $types = "iisii"; // act.creator, q.creator, searchTerm, offset, limit
            $params = [$creator_id, $creator_id, $searchTerm, $offset, $limit];
        }
    
        // Bind parameters and execute the query
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $result = $stmt->get_result();
    
        // Collect the results
        $items = [];
        while ($row = $result->fetch_assoc()) {
            $row['is_selected'] = (bool)$row['is_selected']; // Cast to boolean
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
    
    public static function cacheTest($creator, $total_time, $image_tmp = null, $fileExt = null, $question_ids = []) {
        $conn = Database::connect();
        $creator = (int)$creator;
        $total_time = (int)$total_time;
    
        $conn->begin_transaction();
    
        try {
            // Check if a test already exists
            $checkQuery = "SELECT creator FROM admin_cur_test WHERE creator = ?";
            $checkStmt = $conn->prepare($checkQuery);
            $checkStmt->bind_param("i", $creator);
            $checkStmt->execute();
            $result = $checkStmt->get_result();
            $exists = $result && $result->num_rows > 0;
    
            // Delete old image if overwriting test
            if ($exists) {
                $deleteOld = $conn->prepare("DELETE FROM admin_cur_test WHERE creator = ?");
                $deleteOld->bind_param("i", $creator);
                $deleteOld->execute();
    
                $deleteQuestions = $conn->prepare("DELETE FROM admin_cur_test_have_questions WHERE creator = ?");
                $deleteQuestions->bind_param("i", $creator);
                $deleteQuestions->execute();
            }
    
            // Insert test metadata (image_path handled later)
            $insertQuery = "INSERT INTO admin_cur_test (creator, total_time) VALUES (?, ?)";
            $insertStmt = $conn->prepare($insertQuery);
            $insertStmt->bind_param("ii", $creator, $total_time);
            if (!$insertStmt->execute()) {
                throw new Exception("Failed to insert/update test data: " . $insertStmt->error);
            }
    
            // Image handling
            if ($image_tmp && $fileExt) {
                $fileExt = strtolower($fileExt);
                $uploadPath = "images/current_tests/" . $creator . "." . $fileExt;
                $safePath = $conn->real_escape_string($uploadPath);
    
                $updateQuery = "UPDATE admin_cur_test SET image_path = ? WHERE creator = ?";
                $updateStmt = $conn->prepare($updateQuery);
                $updateStmt->bind_param("si", $safePath, $creator);
                if (!$updateStmt->execute()) {
                    throw new Exception("Failed to update image path: " . $updateStmt->error);
                }
    
                if (!copy($image_tmp, $uploadPath)) {
                    throw new Exception("Failed to copy image to $uploadPath.");
                }
            }
    
            if (!empty($question_ids)) {
                $insertQ = $conn->prepare("INSERT INTO admin_cur_test_have_questions (creator, question_id) VALUES (?, ?)");
                foreach ($question_ids as $qid) {
                    $qid = (int)$qid;
                    $insertQ->bind_param("ii", $creator, $qid);
                    if (!$insertQ->execute()) {
                        throw new Exception("Failed to link question ID $qid: " . $insertQ->error);
                    }
                }
                $insertQ->close();
            }
    
            $conn->commit();
            return [
                "success" => true,
                "message" => "Temporary test cached successfully."
            ];
        } catch (Exception $e) {
            $conn->rollback();
            return [
                "success" => false,
                "message" => "Error caching test: " . $e->getMessage()
            ];
        }
    }
    public static function getCachedTest($creator) {
        $conn = Database::connect();
        $creator = (int)$creator;
    
        // Fetch test metadata
        $query = "SELECT total_time, image_path FROM admin_cur_test WHERE creator = ? LIMIT 1";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $creator);
        $stmt->execute();
        $result = $stmt->get_result();
    
        if ($result && $result->num_rows > 0) {
            $testData = $result->fetch_assoc();
    
            // Fetch associated question IDs
            $questionQuery = "SELECT question_id FROM admin_cur_test_have_questions WHERE creator = ?";
            $questionStmt = $conn->prepare($questionQuery);
            $questionStmt->bind_param("i", $creator);
            $questionStmt->execute();
            $questionResult = $questionStmt->get_result();
    
            $questionIds = [];
            while ($row = $questionResult->fetch_assoc()) {
                $questionIds[] = (int)$row['question_id'];
            }
    
            return [
                "success" => true,
                "data" => [
                    "total_time" => (int)$testData['total_time'],
                    "image_path" => $testData['image_path'],
                    "question_ids" => $questionIds
                ]
            ];
        } else {
            return [
                "success" => false,
                "message" => "No cached test found for this creator."
            ];
        }
    }
    
    
}    
?>