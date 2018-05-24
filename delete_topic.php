<?php
# pripojeni do db
require 'db.php';
require 'user_required.php';
if($_SESSION["topic_by"] = $_SESSION["user_id"]) {
    $del = $db->prepare("DELETE FROM topics WHERE topic_id = ?");
    $del->execute(array($_GET['topic_id']));
    unset($_SESSION["topic_by"]);
    header("Location: index.php");
}else{
    echo "Nastala chyba s mazáním";
}
?>

<!DOCTYPE html>

<html>

<head>
    <meta charset="utf-8" />
    <title>PHP Shopping App</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>

<body>

</body>

</html>