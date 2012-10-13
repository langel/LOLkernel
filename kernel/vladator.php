<?php defined('HOME_DIR') or die('LOLblech');

/*

	VLAD the DICTATOR

	a value validator

*/


class LOL_INTERFACE_VLADATOR	{


	function Validate($value,$rules) {

		if (!is_array($rules)) $rules = array($rules);

		foreach ($rules as $rule) {
			// check for rule method
			// use rule method
		}

	}


}


?>
