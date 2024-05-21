<?php
require_once __DIR__."/../tools/jsonstring.php";
require_once __DIR__."/../tools/log.php";
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET["group"])) {
    $group = $_GET["group"];
    foreach ($logs as $key => $log){
        if ($log->route_group == $group) {
            echo "#";
            echo $log->function_name;
            echo "||";
            echo $log->route_name;
            echo "||";
            foreach ($log->params_list as $key => $name){
                echo "$name:{$log->params_detail[$key]};";
            };
        }
    }
}