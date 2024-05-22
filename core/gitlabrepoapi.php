<?php
require_once __DIR__ . "/call.php";

class GitlabRepositoryApi {
    public static $instance = null;

    public static function getInstance() {
        if (is_null(self::$instance)) {
            self::$instance = new GitLabRepositoryApi();
        }

        return self::$instance;
    }

    private function __construct() {
        // Empty constructor
    }

    public static function get_repository_tree($project_id, $per_page = 100, $ref = null, $path = null) {
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

        $param_url = "/projects/$project_id/repository/tree?";

        foreach($params as $key => $value) {
            $param_url .= "$key=$value&";
        }
        $param_url = rtrim($param_url, "&");

        return call("GET", $param_url);
    }
}