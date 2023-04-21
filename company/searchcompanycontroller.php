<?php
    require_once'searchcompanymodel.php';
    $model = new Model();
    $model->value($_POST);
    require_once('html/searchcompanyview.php');
?>