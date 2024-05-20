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
 * Get ifomation of user by gitlab user_id
 * 
 * @param string $id GitLab user id 
 */
function user_get_user_by_id($id){
    return call("GET","users/$id");
}

/**
 * Create an user
 * You should only save "id" field in function response to moodle database (into GitLabAccount table)
 * 
 * @param string $username Username
 * @param string $name Full name of user
 * @param string $email Email
 * @param string $password Password
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

function user_update_user($id, $username, $name, $email, $password){
    $data = [];
    if ($username == null && $name == null && $email == null && $password == null) return;
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
