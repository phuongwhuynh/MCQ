<?php
require_once "../app/models/Test.php";

class TestController {

    public static function handlePagination() {
        $limit = 10;
        $page = isset($_GET['pageNum']) ? (int)$_GET['pageNum'] : 1;
        $sort = $_GET['sort'] ?? "created_time_desc";
        $categories = isset($_GET['categories']) ? array_filter(explode(",", $_GET["categories"])) : [];
        $searchTerm = $_GET['search'] ?? '';
        $creatorId = $_SESSION['user_id'];

        $questions = Test::getPaginated($page, $limit, $sort, $categories, $searchTerm, $creatorId);
        $totalQuestions = Test::countAllQuestions($categories, $searchTerm, $creatorId);

        $totalPages = ceil($totalQuestions / $limit);

        header('Content-Type: application/json');
        echo json_encode([
            'questions' => $questions,
            'totalPages' => $totalPages
        ]);
    }

    public static function submitTest($data) {
        // Define required fields for the test submission
        $requiredFields = ['total_time',  'question_ids', 'category'];
    
        // Validate if the required fields are provided
        foreach ($requiredFields as $field) {
            if (empty($data[$field])) {
                echo json_encode([
                    "success" => false,
                    "message" => ucfirst(str_replace('_', ' ', $field)) . " is required."
                ]);
                return;
            }
        }
    
        // Extract form data
        $total_time = $data['total_time'];
        $test_name = $data['test_name'];

        $category = $data['category'];
        $question_ids = $data['question_ids'];
        $question_ids = json_decode($question_ids, true);
        $creator = $_SESSION['user_id'];
    
        // Optional: Process the image if it's uploaded
        $image_tmp = null;
        $fileExt = null;
        
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $image_tmp = $_FILES['image']['tmp_name'];
            $fileExt = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        }
    
        // Call the model function to submit the test
        $result = Test::submitTest(
            $total_time, $test_name, $creator, $image_tmp, $fileExt, $question_ids, $category
        );
    
        // Return the result as JSON response
        echo json_encode($result);
    }
    public static function fetchTest() {
        $page = isset($_GET['pageNum']) ? (int)$_GET['pageNum'] : 1;
        $limit = 10;
        $sort = $_GET['sort'] ?? "created_time_desc";
        $categories = isset($_GET['categories']) ? array_filter(explode(",", $_GET["categories"])) : [];
        $searchTerm = $_GET['search'] ?? '';
        $creatorId = $_SESSION['user_id'];
    
        // Fetch paginated list of tests for this creator
        $tests = Test::getTest($page, $limit, $creatorId, $sort, $categories, $searchTerm);
    
        if (!empty($tests)) {
            // Count total tests for this creator
            $totalTests = Test::countTotalTests($creatorId, $categories, $searchTerm);
    
            // Calculate total pages
            $totalPages = ceil($totalTests / $limit);
    
            echo json_encode([
                'success' => true,
                'tests' => $tests,
                'totalPages' => $totalPages,
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'No tests found.',
            ]);
        }
    }
    public static function publish($data){
        $test_id=$data['test_id'];
        $response=Test::publish($test_id);
        echo json_encode($response);
    }
    public static function privatize($data){
        $test_id=$data['test_id'];
        $response=Test::privatize($test_id);
        echo json_encode($response);
    }
    public static function delete($data){
        $test_id=$data['test_id'];
        $response=Test::delete($test_id);
        echo json_encode($response);
    }
    public static function getQuestions($data) {
        $test_id=$data['test_id'];
        $response=Test::getQuestionsOfTest($test_id);
        echo json_encode($response);
    }
    public static function getPreviewQuestions($data) {
        $test_id=$data['test_id'];
        $response=Test::getPreviewQuestionsOfTest($test_id);
        echo json_encode($response);
    }
    public static function createAttempt($data) {
        if ($_SESSION['user_role']!=='user'){
            $response= ["status"=>"Unauthorized"];
        }
        else {
            $test_id=$data["test_id"];
            $user_id=$_SESSION["user_id"];
            $attempt_id=Test::startTestAttempt($test_id, $user_id);
            $response= ["status"=>"success", "attempt_id"=>$attempt_id];
        }
        echo json_encode($response);
    }
    public static function getAttempt($data) {
        $attempt_id = $data['attempt_id'];
        $response = Test::getAttempt($attempt_id);
    
        if ($response) {
            echo json_encode(array_merge(["success" => true], $response));
        } else {
            echo json_encode(["success" => false]);
        }
    }
    public static function submitAnswer($data) {
        $attempt_id= $data['attempt_id'];
        $chosen_answer = $data['chosen_answer'];
        $save_result = Test::saveAnswer($attempt_id, $chosen_answer);
        if ($save_result['status'] !== 'success') {
            echo json_encode([
                "success" => false,
                "message" => $save_result['message'] ?? "Failed to save answer."
            ]);
            return;
        }
    
        // Step 2: Move to the next question and get the updated attempt info
        $next_question_data = Test::nextQuestion($attempt_id);
    
        if (!$next_question_data) {
            echo json_encode([
                "success" => false,
                "message" => "Failed to fetch next question."
            ]);
            return;
        }
    
        // Step 3: Return response with updated attempt info
        echo json_encode(array_merge([ "success" => true], $next_question_data));
    }
    




}
    


    // public static function cacheTest($data) {
    //     $creator = $_SESSION['user_id'];

    //     $totalTime = isset($data['total_time']) ? (int)$data['total_time'] : 0;
    //     $questionIds = isset($data['question_ids']) ? $data['question_ids'] : [];
    //     $imageTmp = $_FILES['image']['tmp_name'] ?? null;
    //     $fileExt = isset($_FILES['image']['name']) ? pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION) : null;

    //     $result = Test::cacheTest($creator, $totalTime, $questionIds, $imageTmp, $fileExt);

    //     header('Content-Type: application/json');
    //     echo json_encode($result);
    // }

    // public static function getCachedTest() {
    //     $creator = $_SESSION['user_id'];
    //     $result = Test::getCachedTest($creator);

    //     header('Content-Type: application/json');
    //     echo json_encode($result);
    // }

?>