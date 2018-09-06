<?php
error_reporting(E_ALL);
require_once('../core/Helper/Helper.php');
use Helper\Helper;
$url_base = Helper::getURLBase();
?>

<!doctype html>
<html class="no-js" lang="">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>App</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--
    <link rel="stylesheet" href="<?=$url_base;?>vendor/bootstrap-3.3.7-dist/css/bootstrap.css">
    -->
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link rel="stylesheet" href="<?=$url_base;?>css/main.css">
    <base href="<?=$url_base;?>" />
</head>

<body ng-app="app">
    <!-- container -->
    <div class="container">
        <!-- Menu -->
        <ng-include src="'html/menu.html'"></ng-include>
        <ng-view></ng-view>
        <ng-include src="'html/loader.html'"></ng-include>
    </div>
    <!-- /container -->
    <!--
    <script src="<?=$url_base;?>vendor/jquery-1.12.4.min.js"></script>
    <script src="<?=$url_base;?>vendor/angular/angular.js"></script>
    <script src="<?=$url_base;?>vendor/angular/angular-route.js"></script>
    <script src="<?=$url_base;?>vendor/angular/angular-messages.js"></script>
    <script src="<?=$url_base;?>vendor/angular/angular-resource.js"></script>
    <script src="<?=$url_base;?>vendor/angular-ui-bootstrap/ui-bootstrap-tpls-2.5.0.min.js"></script>
    -->
    <script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <script src="https://code.angularjs.org/1.6.9/angular.min.js"></script>
    <script src="https://code.angularjs.org/1.6.9/angular-route.min.js"></script>
    <script src="https://code.angularjs.org/1.6.9/angular-messages.min.js"></script>
    <script src="https://code.angularjs.org/1.6.9/angular-resource.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/angular-ui-bootstrap/2.5.0/ui-bootstrap-tpls.min.js"></script>
    
    <script src="<?=$url_base;?>js/prototype.js"></script>
    
    <script src="<?=$url_base;?>js/app.js"></script>
    <script src="<?=$url_base;?>js/pre-config.js"></script>
    <script src="<?=$url_base;?>js/config.js"></script>
    
    <script src="<?=$url_base;?>js/resources.js"></script>
    <script src="<?=$url_base;?>js/directivas.js"></script>
    
    <script src="<?=$url_base;?>js/controllers/navigationController.js"></script>
    <script src="<?=$url_base;?>js/controllers/editorialController.js"></script>
    <script src="<?=$url_base;?>js/controllers/autorController.js"></script>
    <script src="<?=$url_base;?>js/controllers/libroController.js"></script>
</body>

</html>
