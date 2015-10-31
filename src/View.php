<?php
/**
* @author Marcelo Barbosa
*
*/
namespace Anotherwise\Bonus;

class View {
	private $compress = true;
	private $script = '';
	
	function render($template, $layout = "default"){
		$template = str_replace(".php", "", $template);
		$template = SRC."templates/$template.php";
		$layout = SRC."templates/layouts/$layout.php";
		ob_start(array($this,'compressor'));
		if(file_exists($template)){
			$this->template = $template;
			$this->flash = $this->getFlash();
			if(file_exists($layout)){
				require $layout;
			} else {
				echo "layout not found: $layout";
				require $template;
			}
		} else {
			$error = MODULES."Errors";;
			$page = new $error;
			$page->error404();
		}
		ob_end_flush();
	}
	
	function includes($file){
		$file = str_replace(".php", "", $file);
		require SRC."/templates/includes/$file.php";
	}
	
	function link($label = "Link", $dest = "", $attributes = ""){
		$dest = $this->href($dest);
		return "<a href='$dest' $attributes>$label</a>";
	}
	
	function post($label = "Link", $dest = "", $param = "id=null", $attributes = "style='display:inline;'"){
		$dest = $this->href($dest);
		$attr = explode('=', $param);
		
		$form = "<form action='$dest' method='post' $attributes>";
		$form .= "<input type='hidden' name='{$attr[0]}' value='{$attr[1]}'/>";
		$form .= "<button class='btn-link' type='submit'>$label</button>";
		$form .= "</form>";
		
		return $form;
	}
	
	function href($dest){
		return HREF.$dest;
	}
	
	function input($type, $name, $attributes = "", $options = array(), $key = array("id", "name")){
		$id = $this->getId($name);
		$value = $this->getValue($name);
		switch ($type) {
			case 'select':
			echo "<select id='$id' name='$name' $attributes>\n";
			$is_assoc = Util::is_assoc($options[0]);
			foreach ($options as $item) {
				$option = $item;
				$text = $item;
				if($is_assoc || is_object($options)){
					$option = $item[$key[0]];
					$text = $item[$key[1]];
				}
				if($option == $value || in_array($option, $value)){
					echo "<option value='$option' selected>$text</option>";
				} else {
					echo "<option value='$option'>$text</option>";
				}
			}
			echo "</select>\n";
			break;
			case 'textarea':
			echo "<textarea id='$id' name='$name' $attributes>$value</textarea>\n";
			break;
			case 'checkbox':
			echo "<input id='$id' type='$type' name='$name' value=$value $attributes/>\n";
			break;
			default:
			$value = empty($value) ? "" : "value = '$value'";
			echo "<input id='$id' type='$type' name='$name' $value $attributes/>\n";
			break;
		}
	}
	
	function getValue($name){
		$name = rtrim(str_replace(array('[',']'), '.', $name), '.');
		$keys = explode('.', $name);
		$value = @$this;
		foreach ($keys as $i) {
			$value = @$value->{$i};
		}
		return $value;
	}
	
	function getId($name){
		$name = rtrim(str_replace(array('[',']'), ' ', $name), ' ');
		return str_replace(' ', '', ucwords($name));
	}
	
	function css($resource = "default", $attributes = ""){
		$resource = str_replace(".css", "", $resource);
		$resource = WEBROOT.'css/'.$resource;
		return "<link rel='stylesheet' href='$resource.css' $attributes>\n";
	}
	
	function js($resource = "default"){
		$resource = str_replace(".js", "", $resource);
		$resource = WEBROOT.'js/'.$resource;
		return "<script src='$resource.js'></script>\n";
	}
	
	function jsBuffer($buffer){
		return $buffer;
	}
	
	function scriptStart(){
		ob_start(array($this, 'jsBuffer'));
	}
	
	function scriptEnd(){
		$this->script = ob_get_contents();
		ob_clean();
	}
	
	function script(){
		echo $this->script;
	}
	
	function setFlash($message = "", $type="info"){
		$_SESSION["flash"] = array(
			"message"=>$message,
			"class"=>$type
		);
	}
	
	function getFlash(){
		$flash = @$_SESSION["flash"];
		$_SESSION["flash"] = array(
			"message"=>"",
			"class"=>""
		);;
		return $flash;
	}
	
	function compressor($buffer){
		if($this->compress){
			$search = array(
				'/\>[^\S ]+/s', //strip whitespaces after tags, except space
				'/[^\S ]+\</s', //strip whitespaces before tags, except space
				'/(\s)+/s'  // shorten multiple whitespace sequences
			);
			$replace = array(
				'>',
				'<',
				'\\1'
			);
			$buffer = preg_replace($search, $replace, $buffer);
		}
		return $buffer;
	}
	
	function slug($string) {
		$string = strtolower($string);
		// Código ASCII das vogais
		$ascii['a'] = range(224, 230);
		$ascii['e'] = range(232, 235);
		$ascii['i'] = range(236, 239);
		$ascii['o'] = array_merge(range(242, 246), array(240, 248));
		$ascii['u'] = range(249, 252);
		// Código ASCII dos outros caracteres
		$ascii['b'] = array(223);
		$ascii['c'] = array(231);
		$ascii['d'] = array(208);
		$ascii['n'] = array(241);
		$ascii['y'] = array(253, 255);
		foreach ($ascii as $key=>$item) {
			$acentos = '';
			foreach ($item AS $codigo) $acentos .= chr($codigo);
			$troca[$key] = '/['.$acentos.']/i';
		}
		$string = preg_replace(array_values($troca), array_keys($troca), $string);
		
		// Troca tudo que não for letra ou número por um caractere ($slug)
		$string = preg_replace('/[^a-z0-9]/i', $slug, $string);
		// Tira os caracteres ($slug) repetidos
		$string = preg_replace('/' . $slug . '{2,}/i', $slug, $string);
		$string = trim($string, $slug);
		
		return $string;
	}
}
