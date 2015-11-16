<?php
ini_set('display_errors', 'On');

function __autoload($className) {
    require_once("php-classes/$className.php");
}

$app = Application::getInstance();
$stmt = $app->pdoQueryPage('products');
?>
<?php include('header.php') ?>
<div class="content">
    <a class="btn btn-success" href="product-cud.php?id=0&operation=1">Добавить</a><br><br>
    <div class="panel panel-default">
        <div class="panel-heading"> Продукты </div>
        <table class="table">
            <thead>
            <th>ID</th>
            <th>Наименование</th>
            <th>Модель</th>
            <th>Дата производства</th>
            <th>Действие</th>
            </thead>
            <?php while ($row = $stmt->fetch()) { ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= $app->getRowById($row['id_brand'], 'brands')['name'] ?></td>
                    <td><?= $row['model'] ?></td>
                    <td><?= $row['made_year'] ?></td>
                    <td>
                        <a href="product-cud.php?id=<?= $row['id'] ?>&operation=2"><span class="glyphicon glyphicon-pencil">&nbsp;</span>Редактировать</a><br>
                        <a href="product-cud.php?id=<?= $row['id'] ?>&operation=0"><span class="glyphicon glyphicon-remove">&nbsp;</span>Удалить</a>
                    </td>
                </tr>
            <?php } ?>
        </table>
    </div>
    <?= Paginator::getListPages('products', $app->getCountRows('products')) ?>
</div>
<?php include('footer.php');