<?php
require_once "Database.php";
class Resources {
    public static function getTest($page, $limit, $sort = 'total_attempts_desc', $categories = [], $searchTerm = '') {
        $db = Database::connect();
        $offset = ($page - 1) * $limit;
    
        // Default sorting
        $orderBy= "t.total_attempts DESC";
    
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
        } elseif ($sort === "title_asc") {
            $orderBy = "t.test_name AS";
        } elseif ($sort === "title_desc") {
            $orderBy = "t.test_name DESC";
        } 
    
        $query = "
            SELECT 
                t.test_id,
                t.image_path AS test_image,
                t.test_name AS test_name
            FROM test t
            WHERE t.status != 'deleted'
            $inCate
            $searchCondition
            ORDER BY $orderBy
            LIMIT ?, ?
        ";
    
        // Prepare parameters
        $stmt = $db->prepare($query);
        $types =  (count($searchParams) ? "s" : "") . "ii";
        $params = array_merge($searchParams, [$offset, $limit]);
    
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $result = $stmt->get_result();
    
        $tests = [];
        while ($row = $result->fetch_assoc()) {
            $tests[] = [
                'test_id' => $row['test_id'],
                'image_path' => $row['test_image'],
                'test_name' => $row['test_name'],
            ];
        }
    
        return $tests;
    }
    public static function countTotalTests($categories = [], $searchTerm = '') {
        $db = Database::connect();
    
        $conditions = ["t.status != 'deleted'"];
        $params = [];
        $types = "";
    
        if (!empty($categories)) {
            $placeholders = implode(',', array_fill(0, count($categories), '?'));
            $conditions[] = "t.cate IN ($placeholders)";
            $params = array_merge($params, $categories);
            $types .= str_repeat("s", count($categories));  // assuming cate is VARCHAR
        }
    
        // Search term filtering
        if (!empty($searchTerm)) {
            $conditions[] = "t.test_name LIKE ?";
            $params[] = "%$searchTerm%";
            $types .= "s";
        }
    
        $whereClause = implode(" AND ", $conditions);
        $query = "SELECT COUNT(*) as total_tests FROM test t WHERE $whereClause";
    
        $stmt = $db->prepare($query);
    
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }
    
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
    
        return $row['total_tests'];
    }
    



        
}
?>