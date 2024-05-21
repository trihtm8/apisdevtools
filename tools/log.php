<?php

require_once __DIR__ . "/../core/call.php";
require_once __DIR__ . "/../core/user.php";

/**
 * Class Log
 *
 * This class provides logging functionalities and manages parameters for various routes and functions.
 */
class Log {
    /**
     * The base directory of this tools.
     *
     * @var string
     */
    public $base_located = __DIR__ . "/..";

    /**
     * The name of the function being logged.
     *
     * @var string
     */
    public $function_name;

    /**
     * The location of the function.
     *
     * @var string
     */
    public $function_located;

    /**
     * The name of the route.
     *
     * @var string
     */
    public $route_name;

    /**
     * The group to which the route belongs.
     *
     * @var string
     */
    public $route_group;

    /**
     * List of parameter names and type_of_input_HTML_elements.
     *
     * @var array
     */
    public $params_list = [];

    /**
     * List of parameter details.
     *
     * @var array
     */
    public $params_detail = [];

    /**
     * List of parameter values.
     *
     * @var array
     */
    public $params_value = [];

    /**
     * Constructor for the Log class.
     *
     * @param string $function_name The name of the function.
     * @param string $function_located The location of the function.
     * @param string $route_name The name of the route.
     * @param string $route_group The group to which the route belongs.
     */
    public function __construct($function_name, $function_located, $route_name, $route_group) {
        $this->function_name = $function_name;
        $this->function_located = $function_located;
        $this->route_name = $route_name;
        $this->route_group = $route_group;
    }

    /**
     * Executes the function with the provided parameters.
     *
     * @return mixed The result of the function call.
     */
    public function run() {
        return call_user_func_array($this->route_group . "_" . $this->function_name, $this->params_value);
    }

    /**
     * Adds a parameter to the parameter list.
     *
     * @param string $nametype The name and type_of_input_HTML_element of the parameter (e.g., "username:text").
     * @param string $detail The description of the parameter.
     * @return void
     */
    public function list_params($nametype, $detail) {
        $this->params_list[] = $nametype;
        $this->params_detail[] = $detail;
        $this->params_value[] = null;
    }

    /**
     * Loads a value for a specified parameter.
     *
     * @param string $name The name of the parameter.
     * @param mixed $c_value The value to be assigned to the parameter.
     * @return void
     */
    public function load_params($name, $c_value) {
        foreach ($this->params_list as $key => $paramname) {
            if (explode(":", $paramname)[0] == $name) {
                $this->params_value[$key] = $c_value;
                break;
            }
        }
    }

    /**
     * Returns the location and function information.
     *
     * @return string The location and function information.
     */
    public function get_located() {
        return $this->function_located . ": " . $this->route_group . "_" . $this->function_name;
    }
}

// Example usage of the Log class with parameter definitions
$logs = [
    new Log("call", "/core/call.php", "Call APIs", "call"), #0
    new Log("get_users", "/core/user.php", "List all users", "user"), #1
    new Log("create_user", "/core/user.php", "Create a user", "user"), #2
    new Log("get_user_by_id", "/core/user.php", "Get user information by id", "user"), #3
    new Log("update_user", "/core/user", "Update user information", "user"), #4
    new Log("find_users", "/core/user.php", "Find users", "user"), #5
];

// Define parameters for specific logs
$logs[2]->list_params("username:text", "Username");
$logs[2]->list_params("name:text", "Full name");
$logs[2]->list_params("email:text", "Email");
$logs[2]->list_params("password:password", "Password");

$logs[3]->list_params("id:text", "User id");

$logs[4]->list_params("id:text", "User id");
$logs[4]->list_params("username:text", "Username");
$logs[4]->list_params("name:text", "Full name");
$logs[4]->list_params("email:text", "Email");
$logs[4]->list_params("password:password", "Password");

$logs[5]->list_params("string_in_username:text", "String for search in username");
$logs[5]->list_params("string_in_email:text", "String for search in email");
