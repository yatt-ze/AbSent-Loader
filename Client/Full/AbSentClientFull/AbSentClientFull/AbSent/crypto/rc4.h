//
//  rc4.h
//  rc4
//
//  Created by Duye Chen on 4/8/15.
//

#include <iostream>


#ifndef rc4_rc4_h
#define rc4_rc4_h

#define BUFFER 256

namespace absent
{
	namespace crypto
	{
		class RC4
		{
		public:
			std::string crypt(std::string in, std::string key)
			{
				int key_size = (int)key.size();
				int str_size = (int)in.size();

				// Create Key Stream
				ksa(key, key_size);
				// Encrypt or Decrypt input (plaintext)
				return prga(in, str_size);
			}

		private:
			int S[BUFFER];
			char k[BUFFER];
			void ksa(std::string key, int keylength)
			{
				for (int i = 0; i < BUFFER; i++)
				{
					S[i] = i;
				}

				int j = 0, temp;

				for (int i = 0; i < BUFFER; i++)
				{
					j = (j + S[i] + key[i % keylength]) % BUFFER;
					temp = S[i];
					S[i] = S[j];
					S[j] = temp;
				}
			}
			std::string prga(std::string in, int len)
			{
				int i = 0, j = 0, x = 0, temp;
				for (x = 0; x < len; x++)
				{
					i = (i + 1) % BUFFER;
					j = (j + S[i]) % BUFFER;
					temp = S[i];
					S[i] = S[j];
					S[j] = temp;
					k[x] = in[x] ^ S[(S[i] + S[j]) % BUFFER];
				}
				k[x] = '\0';
				return k;
			}
		};
	}
}

#endif