<?php
    require_once'regioutsoucermodel.php';
    $model = new Model();
    $model->value($_POST);
    require_once('html/regioutsoucerview.php');
?>