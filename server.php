#!/usr/bin/env php
<?php

require_once('./websockets.php');
require_once('./telepages.php');

class Server extends WebSocketServer {
	//protected $maxBufferSize = 1048576; //1MB... overkill for an echo server, but potentially plausible for other applications.

	public $pages;
	
	function Server($addr, $port) {
		parent::__construct($addr, $port);
		$this->pages = new TelePages();
	}
	
	protected function process ($user, $message) {
		$command = explode("\x1F", $message);
		
		if($command[0] == 'GetBookmarks') {
			$return = $this->pages->GetBookmarks();
		}
		
		elseif($command[0] == 'AddBookmark') {
			$return = $this->pages->AddBookmark($command[1], $command[2], '');
		}
		
		elseif($command[0] == 'ModifyBookmark') {
			$return = $this->pages->AddBookmark($command[1], $command[2], $command[3], '');
		}
		
		elseif($command[0] == 'RemoveBookmark') {
			$return = $this->pages->RemoveBookmark($command[1]);
		}
		
		$this->send($user,$message."\x1E".$return);
	}

	protected function connected ($user) {
		// Do nothing: This is just an echo server, there's no need to track the user.
		// However, if we did care about the users, we would probably have a cookie to
		// parse at this step, would be looking them up in permanent storage, etc.
	}

	protected function closed ($user) {
		// Do nothing: This is where cleanup would go, in case the user had any sort of
		// open files or other objects associated with them.  This runs after the socket 
		// has been closed, so there is no need to clean up the socket itself here.
	}
}

$server = new Server("0.0.0.0","9000");

try {
	$server->run();
}
catch (Exception $e) {
	$server->stdout($e->getMessage());
}

?>

