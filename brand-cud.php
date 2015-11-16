<?php

ini_set('display_errors', 'On');

function __autoload($className) {
    require_once("php-classes/$className.php");
}

$tableName = 'brands';
$columns = ['id_country', 'name'];

$app = Application::getInstance();
$app->handlerRequest($tableName, $columns, 'brands', 'id_country');
$row = $app->getRowById($_GET['id'], $tableName);
$countrySdh = $app->pdoQueryAllRows('countries');
?>
<?php include('header.php') ?>
<div class="content">
    <h1>Редактировать наименование бренда</h1>    
    <div class="form-group">
        <form method="post">
            <div class="input-group">
                <select name="id_country">
                    <?php while ($country = $countrySdh->fetch()) { ?>
                    <option value="<?= $country['id'] ?>"><?= $country['name'] ?></option>
                    <?php } ?>
                </select>&nbsp;
                <span class="label label-default">Страна</span><br><br>
                <input type="text" name="name" class="form-control" value="<?= $row['name'] ?>"><br><br>
                <span class="label label-default">Наименование бренда</span><br><br>
                
                <input type="submit" class="btn btn-success">
            </div>
        </form>
    </div>
</div>
<?php include('footer.php');