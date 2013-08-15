<?php defined('HOME_DIR') or die('LOLblech');


// this file should probably get rolled into other ones.... :X

class LOL_INTERFACE_SYSTUF {


	function ScriptFind($what,$act='',$suffix='.php') {
		$script = $what.'/'.$what.$act.$suffix;
		if (is_file('app/'.$script)) {
			return 'app/'.$script;
		}
		else if (is_file('kernel/app/'.$script)) {
			return 'kernel/app/'.$script;
		}
		else return FALSE;
	}


}


/* 

	url and header tools?

*/

/*
		Character Encoding Chart

		To help promote the cause of Web Standards and adhering to specifications, here is a quick reference chart explaining which characters are “safe” and which characters should be encoded in URLs.
		Classification 	Included characters 	Encoding required?
		Safe characters 	Alphanumerics [0-9a-zA-Z], special characters $-_.+!*'(), and reserved characters used for their reserved purposes (e.g., question mark used to denote a query string) 	NO
		ASCII Control characters 	Includes the ISO-8859-1 (ISO-Latin) character ranges 00-1F hex (0-31 decimal) and 7F (127 decimal.) 	YES
		Non-ASCII characters 	Includes the entire “top half” of the ISO-Latin set 80-FF hex (128-255 decimal.) 	YES
		Reserved characters 	$ & + , / : ; = ? @ (not including blank space) 	YES*
		Unsafe characters 	Includes the blank/empty space and " < > # % { } | \ ^ ~ [ ] ` 	YES

		* Note: Reserved characters only need encoding when not used for their defined, reserved purposes.
		http://perishablepress.com/stop-using-unsafe-characters-in-urls/
*/
?>
