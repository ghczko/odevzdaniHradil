
<nav class="navbar navbar-default">
    <div class="container">
        <div class="navbar-header">
            <a class="navbar-brand" href="#">PHP Forum</a>
        </div>
        <ul class="nav navbar-nav">
            <li><a href="index.php">Domů</a></li>
            <li><a href="new_topic.php">Nové téma</a></li>

        </ul>
        <ul class="nav navbar-nav navbar-right">
            <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#">Přihlášen jako <?= $current_user['username'] ?> <span class="caret"></span></a>
                <ul class="dropdown-menu">
                    <li><a href="signout.php">Odhlásit</a></li>
                </ul>
            </li>
        </ul>
    </div>
</nav>