<?php
require_once "Database.php";
class Question {
// Function to submit a question
public static function submitQuestion($description, $ans1, $ans2, $ans3, $ans4, $correct_answer, $category, $image_tmp = null, $fileExt = null, $creator,$existingImagePath = null) {
    $conn = Database::connect();

    $description = $conn->real_escape_string($description);
    $ans1 = $conn->real_escape_string($ans1);
    $ans2 = $conn->real_escape_string($ans2);
    $ans3 = $conn->real_escape_string($ans3);
    $ans4 = $conn->real_escape_string($ans4);
    $correct_answer = (int)$correct_answer;
    $category = $conn->real_escape_string($category);
    $creator = (int)$creator;

    $conn->begin_transaction();

    $query = "INSERT INTO question (description, ans1, ans2, ans3, ans4, correct_answer, cate, creator)
              VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssssssi", $description, $ans1, $ans2, $ans3, $ans4, $correct_answer, $category, $creator);

    if (!$stmt->execute()) {
        $conn->rollback();
        return [
            "success" => false,
            "message" => "Error adding question: " . $stmt->error
        ];
    }

    $questionId = $conn->insert_id;


    if ($image_tmp && $fileExt) {
        $fileExt = strtolower($fileExt);
        $uploadPath = "images/questions/" . $questionId . "." . $fileExt;
        $safePath = $conn->real_escape_string($uploadPath);
    
        // Save new image path
        $updateQuery = "UPDATE question SET image_path = ? WHERE question_id = ?";
        $updateStmt = $conn->prepare($updateQuery);
        $updateStmt->bind_param("si", $safePath, $questionId);
    
        if (!$updateStmt->execute()) {
            $conn->rollback();
            return [
                "success" => false,
                "message" => "Question created but error saving image path: " . $updateStmt->error
            ];
        }
    
        if (!move_uploaded_file($image_tmp, $uploadPath)) {
            $conn->rollback();
            return [
                "success" => false,
                "message" => "Question created but could not save image. Changes have been discarded."
            ];
        }
    
    } elseif ($existingImagePath) {
        $safePath = $conn->real_escape_string($existingImagePath);
        $updateQuery = "UPDATE question SET image_path = ? WHERE question_id = ?";
        $updateStmt = $conn->prepare($updateQuery);
        $updateStmt->bind_param("si", $safePath, $questionId);
        $updateStmt->execute(); 
    }
    

    $conn->commit();

    $deleteQuery = "DELETE FROM admin_cur_question WHERE creator = ?";
    $deleteStmt = $conn->prepare($deleteQuery);
    $deleteStmt->bind_param("i", $creator);
    $deleteStmt->execute();

    return [
        "success" => true,
        "message" => "Question added successfully!",
        "question_id" => $questionId
    ];
}
// Function to cache admin's current question
public static function cacheAdminCurrentQuestion($description, $ans1, $ans2, $ans3, $ans4, $correct_answer, $category, $image_tmp = null, $fileExt = null, $creator) {
    $conn = Database::connect();

    $description = $conn->real_escape_string($description);
    $ans1 = $conn->real_escape_string($ans1);
    $ans2 = $conn->real_escape_string($ans2);
    $ans3 = $conn->real_escape_string($ans3);
    $ans4 = $conn->real_escape_string($ans4);
    $creator = (int)$creator;

    if ($category !== null) {
        $category = $conn->real_escape_string($category);
    } else {
        $category = null;
    }

    if ($correct_answer !== null) {
        $correct_answer = (int)$correct_answer;
    } else {
        $correct_answer = null;
    }

    $conn->begin_transaction();

    $query = "SELECT creator FROM admin_cur_question WHERE creator = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $creator);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        // Update existing cached question
        $updateQuery = "UPDATE admin_cur_question 
                        SET description = ?, ans1 = ?, ans2 = ?, ans3 = ?, ans4 = ?, correct_answer = ?, cate = ?
                        WHERE creator = ?";
        $updateStmt = $conn->prepare($updateQuery);
        $updateStmt->bind_param("sssssssi", $description, $ans1, $ans2, $ans3, $ans4, $correct_answer, $category, $creator);

        if (!$updateStmt->execute()) {
            $conn->rollback();
            return [
                "success" => false,
                "message" => "Error updating cached question: " . $updateStmt->error
            ];
        }
    } else {
        // Insert new cached question
        $insertQuery = "INSERT INTO admin_cur_question (creator, description, ans1, ans2, ans3, ans4, correct_answer, cate)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $insertStmt = $conn->prepare($insertQuery);
        $insertStmt->bind_param("isssssss", $creator, $description, $ans1, $ans2, $ans3, $ans4, $correct_answer, $category);

        if (!$insertStmt->execute()) {
            $conn->rollback();
            return [
                "success" => false,
                "message" => "Error creating temporary cached question: " . $insertStmt->error
            ];
        }
    }

    
    $conn->commit();
    return [
        "success" => true,
        "message" => "Temporary question saved successfully!",
        "creator" => $creator
    ];
}

public static function getCachedAdminCurrentQuestion($creator) {
    $conn = Database::connect();
    $creator = (int)$creator;

    $query = "SELECT description, ans1, ans2, ans3, ans4, correct_answer, cate, image_path
              FROM admin_cur_question
              WHERE creator = ?
              LIMIT 1";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $creator);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return [
            "success" => true,
            "data" => [
                "description" => $row['description'],
                "ans1" => $row['ans1'],
                "ans2" => $row['ans2'],
                "ans3" => $row['ans3'],
                "ans4" => $row['ans4'],
                "correct_answer" => $row['correct_answer'],
                "category" => $row['cate'],
                "image_path" => $row['image_path']
            ]
        ];
    } else {
        return [
            "success" => false,
            "message" => "No cached question found for this creator."
        ];
    }
}

public static function deleteQuestion($question_id){
    $db = Database::connect();
        
    $query = "UPDATE question SET status = 'deleted' WHERE question_id = ?";
    
    $stmt = $db->prepare($query);
    $stmt->bind_param("i", $question_id);
    
    if ($stmt->execute()) {
        return ['success' => true, 'message' => 'Test deleted successfully.'];
    } else {
        return ['success' => false, 'message' => 'Failed to deleted the test.'];
    }

}
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
        $types = "isii"; // q.creator, searchTerm, offset, limit
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

}
?>