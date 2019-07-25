<?php
require_once('helper/Helper.php');
use Helper\Helper;
$base_url = Helper::getURLBase();
?>
<!doctype html>
<html class="no-js" lang="">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>App</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link rel="stylesheet" href="<?=$base_url?>/css/main.css">
    <base href="<?=$base_url?>" />
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
    <script src="http://localhost:88/patata2/app/vendor/jquery-1.12.4.min.js"></script>
    <script src="http://localhost:88/patata2/app/vendor/angular/angular.js"></script>
    <script src="http://localhost:88/patata2/app/vendor/angular/angular-route.js"></script>
    <script src="http://localhost:88/patata2/app/vendor/angular/angular-messages.js"></script>
    <script src="http://localhost:88/patata2/app/vendor/angular/angular-resource.js"></script>
    <script src="http://localhost:88/patata2/app/vendor/angular-ui-bootstrap/ui-bootstrap-tpls-2.5.0.min.js"></script>
    -->
    <script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <script src="https://code.angularjs.org/1.6.9/angular.min.js"></script>
    <script src="https://code.angularjs.org/1.6.9/angular-route.min.js"></script>
    <script src="https://code.angularjs.org/1.6.9/angular-messages.min.js"></script>
    <script src="https://code.angularjs.org/1.6.9/angular-resource.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/angular-ui-bootstrap/2.5.0/ui-bootstrap-tpls.min.js"></script>
    
    <script src="<?=$base_url?>js/prototype.js"></script>
    
    <script src="<?=$base_url?>js/api_config.js"></script>
    <script src="<?=$base_url?>js/app.js"></script>
    <script src="<?=$base_url?>js/pre-config.js"></script>
    <script src="<?=$base_url?>js/config.js"></script>
    
    <script src="<?=$base_url?>js/resources.js"></script>
    <script src="<?=$base_url?>js/directivas.js"></script>
    
    <script src="<?=$base_url?>js/controllers/navigationController.js"></script>
    <script src="<?=$base_url?>js/controllers/editorialController.js"></script>
    <script src="<?=$base_url?>js/controllers/autorController.js"></script>
    <script src="<?=$base_url?>js/controllers/libroController.js"></script>
</body>

</html>
