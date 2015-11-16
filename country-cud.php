<?php

function __autoload($className) {
    require_once("php-classes/$className.php");
}
$app = Application::getInstance();
$app->handlerCountry();
$row = $app->getRowById($_GET['id'], 'countries');
?>
<?php include('header.php') ?>
<div class="content">
    <h1>Редактировать наименование страны</h1>    
    <div class="form-group">
        <form method="post">
            <div class="input-group">
                <input type="text" name="name" class="form-control" value="<?= $row['name'] ?>">
                <br><br>
                <input type="submit" class="btn btn-success">
            </div>
        </form>
    </div>
</div>
<?php include('footer.php');