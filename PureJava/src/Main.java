import java.io.BufferedReader;
import java.io.FileInputStream;
import java.io.FileNotFoundException;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.io.PushbackInputStream;
import java.nio.charset.Charset;


public class Main {

	/**
	 * @param args
	 * @throws IOException 
	 */
	public static void main(String[] args) throws IOException {
		InputStream fstream = new FileInputStream("d:\\jura.txt");
		PushbackInputStream pushback = new PushbackInputStream(fstream, 4);
		/*
		BufferedReader reader = new BufferedReader(new InputStreamReader(
				pushback, Charset.forName("UTF8")));
		*/
		System.out.println(isNotUTF8(pushback));

	}

	/*sfsd
	 * sdfsd
	 */
	private static boolean isNotUTF8(PushbackInputStream pushback) throws IOException {
		int i;
		int previous;
		while ((i = pushback.read()) != -1){
			System.out.println(Integer.toHexString(i));
			
			//one byte character
			if((i & 0x80) == 0x00) //i & 10000000 == 0xxxxxxx
				continue;
			
			//two byte character
			if((i & 0xE0) == 0xC0){ //i & 11100000 == 110xxxxx
				if((i & 0xFE) == 0xC0) //overlong endoding for 2 byte character: 1100000x (10xxxxxx) 
					return true;
				if ((pushback.read() & 0xC0) == 0x80){ //i & 11000000 == 10xxxxx
					continue;
				}
				return true;
			}
			
			//three byte character
			if((i & 0xF0) == 0xE0){	//i & 11110000 == 1110xxxx
				previous = i;
				if (((i = pushback.read()) & 0xC0) == 0x80){ //i & 11000000 == 10xxxxx
					//overlong endoding for 3 byte character: 11100000 100xxxxx (10xxxxxx)
					if(previous == 0xE0 && (i & 0xE0) == 0x80)
						return true;
					if ((pushback.read() & 0xC0) == 0x80){ //i & 11000000 == 10xxxxx
						continue;
					}
				}
				return true;
			}
			
			//four byte character
			if((i & 0xF8) == 0xF0){	//i & 11111000 == 11110xxx
				previous = i;
				if (((i = pushback.read()) & 0xC0) == 0x80){ //i & 11000000 == 10xxxxx
					//overlong endoding for 4 byte character: 11110000 1000xxxx (10xxxxxx 10xxxxxx)
					if(previous == 0xF0 && (i & 0xF0) == 0x80)
						return true;
					if ((pushback.read() & 0xC0) == 0x80){ //i & 11000000 == 10xxxxx
						if ((pushback.read() & 0xC0) == 0x80){ //i & 11000000 == 10xxxxx
							continue;
						}
					}
				}
				return true;
			}
			return true;
		}
		return false;
	}
}


