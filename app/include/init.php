<?php

session_start();
date_default_timezone_set('Asia/Ho_Chi_Minh');

if (!isset($_SESSION['user_role'])) {
    $_SESSION['user_role'] = 'guest'; 
}

?>