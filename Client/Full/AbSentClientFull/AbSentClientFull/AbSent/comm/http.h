#pragma once
#pragma comment(lib, "Ws2_32.lib")
#pragma comment( lib, "wininet.lib")

#define WIN32_LEAN_AND_MEAN

#include <urlmon.h>
#include <sstream>
#include <string>
#include <vector>
#include <wininet.h>

#define BUFFERSIZE 1024

namespace absent
{
	namespace http
	{
		int port = 80;

		std::string post(std::string host, std::string path, std::string data)
		{
			char buffer[BUFFERSIZE];
			struct sockaddr_in serveraddr;
			int sock;
			WSADATA wsaData;
			std::stringstream ss; ss << data.length();
			
			std::stringstream request2;
			request2 << "POST " << path << " HTTP/1.1" << "\r\n";
			request2 << "User-Agent: Absent" << "\r\n";
			request2 << "Host: " << host << "\r\n";
			request2 << "Content-Length: " << data.length() << "\r\n";
			request2 << "Content-Type: application/x-www-form-urlencoded" << "\r\n";
			request2 << "Accept-Language: en-au" << "\r\n" << "\r\n";
			request2 << data;
			std::string request = request2.str();

			/*std::cout << request << std::endl << std::endl;*/
			
			if (WSAStartup(MAKEWORD(2, 0), &wsaData) != 0) return "RQF";
			if ((sock = socket(PF_INET, SOCK_STREAM, IPPROTO_TCP)) < 0) return "RQF";
			hostent * record = gethostbyname(host.c_str());
			if (record == NULL) return "RQF";
			in_addr * address = (in_addr *)record->h_addr;
			std::string ipaddress = inet_ntoa(*address);
			memset(&serveraddr, 0, sizeof(serveraddr));
			serveraddr.sin_family = AF_INET;
			serveraddr.sin_addr.s_addr = inet_addr(ipaddress.c_str());
			serveraddr.sin_port = htons((unsigned short)port);
			if (connect(sock, (struct sockaddr *) &serveraddr, sizeof(serveraddr)) < 0) return "RQF";
			if (send(sock, request.c_str(), request.length(), 0) != request.length()) return "RQF";
			std::string response = "";
			int resp_leng = BUFFERSIZE;
			while (resp_leng == BUFFERSIZE)
			{
				resp_leng = recv(sock, (char*)&buffer, BUFFERSIZE, 0);
				if (resp_leng>0) response += std::string(buffer).substr(0, resp_leng);
			}
			closesocket(sock);
			WSACleanup();

			return response.substr(response.find("\r\n\r\n")+4);
		}

		int downloadFile(std::string url, std::string filename)
		{
			HINTERNET hInternet;
			hInternet = InternetOpenA(("Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/47.0.2526.111 Safari/537.36"), INTERNET_OPEN_TYPE_DIRECT, NULL, NULL, NULL);
			DWORD dwSize;
			DWORD written;
			char szHead[15];
			BYTE * szTemp[1024];
			HINTERNET  hConnect;
			HANDLE pFile = CreateFileA(filename.c_str(), GENERIC_WRITE, 0, NULL, CREATE_ALWAYS, FILE_ATTRIBUTE_NORMAL, NULL);
			szHead[0] = '\0';
			if (!(hConnect = InternetOpenUrlA(hInternet, url.c_str(), szHead, 15, INTERNET_FLAG_DONT_CACHE, 0))) { return 0; }
			do
			{
				if (!InternetReadFile(hConnect, szTemp, 1024, &dwSize))
				{
					CloseHandle(pFile);
					return 0;
				}
				if (!dwSize) break;
				else if (!WriteFile(pFile, szTemp, dwSize, &written, NULL));
			} while (TRUE);
			CloseHandle(pFile);
			InternetCloseHandle(hInternet);
			return 1;
		}
	}
}