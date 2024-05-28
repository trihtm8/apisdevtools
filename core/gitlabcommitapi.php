<?php
require_once __DIR__ . "/call.php";

class GitLabCommitApi {
    public static $instance = null;

    public static function getInstance() {
        if (is_null(self::$instance)) {
            self::$instance = new GitLabCommitApi();
        }

        return self::$instance;
    }

    private function __construct() {
        // Empty constructor
    }

    /**
     * Get a list of repository commits in a project.
     * @param string $project_id The ID or URL-encoded path of the project.
     * @param string|null $ref The name of a repository branch, tag or revision range, or if not given the default branch.
     * @param string|null $path The file path.
     * @return mixed Response from the GitLab API.
     */
    public static function get_list_repository_commit($project_id, $ref = null, $path = null) {
        if (self::$instance == null) {
            return;
        }
        $endpoint = "projects/$project_id/repository/commits";
        $params = [];

        if ($ref != null) {
            $params["ref_name"] = $ref;
        }

        if ($path != null) {
            $params["path"] = $path;
        }

        if (count($params) > 0) {
            $endpoint .= "?" . http_build_query($params);
        }
        
        return call("GET", $endpoint);
    }

    /**
     * Create a commit by posting a JSON payload.
     * @param string $project_id The ID or URL-encoded path of the project.
     * @param string $branch Name of the branch to commit into. To create a new branch, also provide `start_branch`.
     * @param string $commit_message Commit message.
     * @param string $aithor_name Specify the commit author’s name.
     * @param string $author_email Specify the commit author’s email address.
     * @param array $actions An array of action hashes to commit as a batch. You must pass some attributes to use this function. 
     * See this [link](https://docs.gitlab.com/ee/api/commits.html#create-a-commit-with-multiple-files-and-actions).
     * Example:
     * 
     * [
     * 
     *           [
     * 
     *               "action" => "create",
     * 
     *              "file_path" => "foo/bar",
     * 
     *               "content" => "some content"
     * 
     *           ],
     * 
     *           [
     * 
     *               "action" => "delete",
     * 
     *               "file_path" => "foo/bar2"
     * 
     *           ],
     * 
     *           [
     * 
     *               "action" => "move",
     * 
     *               "file_path" => "foo/bar3",
     * 
     *               "previous_path" => "foo/bar4",
     * 
     *               "content" => "some content"
     * 
     *           ],
     * 
     *           [
     * 
     *               "action" => "update",
     * 
     *               "file_path" => "foo/bar5",
     * 
     *               "content" => "new content"
     * 
     *           ],
     * 
     *           [
     * 
     *              "action" => "chmod",
     * 
     *              "file_path" => "foo/bar5",
     * 
     *              "execute_filemode" => true
     * 
     *           ]
     * 
     *       ]
     * @return mixed Response from the GitLab API.
     */
    public static function create_commit($project_id, $branch, $commit_message, $author_name, $author_email = null, $actions, $force = false) {
        if (self::$instance == null) {
            return;
        }
        $data = [
            "branch" => $branch,
            "commit_message"=> $commit_message,
            "actions" => $actions,
            "author_name" => $author_name
        ];

        if ($force) {
            $data["author_email"] = $author_email;
        }

        if ($force) {
            $data["force"] = $force;
        }

        return call("POST", "projects/$project_id/repository/commits", $data);
    }

    /**
     * Get a specific commit identified by the commit hash or name of a branch or tag.
     * @param string $project_id The ID or URL-encoded path of the project.
     * @param string $sha The commit hash or name of a repository branch or tag.
     * @return mixed Response from the GitLab API
     */
    public static function get_single_commit($project_id, $sha) {
        if (self::$instance == null) {
            return;
        }
        return call("GET", "projects/$project_id/repository/commits/$sha");
    }

    /**
     * Get the diff of a commit in a project.
     * @param string $project_id The ID or URL-encoded path of the project.
     * @param string $sha The commit hash or name of a repository branch or tag.
     * @return mixed Response from the GitLab API
     */
    public static function get_diff_commit($project_id, $sha) {
        if (self::$instance == null) {
            return;
        }
        return call("GET", "projects/$project_id/repository/commits/$sha/diff");
    }

    /**
     * Get the comments of a commit in a project.
     * @param string $project_id The ID or URL-encoded path of the project.
     * @param string $sha The commit hash or name of a repository branch or tag.
     * @return mixed Response from the GitLab API
     */
    public static function get_comment_of_commit($project_id, $sha) {
        if (self::$instance == null) {
            return;
        }
        return call("GET", "projects/$project_id/repository/commits/$sha/comments");
    }

    /**
     * Adds a comment to a commit.
     * To post a comment in a particular line of a particular file, you must specify the full commit SHA, the path, the line, and line_type should be new.
     * The comment is added at the end of the last commit if at least one of the cases below is valid:
     * - the `$sha` is instead a branch or a tag and the line or path are invalid.
     * - the `$line` number is invalid (does not exist).
     * - the `$path` is invalid (does not exist).
     * In any of the above cases, the response of line, line_type and path is set to null.
     * @param string $project_id The ID or URL-encoded path of the project.
     * @param string $sha The commit SHA or name of a repository branch or tag.
     * @param string $note The text of the comment.
     * @param string|null $path The file path relative to the repository.
     * @param int|null $line The file path relative to the repository.
     * @param string|null $line_type The line type. Takes new or old as arguments
     * @return mixed Response from the GitLab API
     */
    public static function post_comment_to_commit($project_id, $sha, $note, $path = null, $line = null, $line_type = null) {
        if (self::$instance == null) {
            return;
        }
        
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
}