<?php
namespace Polidog\Console\Command;

class Help extends CommandAbstract {
	
	public function main($args,$action) {
		$this->output("command not found:".$action);
	}
}