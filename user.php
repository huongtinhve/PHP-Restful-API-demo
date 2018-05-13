<?php
if (!defined('ENTRY')) { die('Access denied'); }
$user = new users();
if(!isset($_POST['email'])){
    $email = $_COOKIE['ue'];
}
else $email = $_POST['email'];

$user_data = $user->getUserByEmail($email);
$login->setRemember($user_data['id'],$user_data['email']);
?>
<link rel="stylesheet" type="text/css" href="style.css">
<div>
    <form action="index.php" method="post">
    <label>
        Hi ! <b><?=$user_data['name']?>,</b></br>
    </label>
    <label>This is your information:</br></br></label>
        <?php
        echo "Your id: <b>".$user_data['id']."</b></br>";
        echo "Your email: <b>".$user_data['email']."</b></br>";
        echo "Your telephone: <b>".$user_data['telephone']."</b></br>";
        echo "Your address: <b>".$user_data['address']."</b></br>";
        echo "API Key: <b>".$user->encrypt($user_data['access_token'],$user_data['salt'])."</b></br></br>";
        ?>
        <input type="submit" style="background-color: grey;"  value="Logout">
        <input type="hidden" name="type" value="logout">
        <input type="hidden" name="user_id" value="<?=$user_data['id']?>">
        <input type="hidden" name="email" value="<?=$user_data['email']?>">
    </form>
</div>



