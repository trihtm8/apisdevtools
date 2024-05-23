<?php
require_once __DIR__ . "/call.php";

class GitlabCommitApi {
    public static $instance = null;

    public static function getInstance() {
        if (is_null(self::$instance)) {
            self::$instance = new GitlabCommitApi();
        }

        return self::$instance;
    }

    private function __construct() {
        // Empty constructor
    }

    public static function get_list_repository_commit($project_id, $ref = null, $path = null) {
        $endpoint = "projects/$project_id/repository/commits";
        $params = [];

        if ($ref != null) {
            $params["ref"] = $ref;
        }

        if ($path != null) {
            $params["path"] = $path;
        }

        if (count($params) > 0) {
            $endpoint .= "?" . http_build_query($params);
        }
        
        return call("GET", "projects/$project_id/repository/commits");
    }

    public static function get_single_commit($project_id, $sha) {
        return call("GET", "projects/$project_id/repository/commits/$sha");
    }

    public static function get_diff_commit($project_id, $sha) {
        return call("GET", "projects/$project_id/repository/commits/$sha/diff");
    }

    public static function get_comment_of_commit($project_id, $sha) {
        return call("GET", "projects/$project_id/repository/commits/$sha/comments");
    }

    public static function post_comment_to_commit($project_id, $sha, $note, $path = null, $line = null, $line_type = null) {
        $data = [
            "note" => $note,
        ];

        if ($path != null) {
            $data["path"] = $path;
        }

        if ($line != null) {
            $data["line"] = (int)$line;
            $line_type = "new";
        }

        if ($line_type != null) {
            $data["line_type"] = $line_type;
        }
        
        return call("POST", "projects/$project_id/repository/commits/$sha/comments", $data);
    }

    public static function create_project_for_specific_user($project_name, $user_id, $project_description = null) {
        $data = [
            "name" => $project_name,
            "user_id" => $user_id,
            "visibility" => "private"
        ];

        if ($project_description != null) {
            $data["description"] = $project_description;
        }

        $response = call("POST", "projects/user/$user_id", $data);

        // if ($response.has)
    }
}