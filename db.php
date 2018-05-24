<?php
//pripojeni do db na serveru eso.vse.cz
$db = new PDO('mysql:host=127.0.0.1;dbname=balt03;charset=utf8', 'balt03', 'RQ3qaP6l7ElsA3Gn9f');

//vyhazuje vyjimky v pripade neplatneho SQL vyrazu
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION)

?>