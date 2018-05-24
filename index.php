<?php

require 'db.php';
require 'user_required.php';

$stmt = $db->prepare("select cat_id, cat_name, cat_description, count(topics.topic_id) as cat_badge from categories left join topics on topics.topic_cat = cat_id GROUP BY cat_id, cat_name, cat_description");
$stmt->execute();
$categories = $stmt->fetchAll();

$stmt_tags = $db->prepare("select * from tags");
$stmt_tags->execute();
$tags = $stmt_tags->fetchAll();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $parametr = "?cat_id=" . $_POST['topic_tags'];
    header('Location: index.php' . $parametr);
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
<?php include 'navbar.php' ?>
<div class="container">
    <div class="title text-center">
        <div class="centered">
            <h1>Fórum</h1>
            <p>Zde najdeš odpověď na cokoliv...</p>
        </div>
    </div>
</div>

<div class="container">
    <h3>Kategorie</h3>
    <?php foreach ($categories as $row) { ?>
        <div class="col-md-4" id="font">
            <div class="well divCategory">
                <h3><a href='topics.php?cat_id=<?= $row['cat_id'] ?>'><?= $row['cat_name'] ?> <span
                                class="badge"><?= $row['cat_badge'] ?></span></a></h3>
                <p><?= substr($row['cat_description'], 0, 150) ?> <?= strlen($row['cat_description']) > 150 ? "..." : "" ?></p>
            </div>
        </div>
    <?php } ?>

</div>
<div class="container">
    <hr>
    <h3>Tagy</h3>

    <?php foreach ($tags as $row) { ?>
            <a href='tags.php?tags_id=<?= $row['id'] ?>'><label style="background-color: #7BBBE3" class="btn btn-info margintag"  value="<?= $row['id'] ?>"><?= $row['tags_name'] ?></label>
            </a>


        <!--        <button type="button" name="tag_button" class="btn btn-info" value="--><? //= $row['id'] ?><!--">--><? //= $row['tags_name'] ?><!--</button>-->
        <!--               <a href='topics.php?cat_id=--><? //= $row['cat_id'] ?><!--'>--><? //= $row['cat_name'] ?><!-- <span-->
        <!--                                class="badge">--><? //= $row['cat_badge'] ?><!--</span></a></h3>-->
        <!--                <p>--><? //= substr($row['cat_description'], 0, 150) ?><!-- --><? //= strlen($row['cat_description']) > 150 ? "..." : "" ?><!--</p>-->
    <?php } ?>
</div>

<?php include 'footer.php' ?>

</body>
</html>
