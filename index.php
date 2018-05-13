<?php
require_once("helper.php");
define('ENTRY','1'); 
$login = new loginChecker();

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    if($_POST['type'] == "login"){
        if(!empty($_POST['email']) && !empty($_POST['password']) && $login->checkLoginStatus($_POST['email'],$_POST['password'])){
                require("user.php");
                exit;
        }
        else require("login.php");
    }
    if($_POST['type'] == "logout"){
        $login->removeRemember($_POST['user_id']);
        require("login.php");
    }
}
else {
        $remember = $login->checkRemember($_COOKIE['uid']);
        if($remember){
            require("user.php");
            //$user_data = $user->getUserByEmail($_COOKIE['email']);
        }
        else require("login.php");

    // $remember = $login->checkRemember($_COOKIE['uid']);
    // if($remember){
    //     require("user.php");
    // }
    // else require("login.php");
}
?>
