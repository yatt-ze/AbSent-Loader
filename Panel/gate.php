<?php
include 'include/config.php';
include 'include/rc4.php';

function decryptClientInfo($rc4Key, $info)
{
    foreach ($info as $key => $value) { if($key != "fp") $info[$key] = rc4($rc4Key, base64_decode($value)); }
    foreach ($info["fp"] as &$value) { $value = rc4($rc4Key, base64_decode($value));  }
    if ($info["check"] != "check") {return "error";} //If Check Fails Discard Client
    else { return $info; }
}
$clientInfo = decryptClientInfo($rc4Key, json_decode(base64_decode($_POST['request']), true));

print_r($clientInfo);

?>