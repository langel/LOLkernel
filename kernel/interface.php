<?php

class LOL	{

	function ParseCommand($cmd)	{
		//poke('comand_calls[]',$cmd);
		return explode('/',$cmd);
	}

	function Render($cmd)	{
		
		$extra_globals = LOL_INTERFACE_RAMSTACK::ExtraGlobalsGet();
		foreach ($extra_globals as $g) {
			eval('global '.$g.';');
		}

		$a = LOL::ParseCommand($cmd);

		$WUT = $a[0];
		if ($WUT=='') $WUT = 'index';
		$ACT = $a[1];
		if ($ACT=='') $ACT = 'Index';
		// XXX should put a loop in here to allow many, many variables
		$VAR1 = $a[2];
		$VAR2 = $a[3];
		$VAR3 = $a[4];

		$control_file = $WUT.'/'.$WUT.'.php';

		if (is_file('app/'.$control_file)) {
			include('app/'.$control_file);
		}
		else if (is_file('kernel/wuts/'.$control_file)) {
			include('kernel/wuts/'.$control_file);
		}
		else {
			// XXX want this to have an error type too
			// this would be a minor rendering or template control issue
			throw new Exception('Unfound control file -- '.$control_file);
		}

		$template = $WUT.'/'.$WUT.$ACT.'.php';
		if (is_file('app/'.$template)) {
			$template_file = 'app/'.$template;
		}
		else if (is_file('kernel/wuts/'.$template)) {
			$template_file = 'kernel/wuts/'.$template;
		}
		if (!$template_file) {
			// XXX want this to have an error type too
			// this would be a minor rendering or template control issue
			throw new Exception('Unfound template file -- '.$template);
			return false;
		}
		
		$str = file_get_contents($template_file);
		$str = str_replace('<o ','<?php echo $obj->',$str);
		$str = str_replace('<: ','<?php echo ',$str);
		$str = str_replace('<; ','<?php ',$str);
				
		ob_start();
		eval(' ?>'.$str.'<?php ');
		$render = ob_get_contents();
		ob_end_clean();
		return $render;

	}

	function HeaderSet($template) { return LOL_INTERFACE_HTMLJUNK::HeaderSet($template); }
	function HeaderGet() { return LOL_INTERFACE_HTMLJUNK::HeaderGet(); }
	function FooterSet($template) { return LOL_INTERFACE_HTMLJUNK::FooterSet($template); }
	function FooterGet() { return LOL_INTERFACE_HTMLJUNK::FooterGet(); }

}

?>
