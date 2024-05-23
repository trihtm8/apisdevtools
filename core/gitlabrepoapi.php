<?php
require_once __DIR__ . "/call.php";

class GitlabRepositoryApi {
    public static $instance = null;

    public static function getInstance() {
        if (is_null(self::$instance)) {
            self::$instance = new GitlabRepositoryApi();
        }

        return self::$instance;
    }

    private function __construct() {
        // Empty constructor
    }

    /**
     * Get a list of repository files and directories in a project.
     * @param string $project_id The ID or URL-encoded path of the project
     * @param string|null $ref The name of a repository branch or tag or, if not given, the default branch.
     * @param string|null $path The path inside the repository. Used to get content of subdirectories.
     * @param int $per_page Number of results to show per page. If not specified, defaults to 100.
     * @return mixed Response from the GitLab API
     */
    public static function get_repository_tree($project_id, $ref = null, $path = null, $per_page = 100) {
        if (self::$instance == null) {
            return;
        }

        $params = [
            "per_page" => $per_page,
        ];
        if ($path != null) {
            $params["path"] = $path;
        }
        if ($ref != null) {
            $params["ref"] = $ref;
        }

        $param_url = "projects/$project_id/repository/tree?";

        foreach($params as $key => $value) {
            $param_url .= "$key=$value&";
        }
        $param_url = rtrim($param_url, "&");

        return call("GET", $param_url);
    }

<<<<<<< HEAD
=======
    /**
     * Allows you to receive information, such as size and content, about blobs in a repository. Blob content is Base64 encoded.
     * @param string $project_id The ID or URL-encoded path of the project
     * @param string $sha The blob SHA or ID.
     * @return mixed Response from the GitLab API
     */
>>>>>>> acb6005e8e7125104ea75acb281acc7b8cd644d5
    public static function get_blob($project_id, $sha) {
        if (self::$instance == null) {
            return;
        }

        $param_url = "projects/$project_id/repository/blobs/$sha";
    
        return call("GET", $param_url);
    }

<<<<<<< HEAD
=======
    /**
     * Get the raw file contents for a blob, by blob SHA.
     * @param string $project_id The ID or URL-encoded path of the project
     * @param string $sha The blob SHA or ID.
     * @return mixed Response from the GitLab API
     */
>>>>>>> acb6005e8e7125104ea75acb281acc7b8cd644d5
    public static function get_raw_blob_content($project_id, $sha) {
        if (self::$instance == null) {
            return;
        }

        $param_url = "projects/$project_id/repository/blobs/$sha/raw";

        return call("GET", $param_url);
    }
}
