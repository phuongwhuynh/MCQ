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
        $allowedPages = ["home","login","register","forgot","about","resources","contact","preview"];
        if (!in_array($page, $allowedPages)) {
            require_once "../app/views/404.php";
        }
        else {
            $content =  "../app/views/user/$page.php";
            require_once "../app/views/user/layout.php";
        }
    }

    private static function loadUserView($page){
        $allowedPages = ["home","about","resources","contact","history","dashboard", "past_attempt","preview","attempt","result"];
        if (!in_array($page, $allowedPages)) {
            require_once "../app/views/404.php";
        }
        else {
            $content =  "../app/views/user/$page.php";
            require_once "../app/views/user/layout.php";
        }    
    }

    private static function loadAdminView($page){
        $allowedPages = ["home","question","test","manage"];
        if (!in_array($page, $allowedPages)) {
            require_once "../app/views/404.php";
        }
        else {
            $page .= '_admin';
            $content =  "../app/views/admin/$page.php";
            require_once "../app/views/admin/layout_admin.php";
        }  
    }
}

?>
