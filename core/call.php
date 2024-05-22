<?php
/**
 * The URL of your GitLab server.
 * Change this to the URL of your GitLab server.
 *
 * @var string
 */
$gitlab_url = "http://gitlab.localhost:2050";

/**
 * The personal access token of a super admin account on your GitLab server.
 * Change this to the personal access token of a super admin account on your GitLab server.
 *
 * @var string
 */
require_once "../constant.php";

/**
 * Debug mode
 * 
 * @var string
 */
$debug_mode = false;

/**
 * Perform a request to the GitLab API. 
 * You must correct your `$gitlab_url` and `$super_token` before using this function.
 * Setting `$debug_mode=true` to echo request information.
 *
 * @param string $method HTTP method (GET, POST, PUT, DELETE)
 * @param string $endpoint API endpoint
 * @param mixed $data Data to be sent with the request (optional)
 * @param string|null $token Access token (optional)
 * @return mixed Response from the GitLab API
 */
function call($method, $endpoint, $data = null, $is_form_data = false, $token = null, $version = "v4"){
    global $gitlab_url, $super_token;

    $access_token = ($token != null) ? $token : $super_token;
    $url = "$gitlab_url/api/$version/$endpoint";

    $ch = curl_init($url);

    $header = [
        "PRIVATE-TOKEN: $access_token"
    ];

    if (!$is_form_data) {
        array_push($header, "Content-Type: application/json");
    }

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

    switch ($method) {
        case 'GET':
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
            break;
        case 'POST':
            curl_setopt($ch, CURLOPT_POST, true);
            if ($is_form_data) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            } else {
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            }
            break;
        case 'PUT':
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            break;
        case 'DELETE':
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
            break;
        default:
            curl_close($ch);
            return '[{"error":"Method not supported", "function":"core.call()"}]';
    }

    $response = curl_exec($ch);
    global $debug_mode;
    if ($debug_mode){
        require_once __DIR__."/../tools/jsonstring.php";
        echo "<hr> <h5>Call function:</h5> <h5>Url: $url</h5> <h5>Status Code: ";
        echo curl_getinfo($ch, CURLINFO_HTTP_CODE);
        echo "</h5> <h5>Function: /core/call.php --> call();</h5> <h5>Params: \$method=\"$method\" | \$endpoint=\"$endpoint\" | \$token=\"$access_token\" </h5>";
        echo "<h5> Data:</h5>";
        var_dump($data);
        echo "<hr>";
        curl_close($ch);
    }
    

    return $response;
}