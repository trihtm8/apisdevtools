<?php
require_once __DIR__."/../core/call.php";
require_once __DIR__."/../core/user.php";

class Log{
    public $base_located = __DIR__."/..";
    public $function_name;
    public $function_located;
    public $route_name;
    public $route_group;
    public $params_list=[];
    public $params_detail=[];
    public $params_value=[];
    public function __construct($function_name, $function_located, $route_name, $route_group){
        $this->function_name = $function_name;
        $this->function_located = $function_located;
        $this->route_name = $route_name;
        $this->route_group = $route_group;
    }

    public function run(){
        return call_user_func_array($this->route_group ."_".$this->function_name, $this->params_value);
    }

    public function list_params($nametype, $detail){
        $this->params_list[count($this->params_list)] = $nametype;
        $this->params_detail[count( $this->params_detail)] = $detail;
        $this->params_value[count( $this->params_value)] = "";
    }

    public function load_params($name, $c_value){
        foreach($this->params_list as $key => $paramname){
            if(explode(":",$paramname)[0] == $name){
                $index = $key;
                $this->params_value[$index] = $c_value;
                break;
            }
        }
    }

    public function get_located(){
        return $this->function_located.": ".$this->route_group."_".$this->function_name;
    }
}

$logs = [
    new Log("call","/core/call.php", "Call APIs", "call"), #0
    new Log("get_users","/core/user.php", "List all users", "user"), #1
    new Log("create_user", "/core/user.php", "Create an user", "user"), #2
    new Log("get_user_by_id","/core/user.php", "Get user infomation by id", "user"), #3
    new Log("update_user","/core/user", "Update user infomation", "user"), #4
];

$logs[2]->list_params("username:text", "Username");
$logs[2]->list_params("name:text", "Full name");
$logs[2]->list_params("email:text", "Email");
$logs[2]->list_params("password:password","Password");

$logs[3]->list_params("id:text", "User id");

$logs[4]->list_params("id:text","User id");
$logs[4]->list_params("username:text", "Username");
$logs[4]->list_params("name:text", "Full name");
$logs[4]->list_params("email:text", "Email");
$logs[4]->list_params("password:password","Password");