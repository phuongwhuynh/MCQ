<?php
require_once "../app/controllers/PageController.php";
require_once "../app/controllers/UserController.php";
require_once "../app/controllers/QuestionController.php";
require_once "../app/controllers/TestController.php";
require_once "../app/controllers/ResourcesController.php";
require_once "../app/controllers/DashboardController.php";
require_once "../app/controllers/HistoryController.php";
require_once "../app/controllers/PastAttemptController.php";

$controller = [
    "page" => "PageController",
    "user" => "UserController",
    "question" => "QuestionController",
    "test" => "TestController",
    "resources" => "ResourcesController",
    "dashboard" => "DashboardController",
    "history" => "HistoryController",
    "pastattempt" => "PastAttemptController"
];

define("GOOGLE_CLIENT_ID", "214663455323-0muhc3kha1h813kg6kthjmthf6choh4m.apps.googleusercontent.com");
define("GOOGLE_CLIENT_SECRET", "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");
define("GOOGLE_REDIRECT_URI", "http://localhost/WP_CC01_04/index.php?page=login");

// $action = [
//     "pagination" => "handlePagination",
//     "addCart" => "addCart"
// ];