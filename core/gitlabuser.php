<?php
require_once __DIR__ . "/call.php";

class GitLabUser {
    public static $instance = null;

    public function getInstance() {
        if (is_null($this->instance)) {
            $this->instance = new GitLabUser();
        }

        return $this->instance;
    }

    private function __construct() {
        // Empty constructor
    }

    /**
     * List all users in gitlab server.
     * Required `$super_token` in call.php
     */
    public static function get_users() {
        if (is_null(self::$instance)) {
            return;
        }
        return call("GET", "users");
    }

    /**
     * Get information of user by GitLab id
     * 
     * @param string $id GitLab user id 
     * @return mixed Response from the GitLab API
     */
    public static function get_by_id($id) {
        if (is_null(self::$instance)) {
            return;
        }
        return call("GET", "users/$id");
    }

    /**
     * Create a user
     * You should only save the "id" field in the function response to the Moodle database (into GitLabAccount table)
     * 
     * @param string $username Username
     * @param string $name Full name of user
     * @param string $email Email
     * @param string $password Password
     * @return mixed Response from the GitLab API
     */
    public static function create_user($username, $name, $email, $password) {
        if (is_null(self::$instance)) {
            return;
        }

        if (empty($username) || empty($name) || empty($email) || empty($password)) {
            return json_encode(array("error" => "Required all parameters"));
        }
        $data = [
            "username" => $username,
            "name" => $name,
            "email" => $email,
            "password" => $password,
            "skip_confirmation" => true
        ];
        return call("POST", "users", $data);
    }

    /**
     * Update a user
     * 
     * @param string $id GitLab user id
     * @param string|null $username Username (optional)
     * @param string|null $name Full name of user (optional)
     * @param string|null $email Email (optional)
     * @param string|null $password Password (optional)
     * @return mixed Response from the GitLab API
     */
    public static function update_user($id, $username = null, $name = null, $email = null, $password = null) {
        if (is_null(self::$instance)) {
            return;
        }
        $data = [];
        if ($username == null && $name == null && $email == null && $password == null) {
            return json_encode(["error" => "Nothing to update, and request was not sent."]);
        }
        if ($username != null) {
            $data["username"] = $username;
        }
        if ($name != null) {
            $data["name"] = $name;
        }
        if ($email != null) {
            $data["email"] = $email;
        }
        if ($password != null) {
            $data["password"] = $password;
        }
        return call("PUT", "users/$id", $data);
    }

    /**
     * Find users by username or email containing specific strings
     * 
     * @param string|null $string_in_username Substring to search for in the GitLab username (optional) 
     * @param string|null $string_in_email Substring to search for in the user's email (optional) ||
     * `$string_in_email` must be `<search_string>@<mail_domain>`, `<search_string>` can be empty string and `<mail_domain>` must exactly
     * @return mixed Response from the GitLab API or a message if no parameters are provided
     */
    public static function find_users($string_in_username = null, $string_in_email = null) {
        if (is_null(self::$instance)) {
            return;
        }
        if (empty($string_in_email) && empty($string_in_username)) {
            return json_encode(["error" => "No information provided to search."]);
        }

        if (!empty($string_in_username) && !empty($string_in_email)) {
            return json_encode(["error" => "Because GitLab don't support searching both username and email, please provide only one search parameter: username or email."]);
        }

        $params = [];
        if ($string_in_username !== null) {
            $params['search'] = $string_in_username;
        } elseif ($string_in_email !== null) {
            $params['search'] = $string_in_email;
        }

        $query_string = http_build_query($params);

        return call("GET", "users?$query_string");
    }
}