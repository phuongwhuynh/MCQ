<?php
require_once "../app/controllers/PageController.php";
require_once "../app/controllers/UserController.php";
require_once "../app/controllers/QuestionController.php";
require_once "../app/controllers/TestController.php";
require_once "../app/controllers/ResourcesController.php";
require_once "../app/controllers/DashboardController.php";
require_once "../app/controllers/HistoryController.php";
require_once "../app/controllers/PastAttemptController.php";

$controller= [
    "page" => "PageController",
    "user" => "UserController",
    "question" => "QuestionController",
    "test" => "TestController",
    "resources" => "ResourcesController",
    "dashboard" => "DashboardController",
    "history" => "HistoryController",
    "pastattempt" => "PastAttemptController"
];

// $action = [
//     "pagination" => "handlePagination",
//     "addCart" => "addCart"
// ];

?>