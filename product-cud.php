<?php

ini_set('display_errors', 'On');

function __autoload($className) {
    require_once("php-classes/$className.php");
}

$tableName = 'products';
$columns = ['id_brand', 'model', 'made_year', 'power', 'price'];

$app = Application::getInstance();
$app->handlerRequest($tableName, $columns, 'products', 'id_brand');
$row = $app->getRowById($_GET['id'], $tableName);
$brandsSdh = $app->pdoQueryAllRows('brands');
?>
<?php include('header.php') ?>
<div class="content">
    <h1>Редактирование продукта</h1>    
    <div class="form-group">
        <form method="post">
            <div class="input-group">
                <select name="id_brand">
                    <?php while ($brand = $brandsSdh->fetch()) { ?>
                    <option value="<?= $brand['id'] ?>"><?= $brand['name'] ?></option>
                    <?php } ?>
                </select>&nbsp;
                <span class="label label-default">Бренд</span><br><br>
                <input type="text" name="model" class="form-control" value="<?= $row['model'] ?>"><br><br>
                <span class="label label-default">Модель</span><br><br>
                <input type="text" name="made_year" class="form-control" value="<?= $row['made_year'] ?>"><br><br>
                <span class="label label-default">Дата выпуска</span><br><br>
                <input type="text" name="power" class="form-control" value="<?= $row['power'] ?>"><br><br>
                <span class="label label-default">Мощность</span><br><br>
                <input type="text" name="price" class="form-control" value="<?= $row['price'] ?>"><br><br>
                <span class="label label-default">Стоимость</span><br><br>
                <br><br>
                <input type="submit" class="btn btn-success">
            </div>
        </form>
    </div>
</div>
<?php include('footer.php');