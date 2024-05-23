<?php

require_once __DIR__ . "/../core/call.php";
require_once __DIR__ . "/../core/gitlabuserapi.php";
require_once __DIR__ . "/../core/gitlabrepoapi.php";
require_once __DIR__ . "/../core/gitlabprojectapi.php";
require_once __DIR__ . "/../core/gitlabbranchapi.php";
require_once __DIR__ . "/../core/gitlabcommitapi.php";
require_once __DIR__ . "/../core/gitlabrepofileapi.php";

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
                if (!empty($c_value))
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

    new Log("get_projects", "/core/gitlabprojectapi.php", "List all projects", "GitLabProjectApi"), #7
    new Log("get_by_id", "/core/gitlabprojectapi.php", "Get project information by id", "GitLabProjectApi"), #8
    new Log("create_project", "/core/gitlabprojectapi.php", "Create a project", "GitLabProjectApi"), #9
    new Log("update_project", "/core/gitlabprojectapi.php", "Update project information", "GitLabProjectApi"), #10
    new Log("find_projects", "/core/gitlabprojectapi.php", "Find projects", "GitLabProjectApi"), #11
    new Log("delete_by_id", "/core/gitlabprojectapi.php", "Delete project", "GitLabProjectApi"), #12
    new Log("create_project_for_user", "/core/gitlabprojectapi.php", "Create a project for a specific user", "GitLabProjectApi"), #13

    new Log("get_branches", "/core/gitlabbranchapi.php", "List all branches in a project", "GitLabBranchApi"), #14
    new Log("get_branch", "/core/gitlabbranchapi.php", "Get a branch in a project", "GitLabBranchApi"), #15
    new Log("create_branch", "/core/gitlabbranchapi.php", "Create a branch in a project", "GitLabBranchApi"), #16
    new Log("delete_branch", "/core/gitlabbranchapi.php", "Delete a branch in a project", "GitLabBranchApi"), #17

    new Log("get_repository_tree", "/core/gitlabrepoapi.php", "Get repository tree", "GitLabRepositoryApi"), #18
    new Log("get_blob", "/core/gitlabrepoapi.php", "Get blob", "GitLabRepositoryApi"), #19
    new Log("get_raw_blob_content", "/core/gitlabrepoapi.php", "Get raw blob content", "GitLabRepositoryApi"), #20

    new Log("get_list_repository_commit", "/core/gitlabcommitapi.php", "Get list of commit of a repository", "GitLabCommitApi"), #21
    new Log("get_single_commit", "/core/gitlabcommitapi.php", "Get a single commit", "GitLabCommitApi"), #22
    new Log("get_diff_commit", "/core/gitlabcommitapi.php", "Get the diff of a commit in a project.", "GitLabCommitApi"), #23
    new Log("get_comment_of_commit", "/core/gitlabcommitapi.php", "Get comments of a commit.", "GitLabCommitApi"), #24
    new Log("post_comment_to_commit", "/core/gitlabcommitapi.php", "Adds a comment to a commit..", "GitLabCommitApi"), #25

    new Log("get_file_from_repository", "/core/gitlabrepofileapi.php", "Allows you to receive information about file in repository", "GitLabRepositoryFileApi"), #26
    new Log("get_file_blame_from_repository", "/core/gitlabcommitapi.php", "Allows you to receive blame information", "GitLabRepositoryFileApi"), #27
    new Log("get_raw_file_from_repository", "/core/gitlabcommitapi.php", "Get the raw file contents.", "GitLabRepositoryFileApi"), #28
    new Log("create_new_file_in_repository", "/core/gitlabcommitapi.php", "create a single file.", "GitLabRepositoryFileApi"), #29
    new Log("update_existing_file_in_repository", "/core/gitlabcommitapi.php", "update a single file.", "GitLabRepositoryFileApi"), #30
    new Log("delete_existing_file_in_repository", "/core/gitlabcommitapi.php", "delete a single file.", "GitLabRepositoryFileApi"), #31
];

// Define parameters for specific logs
#User
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

#Project
$logs[7]->list_params("page:int", "Page number");
$logs[7]->list_params("per_page:int", "Number of items per page");

$logs[8]->list_params("id:text", "Project id");

$logs[9]->list_params("name:text", "Project name");
$logs[9]->list_params("description:text", "Description");
$logs[9]->list_params("visibility:boolean", "Visibility");

$logs[10]->list_params("id:text", "Project id");
$logs[10]->list_params("name:text", "Project name");
$logs[10]->list_params("description:text", "Description");
$logs[10]->list_params("visibility:boolean", "Visibility");

$logs[11]->list_params("string_in_name:text", "String for search in project name");
$logs[11]->list_params("string_in_description:text", "String for search in description");

$logs[12]->list_params("id:text", "Project id");

$logs[13]->list_params("name:text", "Project name");
$logs[13]->list_params("user_id:text", "User id");
$logs[13]->list_params("description:text", "Description");
$logs[13]->list_params("visibility:boolean", "Visibility");

#Branch
$logs[14]->list_params("project_id:text", "Project id");

$logs[15]->list_params("project_id:text", "Project id");
$logs[15]->list_params("branch_name:text", "Branch name");

$logs[16]->list_params("project_id:text", "Project id");
$logs[16]->list_params("branch_name:text", "Branch name");
$logs[16]->list_params("ref:text", "Reference");

$logs[17]->list_params("project_id:text", "Project id");
$logs[17]->list_params("branch_name:text", "Branch name");

#Repo
$logs[18]->list_params("project_id:text", "Project id");
$logs[18]->list_params("per_page:text", "Number of items per page");
$logs[18]->list_params("ref:text", "Reference");
$logs[18]->list_params("path:text", "Path");

$logs[19]->list_params("project_id:text", "Project id");
$logs[19]->list_params("sha:text", "ID of blob file");

$logs[20]->list_params("project_id:text", "Project id");
$logs[20]->list_params("sha:text", "ID of blob file");

#Commit
$logs[21]->list_params("project_id:text", "Project id");
$logs[21]->list_params("ref:text", "Branch name");
$logs[21]->list_params("path:text", "Path");

$logs[22]->list_params("project_id:text", "Project id");
$logs[22]->list_params("sha:text", "The commit hash or name of a repository branch or tag");

$logs[23]->list_params("project_id:text", "Project id");
$logs[23]->list_params("sha:text", "The commit hash or name of a repository branch or tag");

$logs[24]->list_params("project_id:text", "Project id");
$logs[24]->list_params("sha:text", "The commit hash or name of a repository branch or tag");

$logs[25]->list_params("project_id:text", "Project id");
$logs[25]->list_params("sha:text", "The commit hash or name of a repository branch or tag");
$logs[25]->list_params("note:text", "The text of the comment");
$logs[25]->list_params("path:text", "The file path relative to the repository");
$logs[25]->list_params("line:text", "The line number where the comment should be placed");
$logs[25]->list_params("line_type:text", "The line type");

$logs[26]->list_params("project_id:text", "Project id");
$logs[26]->list_params("file_path:text", "The file path relative to the repository");
$logs[26]->list_params("ref:text", "Branch name");

$logs[27]->list_params("project_id:text", "Project id");
$logs[27]->list_params("file_path:text", "The file path relative to the repository");
$logs[27]->list_params("ref:text", "Branch name");

$logs[28]->list_params("project_id:text", "Project id");
$logs[28]->list_params("file_path:text", "The file path relative to the repository");
$logs[28]->list_params("ref:text", "Branch name");

$logs[29]->list_params("project_id:text", "Project id");
$logs[29]->list_params("file_path:text", "The file path relative to the repository");
$logs[29]->list_params("branch:text", "Branch name");
$logs[29]->list_params("commit_message:text", "The commit message.");
$logs[29]->list_params("content:text", "The file’s content.");
$logs[29]->list_params("author_email:text", "Email of author");
$logs[29]->list_params("author_name:text", "Name of author");

$logs[30]->list_params("project_id:text", "Project id");
$logs[30]->list_params("file_path:text", "The file path relative to the repository");
$logs[30]->list_params("branch:text", "Branch name");
$logs[30]->list_params("commit_message:text", "The commit message.");
$logs[30]->list_params("content:text", "The file’s content.");
$logs[30]->list_params("author_email:text", "Email of author");
$logs[30]->list_params("author_name:text", "Name of author");

$logs[31]->list_params("project_id:text", "Project id");
$logs[31]->list_params("file_path:text", "The file path relative to the repository");
$logs[31]->list_params("branch:text", "Branch name");
$logs[31]->list_params("commit_message:text", "The commit message.");
$logs[31]->list_params("author_email:text", "Email of author");
$logs[31]->list_params("author_name:text", "Name of author");