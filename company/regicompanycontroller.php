<?php
    require_once'regicompanymodel.php';

    $model = new Model();
    $model->value($_POST);

    require_once('html/regicompanyview.php');

?>