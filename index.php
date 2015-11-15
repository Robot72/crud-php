<?php

function __autoload($className) {
    require_once("php-classes/$className.php");
}
?>
<?php include('header.php') ?>
<div class="content">

    <div class="body-content">

        <div class="row">
            <div class="col-lg-4">
                <h2>CRUD operations for:
                    <p><a class="btn btn-default" href="/products.php">Products &raquo;</a></p>
                    <p><a class="btn btn-default" href="/brands.php">Brands &raquo;</a></p>
                    <p><a class="btn btn-default" href="/countries.php">Countries &raquo;</a></p>
            </div>

        </div>

    </div>
</div>
<?php include('footer.php') ?>
                
