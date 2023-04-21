<?php
    require_once'detailcompanymodel.php';

    $model = new Model();
    $model->value($_POST);

    require_once('html/detailcompanyview.php');

?>