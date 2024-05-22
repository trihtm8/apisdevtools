<?php

require_once __DIR__ . "/../core/call.php";
require_once __DIR__ . "/../core/gitlabuser.php";

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
     * The Class of function.
     *
     * @var string
     */
    public $class_name;

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
     * @param string $class_name The group to which the route belongs.
     */
    public function __construct($function_name, $function_located, $route_name, $class_name) {
        $this->function_name = $function_name;
        $this->function_located = $function_located;
        $this->route_name = $route_name;
        $this->class_name = $class_name;
    }

    /**
     * Executes the function with the provided parameters.
     *
     * @return mixed The result of the function call.
     */
    public function run() {
        if (class_exists($this->class_name)) {
            $obj = $this->class_name::getInstance();
            if (method_exists($obj, $this->function_name)) {
                return call_user_func_array([$obj, $this->function_name], $this->params_value);
            } else {
                throw new Exception("Method '{$this->function_name}' does not exist in class '{$this->class_name}'.");
            }
        } else {
            throw new Exception("Class '{$this->class_name}' does not exist.");
        }
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
        return $this->function_located . ": " . $this->class_name . "->" . $this->function_name;
    }
}

// Example usage of the Log class with parameter definitions
$logs = [
    new Log("execute", "/core/call.php", "Call APIs", "GitLabModel"), #0
    new Log("get_users", "/core/user.php", "List all users", "GitLabUser"), #1
    new Log("create_user", "/core/user.php", "Create a user", "GitLabUser"), #2
    new Log("get_user_by_id", "/core/user.php", "Get user information by id", "GitLabUser"), #3
    new Log("update_user", "/core/user", "Update user information", "GitLabUser"), #4
    new Log("find_users", "/core/user.php", "Find users", "GitLabUser"), #5
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
