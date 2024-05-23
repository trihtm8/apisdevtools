<?php
require_once __DIR__ . "/call.php";

class GitLabProjectApi {
    public static $instance = null;

    public static function getInstance() {
        if (is_null(self::$instance)) {
            self::$instance = new GitLabProjectApi();
        }

        return self::$instance;
    }

    private function __construct() {
        // Empty constructor
    }
    
    /**
     * List all projects in the GitLab server.
     */
    public static function get_projects() {
        if (is_null(self::$instance)) {
            return;
        }
        return call("GET", "projects");
    }

    /**
     * Get information of a project by GitLab project id
     * 
     * @param string $id GitLab project id 
     * @return mixed Response from the GitLab API
     */
    public static function get_by_id($id) {
        if (is_null(self::$instance)) {
            return;
        }
        return call("GET", "projects/$id");
    }

    /**
     * Create a project
     * 
     * @param string $name Name of the project
     * @param string|null $description Description of the project (optional)
     * @param bool $visibility Visibility level of the project (optional, default is `public`)
     * @return mixed Response from the GitLab API
     */
    public static function create_project($name, $description = null, $visibility = "private") {
        if (is_null(self::$instance)) {
            return;
        }

        if (empty($name)) {
            return json_encode(array("error" => "Project name is required"));
        }
        $data = [
            "name" => $name,
            "description" => $description,
            "initialize_with_readme" => "true",
            "default_branch" => "main"
        ];
        if (!empty($visibility)) {
            $data["visibility"] = $visibility;
        }else{
            $data["visibility"] = "private";
        }
        return call("POST", "projects", $data);
    }

    /**
     * Update a project
     * 
     * @param string $id GitLab project id
     * @param string|null $name Name of the project (optional)
     * @param string|null $description Description of the project (optional)
     * @param bool|null $visibility Visibility level of the project (optional)
     * @return mixed Response from the GitLab API
     */
    public static function update_project($id, $name = null, $description = null, $visibility = "private") {
        if (is_null(self::$instance)) {
            return;
        }
        $data = [];
        if (empty($name) && empty($description) && empty($visibility)) {
            return json_encode(["error" => "Nothing to update, and request was not sent."]);
        }
        if (!empty($name)) {
            $data["name"] = $name;
        }
        if (!empty($description)) {
            $data["description"] = $description;
        }
        if (!empty($visibility)) {
            $data["visibility"] = $visibility;
        }
        return call("PUT", "projects/$id", $data);
    }

    /**
     * Find projects by name or description containing specific strings
     * 
     * @param string|null $string_in_name Substring to search for in the GitLab project name (optional) 
     * @param string|null $string_in_description Substring to search for in the project's description (optional)
     * @return mixed Response from the GitLab API or a message if no parameters are provided
     */
    public static function find_projects($string_in_name = null, $string_in_description = null) {
        if (is_null(self::$instance)) {
            return;
        }
        if (empty($string_in_name) && empty($string_in_description)) {
            return json_encode(["error" => "No information provided to search."]);
        }

        if (!empty($string_in_name) && !empty($string_in_description)) {
            return json_encode(["error" => "Please provide only one search parameter: name or description."]);
        }

        $params = [];
        if (!empty($string_in_name)) {
            $params['search'] = $string_in_name;
        } elseif (!empty($string_in_description)) {
            $params['search'] = $string_in_description;
        }

        $query_string = http_build_query($params);

        return call("GET", "projects?$query_string");
    }

    /**
     * Delete a project by GitLab project id
     * 
     * @param string $id GitLab project id 
     * @return mixed Response from the GitLab API
     */
    public static function delete_by_id($id) {
        if (is_null(self::$instance)) {
            return;
        }
        return call("DELETE", "projects/$id");
    }

    /**
     * Create a project for a specific user
     * 
     * @param string $name Name of the project
     * @param string|null $description Description of the project (optional)
     * @param string $user_id GitLab user id
     * @param bool $visibility Visibility level of the project (optional, default is `private`)
     * @return mixed Response from the GitLab API
     */
    public static function create_project_for_user($name, $description = null, $user_id, $visibility = "private") {
        if (is_null(self::$instance)) {
            return;
        }

        if (empty($name)) {
            return json_encode(array("error" => "Project name is required"));
        }
        if (empty($user_id)) {
            return json_encode(array("error" => "User id is required"));
        }
        $data = [
            "name" => $name,
            "description" => $description,
            "initialize_with_readme" => "true",
            "default_branch" => "main"
        ];
        if (!empty($visibility)) {
            $data["visibility"] = $visibility;
        }else{
            $data["visibility"] = "private";
        }
        return call("POST", "projects/user/$user_id", $data);
    }
}
