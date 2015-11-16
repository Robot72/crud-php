<?php
ini_set('display_errors', 'On');

function __autoload($className) {
    require_once("php-classes/$className.php");
}

$app = Application::getInstance();
$stmt = $app->pdoQuery('countries');
?>
<?php include('header.php') ?>
<div class="content">
    <a class="btn btn-success" href="country-cud.php?id=0&operation=1">Добавить</a><br><br>
    <div class="panel panel-default">
        <div class="panel-heading"> Страны </div>
        <table class="table">
            <thead>
            <th>ID</th>
            <th>Наименование</th>
            <th>Действие</th>
            </thead>
            <?php while ($row = $stmt->fetch()) { ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= $row['name'] ?></td>
                    <td>
                        <a href="country-cud.php?id=<?= $row['id'] ?>&operation=2"><span class="glyphicon glyphicon-pencil">&nbsp;</span>Редактировать</a><br>
                        <a href="country-cud.php?id=<?= $row['id'] ?>&operation=0"><span class="glyphicon glyphicon-remove">&nbsp;</span>Удалить</a>
                    </td>
                </tr>
            <?php } ?>
        </table>
    </div>
    <?= Paginator::getListPages('countries', $app->getCountRows('countries')) ?>
</div>
<?php include('footer.php') ?>
                
