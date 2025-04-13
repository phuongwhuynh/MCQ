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

    public static function cacheTest($data) {
        $creator = $_SESSION['user_id'];

        $totalTime = isset($data['total_time']) ? (int)$data['total_time'] : 0;
        $questionIds = isset($data['question_ids']) ? $data['question_ids'] : [];
        $imageTmp = $_FILES['image']['tmp_name'] ?? null;
        $fileExt = isset($_FILES['image']['name']) ? pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION) : null;

        $result = Test::cacheTest($creator, $totalTime, $questionIds, $imageTmp, $fileExt);

        header('Content-Type: application/json');
        echo json_encode($result);
    }

    public static function getCachedTest() {
        $creator = $_SESSION['user_id'];
        $result = Test::getCachedTest($creator);

        header('Content-Type: application/json');
        echo json_encode($result);
    }
}
?>