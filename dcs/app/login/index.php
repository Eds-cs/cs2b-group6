<?php
session_start();
require '../../classes/database.class.php';
require '../../classes/account.class.php';
include_once '../../classes/utilities.php';
include_once '../../classes/app.class.php';

$db = Database::getInstance();
$accObj = new Account();

$application = Application::getInstance();

$message = "";

if ($application->getAccountId()) {

    header('Location: ../');
    exit();
}

if (isset($_POST['login'])) {
    $email = clean_input($_POST['email']);
    $password = clean_input($_POST['password']);

    $loginResult = $accObj->login($email, $password);
    if ($loginResult) {
        header(header: 'Location: ../');
        exit();
    } else {


        $message = 'Invalid email or password. ';

        
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet"
          href="../../css/login.css">
    <title>Login</title>
</head>

<body>
    <?php $_SESSION ?>
    <div class="box">
        <div class="pic"><img src="../../photos/CCS LOGO.png"
                 alt=""
                 srcset=""></div>

        <p class="pl">LOG IN</p>
        <p class="details">Please Enter your Account Details</p>


        <?php if ($message) {
            echo "<p style='color:red;'>$message</p>";
        } ?>
        <?php if (isset($_SESSION['success'])) {
            echo "<p style='color:green;'>{$_SESSION['success']}</p>";
            unset($_SESSION['success']);
        } ?>

        <div class="form">
            <form method="post"
                  class="form">
                <label for="email">Email Address:<span class="error">*</span></label>
                <input type="email"
                       name="email"
                       id="email"
                       size="40"
                       placeholder="   Enter your Email"
                       required><br>

                <label for="password">Password:<span class="error">*</span></label>
                <input type="password"
                       name="password"
                       id="password"
                       size="40"
                       placeholder="   Enter your Password"
                       required><br>
                <div class="buttonbox">
                    <button class="subbox"
                            type="submit"
                            name="login">Login</button>
                </div>
            </form>
        </div>

    </div>
</body>

</html>