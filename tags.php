<?php
require 'db.php';
require 'user_required.php';

$stmt_tag = $db->prepare("SELECT * FROM tags WHERE id=?");
$stmt_tag->execute(array($_GET['tags_id']));
$tag = $stmt_tag->fetch();
#pomocna promena


$stmt_top = $db->prepare("
select tags.id, tags.tags_name, topics.topic_id, topics.topic_name, topics.topic_description, topics.topic_date, topics.topic_by, users.username as users from tags join tag_topic on tag_topic.tag = tags.id join topics on topics.topic_id = tag_topic.topic join users on topics.topic_by = users.id_user where tags.id = ? ORDER by topics.topic_date
");
$stmt_top->execute(array($_GET['tags_id']));
$topics = $stmt_top->fetchAll();

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
            <li>Tag: <?= $tag['tags_name'] ?></li>

        </ul>
    </div>
    <div style="display: table;  margin-right: auto;  margin-left: auto;">
        <?php if(!$topics){
            echo 'Tento tag není nikde přiřazen!!';
        } ?>
    </div>
    <div class="container-fluid">
        <?php foreach ($topics as $row) { ?>
            <div class="col-md-3 well" id="div_topic">

                <h3><a href='topics_detail.php?topic_id=<?= $row['topic_id'] ?>'><?= $row['topic_name'] ?></a></h3>
                <?php
                $stmt_tags = $db->prepare("select tags_name, topics.topic_name from tags join tag_topic on tag_topic.tag = tags.id JOIN topics on topics.topic_id = tag_topic.topic where topics.topic_id = ?");
                $stmt_tags->execute(array($row['topic_id']));
                $tags = $stmt_tags->fetchAll();
                ?>
                <?php
                if ($tags){
                    foreach ($tags as $tag) { ?>
                        <button type="button" class="btn btn-info"><?= $tag['tags_name'] ?></button>
                    <?php } }
                else {
                    ?>
                    <button type="button" class="btn btn-danger"> Žádné tagy</button>

                <?php  }
                ?>
                </p>

                <p><b>Popis: </b> <?= substr($row['topic_description'], 0, 50) ?> <?= strlen($row['topic_description']) > 50 ? "..." : "" ?></p>
                <p><b>Uživatel: </b><?= $row['users'] ?></p>
                <p><b>Založeno: </b><?= $row['topic_date'] ?></p>

            </div>
        <?php } ?>

    </div>
    <?php include 'footer.php' ?>

</body>

</html>