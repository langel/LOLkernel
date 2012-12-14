<?php defined('HOME_DIR') or die('LOLblech');

class LOL_INTERFACE_HTMLJUNK {

	function JS_attach($file,$hook='header') {
		POKE('HTMLJUNK|'.$hook.'|js_attach[]','<script src="'.$file.'"></script>');
	}

	function JS_add($code,$hook='header') {
		POKE('HTMLJUNK|'.$hook.'|js_add',PEEK('HTMLJUNK|'.$hook.'|js_add'.$code));
	}

	function CSS_attach($file,$hook='header') {
		POKE('HTMLJUNK|'.$hook.'|css_attach[]','<link href="'.$file.'" rel="stylesheet">');
	}

	function CSS_add($code,$hook='header') {
		POKE('HTMLJUNK|'.$hook.'|css_add',PEEK('HTMLJUNK|'.$hook.'|css_add'.$code));
	}

	function META_add($code) {
	}

	function Hook($hook) {
		$info = PEEK('HTMLJUNK|'.$hook);
		if (count($info['js_attach'])) foreach ($info['js_attach'] as $js) {
			$a .= $js.CR;
		}
		if ($info['js_add']) {
			$a .= '<scipt type="text/javascript">'.CR.$info['js_add'].CR.'</script>';
			// <noscript>  
			// Your browser either does not support javascript or has it disabled.  :(
			// </noscript>
		}
		if ($info['css_attach']) foreach ($info['css_attach'] as $css) {
			$a .= $css.CR;
		}
		if ($info['css_add']) {
			// XXX fill it in
		}
		return $a;
	}

	function HeaderSet($template) {
		POKE('HTMLJUNK|template_header', $template);
	}

	function HeaderGet() {
		$template = PEEK('HTMLJUNK|template_header');
		if ($template!='') {
			return LOL::Render(PEEK('HTMLJUNK|template_header'));
		}
		else return '';
	}

	function FooterSet($template) {
		POKE('HTMLJUNK|template_footer', $template);
	}

	function FooterGet() {
		$template = PEEK('HTMLJUNK|template_footer');
		if ($template!='') {
			return LOL::Render(PEEK('HTMLJUNK|template_footer'));
		}
		else return '';
	}


	function Render($cmd=NULL)	{

		#	First check this render's recursive depth . . .
		#	if too deep kill process and report an infinite loop
		$recursion_depth = LOL::INC('RenderRecursionDepth');
		if ($recursion_depth>42) {
			// XXX need to add render trail display
			throw new Exception('Render recursion depth limit of 42 exceeded. ');
			return false;
		}

		$errors = array();
/*
		#	Swizzle the RESTful command string into an array
		if ($cmd==NULL) {
			$cmd = urldecode(substr($_SERVER['REQUEST_URI'],1));
		}
*/
		poke('command_calls[]',$cmd);
		$a = explode('/',$cmd);
		$WUT = $a[0];
		if ($WUT=='') $WUT = 'index';
		$ACT = $a[1];
		if ($ACT=='') $ACT = 'Index';
		// XXX should put a loop in here to allow many, many variables
		$PARAM1 = $a[2];
		$PARAM2 = $a[3];
		$PARAM3 = $a[4];

		#	Load all extra globals set in boot.php into local code space
		$extra_globals = LOL_INTERFACE_RAMSTACK::ExtraGlobalsGet();
		if ($extra_globals) foreach ($extra_globals as $g) {
			eval('global '.$g.';');
		}

		#	Process the controller.
		$control_file = $WUT.'/'.$WUT.'.php';
		if (is_file('app/'.$control_file)) {
			include('app/'.$control_file);
		}
		else if (is_file('kernel/app/'.$control_file)) {
			include('kernel/app/'.$control_file);
		}
		else {
			// XXX want this to have an error type too
			// this would be a minor rendering or template control issue
			// throw new Exception('Unfound control file -- '.$control_file);
			$errors[] = 'Unfound control file -- '.$control_file;			
		}

		#	Process the template.
		$template = $WUT.'/'.$WUT.$ACT.'.php';
		if (is_file('app/'.$template)) {
			$template_file = 'app/'.$template;
		}
		else if (is_file('kernel/app/'.$template)) {
			$template_file = 'kernel/app/'.$template;
		}
		if ($template_file==''||$output=='') {
			// XXX want this to have an error type too
			// this would be a minor rendering or template control issue
			//throw new Exception('Unfound template file -- '.$template);
			//return false;
			$errors[] = 'No output and/or Unfound template file -- '.$template;
		}
		if ($template_file) {
			$str = file_get_contents($template_file);
			$str = str_replace('<o ','<?php echo $obj->',$str);
			$str = str_replace('<: ','<?php echo ',$str);
			$str = str_replace('<; ','<?php ',$str);		
			ob_start();
			eval(' ?>'.$str.'<?php ');
			$render = ob_get_contents();
			ob_end_clean();
		}
		if ($output!='') {
			$render = $output.$render;
		}
		
		#	...keeping track of the render depth  :D/
		LOL::DEC('RenderRecursionDepth');
		
		#	Process errors.
		if (count($errors)>=2) {
			$four_oh_four = LOL::PEEK('kernel|404');
			if ($four_oh_four!='') {
				return LOL::Render($four_oh_four);
			}
			else {
				return implode(BR,$errors);
			}
		}

		# All good, send this turkey home!
		return $render;
	}


}



?>
