#pragma once

namespace absent
{
	namespace tasks
	{
		bool update(std::string url)
		{
			if (!absent::tasks::download_execute(url)) { return false; }
			if (!absent::tasks::uninstall()) { return false; }
			return true;
		}
	}
}