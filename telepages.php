<?php

require_once "spyc.php";

class TelePages {
	private $data = ['bookmark' => [], 'hierarchy' => []];
	
    function TelePages() {
  		$this->data = Spyc::YAMLLoad('pages.yaml');
    }
    
    function Save() {
		$yamldata = Spyc::YAMLDump($this->data, false, false, true);
   		$handle = fopen("pages.yaml", "w");
   		fwrite($handle, $yamldata);
   		fclose($handle);
   		return True;
    }
    
    function GetBookmarks() {
    	$html = '';
		foreach ($this->data['bookmark'] as $key => $line) {
			$html = $html."\t<li><a id=".$key." href='".$line[1]."'>".$line[0]."</a></li>\n";
		}
		
		return $html;
    }
    
    function AddBookmark($name, $url, $position) {
    	$array = [$name, $url, $position];
    	$this->data['bookmark'][] = $array;
		$this->Save();
		return True;
    }
    
    function ModifyBookmark($index, $name='', $url='', $position='') {
    	if ($name != '')
    		$this->data['bookmark'][$index][0] = $name;
    	if ($url != '')
    		$this->data['bookmark'][$index][1] = $url;
		if ($position != '')
    		$this->data['bookmark'][$index][2] = $position;
		$this->Save();
    	return True;
    }
    
    function RemoveBookmark($index) {
    	unset($this->data['bookmark'][$index]);
		$this->Save();
    	return True;
    }
}

?>

