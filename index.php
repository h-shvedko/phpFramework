<?php
namespace Copernicus;
include("vendor/autoload.php");
use \Copernicus\Controllers\MainController as MainController;
$controller = new MainController();

if(!empty($_GET)){
    $controller->getNumberOfArticlesPerDay();
} elseif(!empty($_POST) && array_key_exists('userID', $_POST)){
    $controller->insertUser();
} elseif(!empty($_POST) && array_key_exists('needed_date', $_POST)){
    $controller->getNumberOfArticlesPerDayBetween();
}