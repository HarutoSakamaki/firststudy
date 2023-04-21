<?php
    require_once'changecompanymodel.php';

    $model = new Model();
    $model->value($_POST);

    require_once('html/changecompanyview.php');

?>