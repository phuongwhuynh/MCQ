<?php
require_once "Database.php";
class Question {
// Function to submit a question
public static function submitQuestion($description, $ans1, $ans2, $ans3, $ans4, $correct_answer, $category, $image_tmp = null, $fileExt = null, $creator) {
    $conn = Database::connect();

    // Sanitize input data
    $description = $conn->real_escape_string($description);
    $ans1 = $conn->real_escape_string($ans1);
    $ans2 = $conn->real_escape_string($ans2);
    $ans3 = $conn->real_escape_string($ans3);
    $ans4 = $conn->real_escape_string($ans4);
    $correct_answer = (int)$correct_answer;
    $category = $conn->real_escape_string($category);
    $creator = (int)$creator;

    $conn->begin_transaction();

    // Use prepared statement for insertion
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

    // Step 2: Handle image upload if provided
    if ($image_tmp && $fileExt) {
        $fileExt = strtolower($fileExt);
        $uploadPath = "images/questions/" . $questionId . "." . $fileExt;

        $safePath = $conn->real_escape_string($uploadPath);
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
    }

    $conn->commit();
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

    if ($image_tmp && $fileExt) {
        $fileExt = strtolower($fileExt);
        $uploadPath = "images/current_questions/" . $creator . "." . $fileExt;
    

    
        $safePath = $conn->real_escape_string($uploadPath);
        $updateQuery = "UPDATE admin_cur_question SET image_path = ? WHERE creator = ?";
        $updateStmt = $conn->prepare($updateQuery);
        $updateStmt->bind_param("si", $safePath, $creator);
    
        if (!$updateStmt->execute()) {
            $conn->rollback();
            return [
                "success" => false,
                "message" => "Error saving image path for cached question: " . $updateStmt->error
            ];
        }
    
        if (!copy($image_tmp, $uploadPath)) {
            $conn->rollback();
            return [
                "success" => false,
                "message" => "Temporary question created but could not copy the image. Changes have been discarded."
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

        
}
?>