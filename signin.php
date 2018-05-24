<?php
session_start();
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = htmlspecialchars(trim($_POST['username']));
    $password = htmlspecialchars(trim($_POST['password']));

    # zajimavost: mysql porovnani retezcu je case insensitive, pokud dame select na NECO@DOMENA.COM, najde to i zaznam neco@domena.com
    # viz http://dev.mysql.com/doc/refman/5.0/en/case-sensitivity.html

    $stmt = $db->prepare("SELECT * FROM users WHERE username = ? LIMIT 1"); //limit 1 jen jako vykonnostni optimalizace, 2 stejne maily se v db nepotkaji
    $stmt->execute(array($email));
    $existing_user = @$stmt->fetchAll()[0];

    if (password_verify($password, $existing_user["password"])) {

        $_SESSION['user_id'] = $existing_user["id_user"];

        header('Location: index.php');

    } else {

        die("Invalid user or password!");

    }

}
?>

<!DOCTYPE html>

<html>

<head>
    <meta charset="utf-8"/>
    <title>PHP Forum</title>
    <?php include 'head_bootstrap.php' ?>
</head>

<body>

    <div class="loginmodal-container">
        <h1>PHP Forum</h1>
        <p>Pro čtení a přidávání obsahu se musíte přihlásit</p>
        <h2>Přihlášení</h2>

        <form action="" method="POST">
            <input type="text" name="username" value="" placeholder="Vaše přezdívka" required>
            <input type="password" name="password" value="" placeholder="Vaše heslo" required>
            <input type="submit" name="login" class="login loginmodal-submit" value="Přihlásit se">
            <!--    <input type="submit" value="Sign in">-->

        </form>

        <div class="login-h">

            <a href="signup.php">Pokud nemáte účet - registrujte se</a>
        </div>
    </div>
    <?php include 'footer.php' ?>

</body>

</html>