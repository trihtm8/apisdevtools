<?php

require_once __DIR__ . "/../core/call.php";
require_once __DIR__ . "/../core/gitlabuserapi.php";
require_once __DIR__ . "/../core/gitlabrepoapi.php";
require_once __DIR__ . "/../core/gitlabcommitapi.php";

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
    new Log("get_users", "/core/user.php", "List all users", "GitLabUserApi"), #1
    new Log("create_user", "/core/user.php", "Create a user", "GitLabUserApi"), #2
    new Log("get_user_by_id", "/core/user.php", "Get user information by id", "GitLabUserApi"), #3
    new Log("update_user", "/core/user", "Update user information", "GitLabUserApi"), #4
    new Log("find_users", "/core/user.php", "Find users", "GitLabUserApi"), #5
    new Log("get_repository_tree", "/core/gitlabrepoapi.php", "Get repository tree", "GitlabRepositoryApi"), #6
    new Log("get_blob", "/core/gitlabrepoapi.php", "Get blob", "GitlabRepositoryApi"), #7
    new Log("get_raw_blob_content", "/core/gitlabrepoapi.php", "Get raw blob content", "GitlabRepositoryApi"), #8
    new Log("get_list_repository_commit", "/core/gitlabcommitapi.php", "Get list of commit of a repository", "GitlabCommitApi"), #9
    new Log("get_single_commit", "/core/gitlabcommitapi.php", "Get a single commit", "GitlabCommitApi"), #10
    new Log("get_diff_commit", "/core/gitlabcommitapi.php", "Get the diff of a commit in a project.", "GitlabCommitApi"), #11
    new Log("get_comment_of_commit", "/core/gitlabcommitapi.php", "Get comments of a commit.", "GitlabCommitApi"), #12  
    new Log("post_comment_to_commit", "/core/gitlabcommitapi.php", "Adds a comment to a commit..", "GitlabCommitApi"), #13
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

$logs[6]->list_params("project_id:text", "Project id");
$logs[6]->list_params("per_page:text", "Number of items per page");
$logs[6]->list_params("ref:text", "Reference");
$logs[6]->list_params("path:text", "Path");

$logs[7]->list_params("project_id:text", "Project id");
$logs[7]->list_params("sha:text", "ID of blob file");

$logs[8]->list_params("project_id:text", "Project id");
$logs[8]->list_params("sha:text", "ID of blob file");

$logs[9]->list_params("project_id:text", "Project id");
$logs[9]->list_params("ref:text", "Branch name");
$logs[9]->list_params("path:text", "Path");

$logs[10]->list_params("project_id:text", "Project id");
$logs[10]->list_params("sha:text", "The commit hash or name of a repository branch or tag");

$logs[11]->list_params("project_id:text", "Project id");
$logs[11]->list_params("sha:text", "The commit hash or name of a repository branch or tag");

$logs[12]->list_params("project_id:text", "Project id");
$logs[12]->list_params("sha:text", "The commit hash or name of a repository branch or tag");

$logs[13]->list_params("project_id:text", "Project id");
$logs[13]->list_params("sha:text", "The commit hash or name of a repository branch or tag");
$logs[13]->list_params("note:text", "The text of the comment");
$logs[13]->list_params("path:text", "The file path relative to the repository");
$logs[13]->list_params("line:text", "The line number where the comment should be placed");
$logs[13]->list_params("line_type:text", "The line type");