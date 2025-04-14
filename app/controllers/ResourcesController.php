<?php
require_once "../app/models/Resources.php";
class ResourcesController {
    public static function fetchTest() {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 12;
        $sort = $_GET['sort'] ?? "total_attempts_desc";
        $categories = isset($_GET['categories']) ? array_filter(explode(",", $_GET["categories"])) : [];
        $searchTerm = $_GET['search'] ?? '';
    
        // Fetch paginated list of tests for this creator
        $tests = Resources::getTest($page, $limit,  $sort, $categories, $searchTerm);
    

        $totalTests = Resources::countTotalTests( $categories, $searchTerm);

        $totalPages = ceil($totalTests / $limit);

        echo json_encode([
            'success' => true,
            'tests' => $tests,
            'totalPages' => $totalPages,
        ]);
        
    }

}
?>