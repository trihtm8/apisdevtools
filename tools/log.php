<?php

require_once __DIR__ . "/../core/call.php";
require_once __DIR__ . "/../core/gitlabuserapi.php";
require_once __DIR__ . "/../core/gitlabrepoapi.php";
require_once __DIR__ . "/../core/gitlabprojectapi.php";
require_once __DIR__ . "/../core/gitlabbranchapi.php";


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
    new Log("get_users", "/core/gitlabuserapi.php", "List all users", "GitLabUserApi"), #1
    new Log("create_user", "/core/gitlabuserapi.php", "Create a user", "GitLabUserApi"), #2
    new Log("get_by_id", "/core/gitlabuserapi.php", "Get user information by id", "GitLabUserApi"), #3
    new Log("update_user", "/core/user", "Update user information", "GitLabUserApi"), #4
    new Log("find_users", "/core/gitlabuserapi.php", "Find users", "GitLabUserApi"), #5
    new Log("delete_by_id", "/core/gitlabuserapi.php", "Delete user", "GitLabUserApi"), #6

    new Log("get_repository_tree", "/core/gitlabrepoapi.php", "Get repository tree", "GitlabRepositoryApi"), #7

    new Log("get_projects", "/core/gitlabprojectapi.php", "List all projects", "GitLabProjectApi"), #8
    new Log("get_by_id", "/core/gitlabprojectapi.php", "Get project information by id", "GitLabProjectApi"), #9
    new Log("create_project", "/core/gitlabprojectapi.php", "Create a project", "GitLabProjectApi"), #10
    new Log("update_project", "/core/gitlabprojectapi.php", "Update project information", "GitLabProjectApi"), #11
    new Log("find_projects", "/core/gitlabprojectapi.php", "Find projects", "GitLabProjectApi"), #12
    new Log("delete_by_id", "/core/gitlabprojectapi.php", "Delete project", "GitLabProjectApi"), #13
    new Log("create_project_for_user", "/core/gitlabprojectapi.php", "Create a project for a specific user", "GitLabProjectApi"), #14

    new Log("get_branches", "/core/gitlabbranchapi.php", "List all branches in a project", "GitLabBranchApi"), #15
    new Log("get_branch", "/core/gitlabbranchapi.php", "Get a branch in a project", "GitLabBranchApi"), #16
    new Log("create_branch", "/core/gitlabbranchapi.php", "Create a branch in a project", "GitLabBranchApi"), #17
    new Log("delete_branch", "/core/gitlabbranchapi.php", "Delete a branch in a project", "GitLabBranchApi"), #18
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

$logs[6]->list_params("id:text", "User id");

$logs[7]->list_params("project_id:text", "Project id");
$logs[7]->list_params("per_page:text", "Number of items per page");
$logs[7]->list_params("ref:text", "Reference");
$logs[7]->list_params("path:text", "Path");

$logs[8]->list_params("page:int", "Page number");
$logs[8]->list_params("per_page:int", "Number of items per page");

$logs[9]->list_params("id:text", "Project id");

$logs[10]->list_params("name:text", "Project name");
$logs[10]->list_params("description:text", "Description");
$logs[10]->list_params("visibility:boolean", "Visibility");

$logs[11]->list_params("id:text", "Project id");
$logs[11]->list_params("name:text", "Project name");
$logs[11]->list_params("description:text", "Description");
$logs[11]->list_params("visibility:boolean", "Visibility");

$logs[12]->list_params("string_in_name:text", "String for search in project name");
$logs[12]->list_params("string_in_description:text", "String for search in description");

$logs[13]->list_params("id:text", "Project id");

$logs[14]->list_params("name:text", "Project name");
$logs[14]->list_params("description:text", "Description");
$logs[14]->list_params("user_id:text", "User id");
$logs[14]->list_params("visibility:boolean", "Visibility");

$logs[15]->list_params("project_id:text", "Project id");

$logs[16]->list_params("project_id:text", "Project id");
$logs[16]->list_params("branch_name:text", "Branch name");

$logs[17]->list_params("project_id:text", "Project id");
$logs[17]->list_params("branch_name:text", "Branch name");
$logs[17]->list_params("ref:text", "Reference");

$logs[18]->list_params("project_id:text", "Project id");
$logs[18]->list_params("branch_name:text", "Branch name");
