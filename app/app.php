<?php
require_once "../app/include/config.php";

function getJsonInput() {
    $json = file_get_contents('php://input');
    return json_decode($json, true); 
}

if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET['ajax']) && $_GET['ajax'] == 1) {
    $contr = $_GET['controller'] ?? null;
    $action = $_GET['action'] ?? null;

    if ($contr && $action) {
        $controller[$contr]::$action();
    } else {
        echo json_encode(["success" => false, "message" => "Invalid request"]);
    }
} else if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
    
    $data = [];
    if (strpos($contentType, 'application/json') !== false) {
        $data = getJsonInput(); 
    } else {
        $data = $_POST; 
    }

    if (isset($data['ajax']) && $data['ajax'] == 1) {
        if (isset($data['controller']) && isset($data['action'])) {
            $controllerName = $data['controller'];
            $actionName = $data['action'];

            if (method_exists($controller[$controllerName], $actionName)) {
                $controller[$controllerName]::$actionName($data);  
            } else {
                echo json_encode(["success" => false, "message" => "Method not found"]);
            }
        } else {
            echo json_encode(["success" => false, "message" => "Controller or Action not provided in the request"]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "'ajax' parameter not provided or not set to 1"]);
    }
}

else {
    
    if ($_SESSION['user_role']=='admin'){
        $page = isset($_GET['page']) ? $_GET['page'] : 'menuAdmin';
        if ($page=='home') $page='menuAdmin';
    }
    else {
        $page = isset($_GET['page']) ? $_GET['page'] : 'home';
    }
    PageController::loadPage($page);
}

?>