<?php
require_once __DIR__."/../tools/jsonstring.php";
require_once __DIR__."/../tools/log.php";
$debug_mode=true;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GitLab APIs</title>
    <style>
        h5 {
            margin-top: 0px;
            margin-bottom: 0px;
        }
    </style>
</head>
<?php
    $command = "";
    $response = "";
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST["gitlab_url"]) && $_POST["gitlab_url"] != "") {
            $gitlab_url = $_POST["gitlab_url"];
        }
        if (isset($_POST["token"]) && $_POST["token"] != "") {
            $super_token = $_POST["token"];
        }
        if (isset($_POST["route"])){
            $route_group = $_POST["route"];
            $route_group_name = $route_group."_route";
            $function_name = $_POST[$route_group_name];
            foreach ($logs as $index => $log) {
                if ($log->function_name == $function_name && $log->class_name == $route_group) {
                    foreach ($_POST as $key => $value) {
                        $log->load_params($key, $value);
                    }
                    $function = $log->get_located();
                    $response = $log->run();
                    break;
                }  
            }
        }
    }
?>
<body>
    <form method="post">
        <label for="gitlab-url">Your GitLab server URL:</label>
        <input type="text" id="gitlab-url" name="gitlab_url">
        <br>
        <label for="token">Your Token: </label>
        <input type="password" id="token" name="token">
        <hr>
        <label for="route">Route group (Class):</label>
        <select id="route" name="route">
            <option value="nochoise">Choose a class</option>
            <option value="GitLabUserApi">GitLabUserApi</option>
            <option value="GitLabRepositoryApi">GitLabRepo</option>
            <option value="GitLabProjectApi">GitLabProject</option>
            <option value="GitLabBranchApi">GitLabBranch</option>
            <option value="GitLabCommitApi">GitLabCommit</option>
            <option value="GitLabRepositoryFileApi">GitLabRepositoryFile </option>
        </select>
        <hr>
        <label for="c-routes">Choose a route: </label>
        <select id="c-routes">
        </select>
        <hr>
        <h5>Params:</h5>
        <div id="params"></div>
        <hr>
        <button type="submit">Submit</button>
    </form>
    <h5>Function:</h5>
    <textarea style="width: 500px; height: 50px" disabled><?=$function?></textarea>
    <?php if ($debug_mode): ?>
    <h5>Ajax response:</h5>
    <textarea id="ajax" style="width: 500px; height: 50px" disabled></textarea>
    <?php endif;?>
    <h5>Response:</h5>
    <textarea style="width: 100%; height: 500px; overflow-x: auto; overflow-y: auto" disabled><?=pretty($response)?></textarea>
    <h5>No pretty response:</h5>
    <textarea style="width: 100%; height: 500px; overflow-x: auto; overflow-y: auto" disabled><?=$response?></textarea>
    <script>
        param_paths = [];
        const route_selecter =document.getElementById("route");
        route_selecter.addEventListener('change',async function(){
            param_paths=[];
            const select_value = this.value;
            <?php if ($debug_mode):?>
            const ajax_content =document.getElementById("ajax");
            ajax_content.innerHTML='Loading routes in "' + select_value + '" group';
            <?php endif; ?>
            const c_routes =document.getElementById("c-routes");
            c_routes.setAttribute("name", this.value+"_route");
            c_routes.innerHTML = "";

            const response = await fetch (
                "http://localhost/apisdevtools/html/routes.ajax.php?group="+select_value
            )

            responsetext = await response.text();
            <?php if ($debug_mode): ?>
            ajax_content.innerHTML =responsetext;
            <?php endif; ?>
            responsetext.split('#').forEach(element => {
                if (element != ""){
                    const paths = element.split("||");
                    const newOption = document.createElement('option');
                    newOption.value = paths[0];
                    newOption.textContent = paths[1];
                    c_routes.appendChild(newOption);
                    if (paths[3] != ""){
                        param_paths[paths[0]] = paths[2].split(";");
                    }
                }
            });
            const param_div =document.getElementById("params");
            param_div.innerHTML="";
            if (responsetext!="")
            param_paths[responsetext.split("#")[1].split("||")[0]].forEach(element =>{
                if (element!=""){
                    const newLabel =document.createElement('label');
                    newLabel.setAttribute("for", element.split(":")[0]+"_param");
                    newLabel.textContent = "$"+element.split(":")[0];
                    const newInput =document.createElement("input");
                    newInput.setAttribute('id', element.split(":")[0]+"_param");
                    newInput.setAttribute('name', element.split(":")[0]);
                    newInput.setAttribute('type', element.split(":")[1]);
                    const newDetail =document.createElement("button");
                    newDetail.setAttribute("title", element.split(":")[2]);
                    newDetail.disabled=true;
                    newDetail.textContent="?";
                    newDetail.setAttribute("style", "border-radius: 50%;")
                    param_div.appendChild(newLabel);
                    param_div.appendChild(newInput);
                    param_div.appendChild(newDetail);
                    const br =document.createElement("br");
                    param_div.appendChild(br);
                }
            })
        })

        const cs_routes=document.getElementById("c-routes");
        cs_routes.addEventListener('change', function(){
            const param_div =document.getElementById("params");
            param_div.innerHTML="";
            param_paths[this.value].forEach(element => {
                if (element!=""){
                    const newLabel =document.createElement('label');
                    newLabel.setAttribute("for", element.split(":")[0]+"_param");
                    newLabel.textContent = "$"+element.split(":")[0];
                    const newInput =document.createElement("input");
                    newInput.setAttribute('id', element.split(":")[0]+"_param");
                    newInput.setAttribute('name', element.split(":")[0]);
                    newInput.setAttribute('type', element.split(":")[1]);
                    const newDetail =document.createElement("button");
                    newDetail.setAttribute("title", element.split(":")[2]);
                    newDetail.disabled=true;
                    newDetail.textContent="?";
                    newDetail.setAttribute("style", "border-radius: 50%;")
                    param_div.appendChild(newLabel);
                    param_div.appendChild(newInput);
                    param_div.appendChild(newDetail);
                    const br =document.createElement("br");
                    param_div.appendChild(br);
                }
            });
        })
    </script>
</body>
</html>