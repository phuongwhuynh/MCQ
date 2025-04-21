<?php
require_once "../app/models/Question.php";
class QuestionController {
    public static function submitQuestion($data) {
        $requiredFields = ['description', 'ans1', 'ans2', 'ans3', 'ans4', 'correctAnswer', 'category'];
        foreach ($requiredFields as $field) {
            if (empty($data[$field])) {
                echo json_encode([
                    "success" => false,
                    "message" => ucfirst($field) . " is required."
                ]);
                return;
            }
        }
    
        $description = $data['description'];
        $ans1 = $data['ans1'];
        $ans2 = $data['ans2'];
        $ans3 = $data['ans3'];
        $ans4 = $data['ans4'];
        $correct = $data['correctAnswer'];
        $category = $data['category'];
        $creator = $_SESSION['user_id'];
        $existingImagePath = $data['existingImagePath'] ?? null;

        $image_tmp = null;
        $fileExt = null;
    
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $image_tmp = $_FILES['image']['tmp_name'];
            $fileExt = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        }
    
        $result = Question::submitQuestion(
            $description, $ans1, $ans2, $ans3, $ans4,
            $correct, $category, $image_tmp, $fileExt, $creator, $existingImagePath
        );
    
        echo json_encode($result);
    }
    public static function cacheQuestion($data) {
        $description = isset($data['description']) ? $data['description'] : null;
        $ans1 = isset($data['ans1']) ? $data['ans1'] : null;
        $ans2 = isset($data['ans2']) ? $data['ans2'] : null;
        $ans3 = isset($data['ans3']) ? $data['ans3'] : null;
        $ans4 = isset($data['ans4']) ? $data['ans4'] : null;
        $correct_answer = isset($data['correctAnswer']) ? $data['correctAnswer'] : null;
        $category = !empty($data['category']) ? $data['category'] : null;
        $creator = $_SESSION['user_id'];

        $image_tmp = null;
        $fileExt = null;

        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $image_tmp = $_FILES['image']['tmp_name'];
            $fileExt = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        }

        $result = Question::cacheAdminCurrentQuestion(
            $description, $ans1, $ans2, $ans3, $ans4,
            $correct_answer, $category, $image_tmp, $fileExt, $creator
        );

        echo json_encode($result);
    }

    public static function getCacheQuestion(){
        $response=Question::getCachedAdminCurrentQuestion($_SESSION['user_id']);
        echo json_encode($response);
    }
    public static function deleteQuestion($data){
        $question_id=$data['question_id'];
        $response=Question::deleteQuestion($question_id);
        echo json_encode($response);
    }
    public static function handlePagination() {
        $limit = 10;
        $page = isset($_GET['pageNum']) ? (int)$_GET['pageNum'] : 1;
        $sort = $_GET['sort'] ?? "created_time_desc";
        $categories = isset($_GET['categories']) ? array_filter(explode(",", $_GET["categories"])) : [];
        $searchTerm = $_GET['search'] ?? '';
        $creatorId = $_SESSION['user_id'];
    
        $questions = Question::getPaginated($page, $limit, $sort, $categories, $searchTerm, $creatorId);
        $totalQuestions = Question::countAllQuestions($categories, $searchTerm, $creatorId);
    
        $totalPages = ceil($totalQuestions / $limit);
    
        header('Content-Type: application/json');
        echo json_encode([
            'questions' => $questions,
            'totalPages' => $totalPages
        ]);
    }
    
}
?>