<?php
session_start();
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = htmlspecialchars(trim($_POST['username']));
    $password = htmlspecialchars(trim($_POST['password']));
    $password_check = htmlspecialchars(trim($_POST['password_check']));


    # dalsi moznosti je vynutit bcrypt: PASSWORD_BCRYPT
    $hashed = password_hash($password, PASSWORD_DEFAULT);
    #overim bullshity ze vstupu
    $errors = array(); /* pole na bullshity */

    if (isset($_POST['username'])) {
        if (!ctype_alnum($_POST['username'])) {
            $errors[] = 'Přezdivka může obsahovat jen písmena a číslice.';
        }
        if (strlen($_POST['username']) > 30) {
            $errors[] = 'Přezdivka nemůže být delší než 30 znaků. Kdo to má vykreslovat na webu';
        }
    } else {
        $errors[] = 'Přezdívka nesmí být prázdná.';
    }

    if (isset($_POST['password'])) {
        if ($_POST['password'] != $_POST['password_check']) {
            $errors[] = 'Hesla se neshodují';
        }
        if (strlen($_POST['password_check']) < 8) {
            $errors[] = 'heslo je moc krátké';

        }
        if (empty($_POST['password'])) {
            $errors[] = 'Heslo nesmí být prázdné';
        }
    }

    if (!$errors) { /*check for an empty array, if there are errors, they're in this array (note the ! operator)*/
        /*echo "<br/>";
        echo 'Něco se pokazilo: ';
        echo $username;
        echo $password;
        echo $password_check;
        echo '<ul>';
        foreach ($errors as $key => $value)  {
            echo '<li>' . $value . '</li>';
        }
        echo '</ul>';

        echo 'zkus se to znova <a href="signup.php">přihlásit se</a>';
        die();*/

        #vlozime usera do databaze
        $stmt = $db->prepare("INSERT INTO users(username, password) VALUES (?, ?)");
        $stmt->execute(array($username, $hashed));

        #ted je uzivatel ulozen, bud muzeme vzit id posledniho zaznamu pres last insert id (co kdyz se to potka s vice requesty = nebezpecne), nebo nacist uzivatele podle mailove adresy (ok, bezpecne)

        $stmt = $db->prepare("SELECT id_user FROM users WHERE username = ? LIMIT 1"); //limit 1 jen jako vykonnostni optimalizace, 2 stejne maily se v db nepotkaji
        $stmt->execute(array($username));
        $user_id = (int)$stmt->fetchColumn();

        $_SESSION['user_id'] = $user_id;

        header('Location: index.php');
    }
}
?>

<!DOCTYPE html>

<html>

<head>
    <meta charset="utf-8"/>
    <title>PHP Forum</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <?php include 'head_bootstrap.php' ?>
</head>

<body>


<div class="loginmodal-container">
    <h1>PHP Forum</h1>
    <p>Pro čtení a přidávání obsahu se musíte registrovat</p>
    <h2>Registrace</h2>
    <?php
    if (isset($errors)) {

        echo 'Něco se pojebalo. <br>';

        foreach ($errors as $error) {
            echo '<div class="alert alert-danger">' . $error . '</div>';
        }
    }
    ?>
    <form action="" method="POST">
        <input type="text" name="username" value="" placeholder="Vaše přezdívka" required>
        <input type="password" name="password" value="" placeholder="Vaše heslo" required>
        <input type="password" name="password_check" value="" placeholder=" Potvrďte heslo" required><br/>
        <input type="submit" name="login" class="login loginmodal-submit" value="Registrovat">
        <!--    <input type="submit" value="Sign in">-->

    </form>

    <div class="login-h">

        <a href="index.php">Zrušit registraci</a>
    </div>
</div>

</body>

</html>