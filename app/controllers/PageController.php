<?php

class PageController {


    public static function loadPage($page) {
        if ($_SESSION['user_role']==='user'){
            self::loadUserView($page);
        }
        else if ($_SESSION['user_role']==='admin'){
            self::loadAdminView($page);
        }
        else {
            self::loadGuestView($page);
        }
    }

    private static function loadGuestView($page) {
        $allowedPages = ["home","login","register","forgot","about","resources","contact"];
        if (!in_array($page, $allowedPages)) {
            require_once "../app/views/404.php";
        }
        else {
            $content =  "../app/views/user/$page.php";
            require_once "../app/views/user/layout.php";
        }
    }

    private static function loadUserView($page){
        $allowedPages = ["home","about","resources","contact","history","dashboard", "past_attempt"];
        if (!in_array($page, $allowedPages)) {
            require_once "../app/views/404.php";
        }
        else {
            $content =  "../app/views/user/$page.php";
            require_once "../app/views/user/layout.php";
        }    
    }
    private static function loadAdminView($page){
        /*to do*/

    }
}

?>
