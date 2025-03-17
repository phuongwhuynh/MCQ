<?php

class PageController {


    public static function loadPage($page) {
        if ($_SESSION['user_role']==='student'){

        }
        else if ($_SESSION['user_role']==='teacher'){

        }
        else {
            self::loadGuestView($page);
        }
    }

    private static function loadGuestView($page) {
        $allowedPages = ["home"/*, "order", "activity", "history"*/];
        if (!in_array($page, $allowedPages)) {
            require_once "../app/views/404.php";
        }
        else {
            $content =  "../app/views/guest/$page.php";
            require_once "../app/views/guest/layout.php";
        }
    }

    private static function loadUserView($page){
        /*to do*/
    }
    private static function loadAdminView($page){
        /*to do*/

    }
}

?>
