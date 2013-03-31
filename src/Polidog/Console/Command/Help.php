<?php
namespace Polidog\Console\Command;

class Help extends CommandAbstract {
	
	public function main($params,$action) {
		$this->output("command not found:".$action);
	}
}