<!DOCTYPE HTML>

<!-- Robert Salchegger, 17.01.19
      index.php -->

<html>
<head>
    <meta charset="UTF-8">
    <title>Rezepte</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="./css/rezept.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="./css/skeleton.css">
    <link rel="stylesheet" type="text/css" href="./css/styles.css">
</head>
<body>
<nav>
    <?php
    include 'nav.html';
    ?>
</nav>
<main>
    <br>
<?php

include './function/DBUsage.php';

$con = new DBUsage('localhost', 'root', '', 'rezept');
//$con = DBConnection('localhost', 'root', '', 'rezept');
if(!isset($_GET['seite']))
{
    include 'src/welcome.php';
} else
{

switch($_GET['seite'])
{
    case 'rezept':
        include 'src/rezept.php'; break;
    case 'suche':
        include'src/suche.php'; break;
    default:
        include 'src/welcome.php';
}
}
?>
</main>
</body>
</html>
