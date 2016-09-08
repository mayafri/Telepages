<?php

require_once "spyc.php";

class TelePages {
	private $data = ['bookmark' => [], 'hierarchy' => []];
	
    function TelePages() {
		$this->Load();
    }
    
    function Load() {
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
    	$this->Load();
    	$html = '';
		foreach ($this->data['bookmark'] as $key => $line) {
			$html = $html.$line[0]."\x1F".$line[1]."\x1F".$line[2]."\x1F".$key."\x1E";
		}
		
		return $html;
    }
    
    function AddBookmark($name, $url, $position) {
    	$array = [$name, $url, $position];
    	$this->data['bookmark'][] = $array;
		$this->Save();
		end($this->data['bookmark']);
		return key($this->data['bookmark']);
    }
    
    function ModifyBookmark($name='', $url='', $position='', $index) {
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

