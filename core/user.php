<?php
require_once __DIR__."/call.php";

/**
 * List all users in gitlab server.
 * Required `$super_token` in call.php
 */
function user_get_users(){
    return call("GET","users");
}

/**
 * Get information of user by GitLab user_id
 * 
 * @param string $id GitLab user id 
 * @return mixed Response from the GitLab API
 */
function user_get_user_by_id($id){
    return call("GET","users/$id");
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
function user_create_user($username, $name, $email, $password){
    $data = [
        "username"=> $username,
        "name"=> $name,
        "email"=> $email,
        "password" => $password,
        "skip_confirmation" => true
    ] ;
    return call("POST","users", $data);
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
function user_update_user($id, $username, $name, $email, $password){
    $data = [];
    if ($username == null && $name == null && $email == null && $password == null) 
    return 
"{
    'nothing_change':'Nothing to update, and request was not sent.'
}";
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
    return call("PUT","users/$id", $data);
}

