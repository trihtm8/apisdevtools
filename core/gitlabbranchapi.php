<?php
require_once __DIR__ . "/call.php";

class GitLabBranchApi {
    public static $instance = null;

    public static function getInstance() {
        if (is_null(self::$instance)) {
            self::$instance = new GitLabBranchApi();
        }

        return self::$instance;
    }

    private function __construct() {
        // Empty constructor
    }
    
    /**
     * List all branches in a project.
     * 
     * @param string $project_id GitLab project id
     * @return mixed Response from the GitLab API
     */
    public static function get_branches($project_id) {
        if (is_null(self::$instance)) {
            return;
        }
        return call("GET", "projects/$project_id/repository/branches");
    }

    /**
     * Get a specific branch in a project.
     * 
     * @param string $project_id GitLab project id
     * @param string $branch_name Branch name
     * @return mixed Response from the GitLab API
     */
    public static function get_branch($project_id, $branch_name) {
        if (is_null(self::$instance)) {
            return;
        }
        return call("GET", "projects/$project_id/repository/branches/$branch_name");
    }

    /**
     * Create a new branch in a project.
     * 
     * @param string $project_id GitLab project id
     * @param string $branch_name Branch name
     * @param string $ref Reference (commit SHA, branch name, or tag name)
     * @return mixed Response from the GitLab API
     */
    public static function create_branch($project_id, $branch_name, $ref) {
        if (is_null(self::$instance)) {
            return;
        }

        if (empty($project_id) || empty($branch_name) || empty($ref)) {
            return json_encode(array("error" => "Required all parameters"));
        }
        $data = [
            "branch" => $branch_name,
            "ref" => $ref
        ];
        return call("POST", "projects/$project_id/repository/branches", "v4", $data);
    }

    /**
     * Delete a branch in a project.
     * 
     * @param string $project_id GitLab project id
     * @param string $branch_name Branch name
     * @return mixed Response from the GitLab API
     */
    public static function delete_branch($project_id, $branch_name) {
        if (is_null(self::$instance)) {
            return;
        }
        return call("DELETE", "projects/$project_id/repository/branches/$branch_name");
    }
}
