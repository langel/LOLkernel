<?php

class LOL_INTERFACE_HTMLJUNK {

	function JS_attach($file,$hook='header') {
	
	}

	function JS_add($code,$hook='header') {
		POKE('HTMLJUNK|'.$hook.'|js_add',$code);
	}

	function CSS_attach($file) {
	}

	function CSS_add($code) {
	}

	function META_add($code) {
	}

	function Hook($hook) {
		$info = PEEK('HTMLJUNK|'.$hook);
		if ($info['js_attach']) {
			$a .= $info['js_attach'];
		}
		if ($info['js_add']) {
			$a .= '<scipt type="text/javascript">'.CR.$info['js_add'].CR.'</script>';
			// <noscript>  
			// Your browser either does not support javascript or has it disabled.  :(
			// </noscript>
		}
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

}



?>
