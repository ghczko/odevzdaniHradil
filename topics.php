<?php
require 'db.php';
require 'user_required.php';

$stmt_cat = $db->prepare("SELECT * FROM categories WHERE cat_id=?");
$stmt_cat->execute(array($_GET['cat_id']));
$cat = $stmt_cat->fetch();
#pomocna promena

$stmt_top = $db->prepare("select topic_name, topic_id, topic_by, topic_description, topic_date, users.username as users from topics join users on users.id_user = topics.topic_by where topic_cat = ? order by topic_date DESC");
$stmt_top->execute(array($_GET['cat_id']));
$topics = $stmt_top->fetchAll();

//$stmt_tags = $db->prepare("select topic_name, topic_id, topic_by, topic_description, topic_date, users.username as users from topics join users on users.id_user = topics.topic_by where topic_cat = ?");
//$stmt_tags->execute(array($_GET['cat_id']));
//$topics = $stmt_tags->fetchAll();

if (!$cat && !$topics && !isset($_GET['cat_id'])) {

    die("Unable to find category!");
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
    <div class="container">
        <ul id="menu">
            <li>Kategorie <?= $cat['cat_name'] ?></li>
            <li style="margin: auto" class="right"><a href="new_topic.php?cat_id=<?= $cat['cat_id'] ?>"><b>&#x0002B;</b></a></li>
        </ul>

    <div style="display: table;  margin-right: auto;  margin-left: auto;">
        <?php if(!$topics){
            echo 'V této kategorii nejsou žádná témata. Tlačítkem plus předejte!';
        } ?>
    </div>
        <?php foreach ($topics as $row) { ?>
            <div class="col-md-3" id="font">
                <div class="well divTopic">
                <h3><a href='topics_detail.php?topic_id=<?= $row['topic_id'] ?>'><?= substr($row['topic_name'], 0, 18) ?> <?= strlen($row['topic_name']) > 18 ? "..." : "" ?></a></h3>
                <?php
                $stmt_tags = $db->prepare("select tags_name, topics.topic_name from tags join tag_topic on tag_topic.tag = tags.id JOIN topics on topics.topic_id = tag_topic.topic where topics.topic_id = ?");
                $stmt_tags->execute(array($row['topic_id']));
                $tags = $stmt_tags->fetchAll();
                ?>
                 <?php
                        if ($tags){
                        foreach ($tags as $tag) { ?>
                     <span class="label labeltag btn-info"  style="padding: 5px"><?= $tag['tags_name'] ?></span>
                    <?php } }
                        else {
                            ?>
                            <span class="label btn-danger" style="padding: 5px"> Žádné tagy</span>
                <?php  }
                    ?>
                <p><b>Popis: </b> <?= substr($row['topic_description'], 0, 25) ?> <?= strlen($row['topic_description']) > 25 ? "..." : "" ?></p>
                <p><b>Uživatel: </b><?= $row['users'] ?></p>
                <p><b>Založeno: </b><?= $row['topic_date'] ?></p>
                </div>
            </div>
        <?php } ?>

    </div>
</div>
<?php include 'footer.php' ?>
</body>

</html>