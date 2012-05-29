<?php

function __autoload($wut) {

	$model_file = $wut.'/'.$wut.'_class.php';

	if (is_file('app/'.$model_file)) {
		require_once('app/'.$model_file);
	}
	elseif (is_file('kernel/wuts/'.$model_file)) {
		require_once('kernel/wuts/'.$model_file);
	}
	else {
	// XXX	should we check if there is an existing table first?
	//		could keep table schema in disk directory
		eval("class {$wut} extends FOHAT {
			function __construct() {
			  \$this->table_name = '{$wut}';
			}

			function Pop(\$id) {
			  \$a = new {$wut};
			  \$a->Load(\$id);
			  return \$a;
			}
		}");
	}
}

?>
