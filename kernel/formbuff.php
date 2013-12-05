<?php defined('HOME_DIR') or die('LOLblech');


class LOL_FORMBUFF {


	function __construct($data = NULL) {
		// XXX if data is null fail?!?!?!
		$this->data = $data;
		$this->data['attributes']['name'] = $data['name'];
		$this->data['attributes']['method'] = 'post';
		foreach ($this->data['fields'] as $name => &$field) {
			$field['attributes']['name'] = $name;
			if (isset($_POST[$name])) {
				$field['value'] = $_POST[$name];
			}
		}
	}

	function ElementsGlobal() {
	}

	function Render() {
		$a = '<form '.LOL::Attributes($this->data['attributes']).'>';
		foreach ($this->data['fields'] as $field) {
			$a .= $this->FieldRender($field);
		}
		return $a.'</form>';
	}


	function __get($name) {
		if (is_array($this->data['fields'][$name])) {
			return $this->data['fields'][$name]['value'];
		}
		else {
			// XXX error out / field not data found
			echo 'No field data in '.$name.'.';
		}
	}

	// render a fields input html
	function FieldRender($data) {

		$a = $data['label'];
		
		switch ($data['type']) {
			case 'text' :
			case 'password' :
			case 'hidden' :
			case 'submit' :
				$data['attributes']['type'] = $data['type'];
				$a .= '<input ';
				if (isset($data['value'])) {
					$a .= 'value="'.$data['value'].'" ';
				}
				$a .= LOL::Attributes($data['attributes']).'/>';
				break;

			case 'select' :
				$a .= '<select '.LOL::Attributes($data['attributes']).'>';
				if (isset($data['options'])) {
					foreach ($data['options'] as $value => $display) {
						$a .= '<option value="'.$value.'"';
						if ($value == $data['value']) {
							$a .= ' selected="selected"';
						}
						$a .= '>'.$display.'</option>';
					}
				}
				$a .= '</select>';
				break;
		}

		return $a;

	}


}
