<html>
<head>
<title>DI TEST API</title>
<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<div>
    <form action="index.php" method="post">
        <label for="email">Email:</label>
        <input type="text" id="email" name="email" placeholder="Your email" value="<?=$_POST['email']?>">
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" placeholder="Your password" value="<?=$_POST['password']?>">
        <input type="submit" value="Login">
        <input type="hidden" name="type" value="login">
    </form>
</div>
</body>
</html>