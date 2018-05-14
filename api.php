<?php
require_once("helper.php");
$users = new users();
if(isset($_SERVER['HTTP_USER'])){
    $user_credentials=explode(":",$_SERVER['HTTP_USER']);
    $user_data = $users->getUserByEmail($user_credentials[0]);
    $decrypted_token = $users->decrypt($user_credentials[1],$user_data['salt']);
    if($decrypted_token == $user_data['access_token']){
        $allow_access_api = true;
    }
}

if($allow_access_api==true){

    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        header('Content-Type: application/json');
        if($_GET['q1']=='api'){
            #echo "This is API call<br>";
            $param_array = explode("/",$_GET['q2']);
            if($param_array[1]=="users" && !isset($param_array[2])){
                #echo "This is get all user's API call<br>";
                $user_list = $users->getUserList();
                echo json_encode($user_list);
            }
            if($param_array[1]=="users" && isset($param_array[2]) && is_numeric($param_array[2])){
                $user = $users->getUserById($param_array[2]);
                echo json_encode($user);
            }
        }
        else echo "Error!";
    
    }
    if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
        if($_REQUEST['q1']=='api'){
            $param_array = explode("/",$_REQUEST['q2']);
            if($param_array[1]=="users" && isset($param_array[2]) && is_numeric($param_array[2])){
                $dataset=json_decode(file_get_contents("php://input"),true);
                if(sizeof($dataset)!=3){
                    echo "Your data's structure is wrong format, ";
                    echo 'The right format is: {"name":"Christiano Ronaldo","address":"Real Marid FC, Spain","telephone":"0107070707"}';
                    exit;
                }
                $users->updateUserById($param_array[2],$dataset);
            }else echo "This is not valid endpoint";
        }
        else echo "Error!";
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    //Todo
    }

    if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
        //Todo
    }
}
else echo "You are not authorized to access this api";
?>