<?php
require 'db.php';
require 'user_required.php';


$stmt_cat = $db->prepare("SELECT * FROM categories");
$stmt_cat->execute();
$cat = $stmt_cat->fetchAll();
if (isset($_GET['cat_id'])) {
    $picked_cat = $_GET['cat_id'];
}
$stmt_tag = $db->prepare("SELECT * FROM tags");
$stmt_tag->execute();
$tags = $stmt_tag->fetchAll();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $topic_name = htmlspecialchars(trim($_POST['topic_name']));
    $description = htmlspecialchars(trim($_POST['topic_description']));
    if(($_POST['topic_cat']) == null) {
        $category = $picked_cat;
    } else {
        $category = htmlspecialchars(trim($_POST['topic_cat']));
        }
    $user = htmlspecialchars(trim($_SESSION['user_id']));
    $now = (new DateTime)->format('Y-m-d H:i:s');

    $stmt = $db->prepare("INSERT INTO topics(topic_name, topic_description, topic_date, topic_cat, topic_by) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute(array($topic_name, $description, $now, $category, $user));

    $stmt_topicId = $db->prepare("SELECT topic_id from topics where topic_date=? AND topic_description=?");
    $stmt_topicId->execute(array($now, $description));
    $topic_id = $stmt_topicId->fetch();
    $topic_id = $topic_id[0];

    foreach ($_POST['topic_tags'] as $one) {
        $stmt_tags = $db->prepare("INSERT INTO tag_topic(tag, topic) VALUES (?, ?)");
        $stmt_tags->execute(array($one, $topic_id));

    }

    header('Location: index.php');
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
<?php include 'navbar.php' ?>
<div class="loginmodal-container">
    <h1>Přidej téma</h1>

    <form action="" method="POST">
        <label for="comment">Název</label>

        <input type="text" name="topic_name" value="" required><br/><br/>
        <div class="form-group">
            <label for="comment">Popis tématu</label>
            <textarea class="form-control" rows="5" id="comment" name="topic_description"></textarea>
        </div>

        <div class="form-group">
            <label class="" for="sel1">Kategorie tématufjakdsljf</label>
                <?php if (isset($picked_cat)) { ?>
                    <select name="topic_cat" id="disabledInput" class='form-control' disabled>
                        <?php foreach ($cat as $row) {
                            if ($row['cat_id'] == $picked_cat){
                                ?>
                                <option value="<?= $row['cat_id'] ?>"><?= $row['cat_name'] ?> </option>
                            <?php }} ?>
                    </select>
                <?php } else { ?>
                    <select name="topic_cat" class='form-control' id="sel1">
                        <?php foreach ($cat as $row) { ?>
                            <option value="<?= $row['cat_id'] ?>"><?= $row['cat_name'] ?> </option>
                        <?php } ?>
                    </select>
                <?php } ?>

        </div>
        <div class="form-group">
            <label class="" for="sel1">Tagy</label>
            <br/>
            <select name='topic_tags[]' class='selectpicker' multiple>
                <?php foreach ($tags as $row) { ?>
                    <option value="<?= $row['id'] ?>"><?= $row['tags_name'] ?> </span>
                    </option>
                <?php } ?>
            </select>
        </div>

<!--<span class='label label-success'></span>-->
        <input type="submit" value="Save" class="login loginmodal-submit">
        <p><a href="index.php">Cancel</a></p>

    </form>
</div>

<?php include 'footer.php' ?>

</body>

</html>