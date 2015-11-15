<!DOCTYPE html>
<?php 
$scriptName = $_SERVER['SCRIPT_NAME'];
$class = ' class="active"';
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>CRUD</title>
        <link rel="stylesheet" href="css/bootstrap.css">
        <link rel="stylesheet" href="css/site.css">
    </head>
    <body>
        <div class="wrap">
            <nav class="navbar-inverse navbar-fixed-top navbar">
                <div class="container">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand" href="/index.php">Тестовая задача</a></div>
                    <div id="w0-collapse" class="collapse navbar-collapse">
                        <ul id="w1" class="navbar-nav navbar-right nav">
                            <li<?= !strncmp($scriptName, '/index', 6) ? $class : ''?>><a href="/index.php">Главная</a></li>
                            <li<?= !strncmp($scriptName, '/produ', 6) ? $class : ''?>><a href="/products.php">Продукты</a></li>
                            <li<?= !strncmp($scriptName, '/brand', 6) ? $class : ''?>><a href="/brands.php">Бренды</a></li>
                            <li<?= !strncmp($scriptName, '/count', 6) ? $class : ''?>><a href="/countries.php">Страны</a></li>
                        </ul>
                    </div>
                </div>
            </nav>
            <div class="container">