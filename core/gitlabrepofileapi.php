<?php
require_once __DIR__ . "/call.php";

class GitLabRepositoryFileApi {
    public static $instance = null;

    public static function getInstance() {
        if (is_null(self::$instance)) {
            self::$instance = new GitLabRepositoryFileApi();
        }

        return self::$instance;
    }

    private function __construct() {
        // Empty constructor
    }

    /**
     * Allows you to receive information about file in repository like name, size, and content. File content is Base64 encoded.
     * @param string $project_id The ID or URL-encoded path of the project
     * @param string $file_path URL encoded full path to new file, such as `lib%2Fclass%2Erb`
     * @param string $ref The name of branch, tag or commit..
     * @return mixed Response from the GitLab API
     */
    public static function get_file_from_repository($project_id, $file_path, $ref) {
        if (self::$instance == null) {
            return;
        }

        $urlencodepath = urlencode($file_path);

        return call("GET", "projects/$project_id/repository/files/$urlencodepath?ref=$ref");
    }

    /**
     * Allows you to receive blame information. Each blame range contains lines and corresponding commit information.
     * @param string $project_id The ID or URL-encoded path of the project
     * @param string $file_path URL encoded full path to new file, such as `lib%2Fclass%2Erb`
     * @param string $ref The name of branch, tag or commit..
     * @return mixed Response from the GitLab API
     */
    public static function get_file_blame_from_repository($project_id, $file_path, $ref) {
        if (self::$instance == null) {
            return;
        }

        $urlencodepath = urlencode($file_path);

        return call("GET", "projects/$project_id/repository/files/$urlencodepath/blame?ref=$ref");
    }

    /**
     * Get the raw file contents
     * @param string $project_id The ID or URL-encoded path of the project
     * @param string $file_path URL encoded full path to new file, such as `lib%2Fclass%2Erb`
     * @param string|null $ref The name of branch, tag or commit. Default is the HEAD of the project.
     * @return mixed Response from the GitLab API
     */
    public static function get_raw_file_from_repository($project_id, $file_path, $ref = null) {
        if (self::$instance == null) {
            return;
        }

        $urlencodepath = urlencode($file_path);

        $param_url = "projects/$project_id/repository/files/$urlencodepath/raw";

        if ($ref != null) {
            $param_url .= "?ref=$ref";
        }

        return call("GET", $param_url);
    }

    /**
     * Allows you to create a single file. For creating multiple files with a single request, refer to the [commits API](https://docs.gitlab.com/ee/api/commits.html#create-a-commit-with-multiple-files-and-actions).
     * @param string $project_id The ID or URL-encoded path of the project.
     * @param string $file_path URL encoded full path to new file, such as `lib%2Fclass%2Erb`.
     * @param string $branch Name of the new branch to create. The commit is added to this branch.
     * @param string $commit_message The commit message.
     * @param string $content The file’s content.
     * @return mixed Response from the GitLab API
     */
    public static function create_new_file_in_repository($project_id, $file_path, $branch, $commit_message, $content, $author_email = null, $author_name = null) {
        if (self::$instance == null) {
            return;
        }

        $data = [
            "branch" => $branch,
            "commit_message" => $commit_message,
            "content" => $content,
        ];

        if ($author_email != null) {
            $data["author_email"] = $author_email;
        }

        if ($author_name != null) {
            $data["author_name"] = $author_name;
        }

        $urlencodepath = urlencode($file_path);

        return call("POST", "/projects/$project_id/repository/files/$urlencodepath", $data);
    }

    /**
     * Allows you to update a single file. For updating multiple files with a single request, refer to the [commits API](https://docs.gitlab.com/ee/api/commits.html#create-a-commit-with-multiple-files-and-actions).
     * @param string $project_id The ID or URL-encoded path of the project.
     * @param string $file_path URL encoded full path to new file, such as `lib%2Fclass%2Erb`.
     * @param string $branch Name of the new branch to create. The commit is added to this branch.
     * @param string $commit_message The commit message.
     * @param string $content The file’s content.
     * @return mixed Response from the GitLab API
     */
    public static function update_existing_file_in_repository($project_id, $file_path, $branch, $commit_message, $content, $author_email = null, $author_name = null) {
        if (self::$instance == null) {
            return;
        }

        $data = [
            "branch" => $branch,
            "commit_message" => $commit_message,
            "content" => $content
        ];

        if ($author_email != null) {
            $data["author_email"] = $author_email;
        }

        if ($author_name != null) {
            $data["author_name"] = $author_name;
        }

        $urlencodepath = urlencode($file_path);

        return call("PUT", "/projects/$project_id/repository/files/$urlencodepath", $data);
    }

    /**
     * This allows you to delete a single file. For deleting multiple files with a single request,  refer to the [commits API](https://docs.gitlab.com/ee/api/commits.html#create-a-commit-with-multiple-files-and-actions).
     * @param string $project_id The ID or URL-encoded path of the project.
     * @param string $file_path URL encoded full path to new file, such as `lib%2Fclass%2Erb`.
     * @param string $branch Name of the new branch to create. The commit is added to this branch.
     * @param string $commit_message The commit message.
     * @return mixed Response from the GitLab API
     */
    public static function delete_existing_file_in_repository($project_id, $file_path, $branch, $commit_message, $author_email = null, $author_name = null) {
        if (self::$instance == null) {
            return;
        }

        $data = [
            "branch" => $branch,
            "commit_message" => $commit_message,
        ];

        if ($author_email != null) {
            $data["author_email"] = $author_email;
        }

        if ($author_name != null) {
            $data["author_name"] = $author_name;
        }

        $urlencodepath = urlencode($file_path);

        return call("DELETE", "/projects/$project_id/repository/files/$urlencodepath", $data);
    }
}
