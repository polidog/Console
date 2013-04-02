<?php
use Polidog\Console\Command\CommandAbstract;
class Test extends CommandAbstract
{
	public function main($params,$action) {
		echo "Hello extend command\n";
	}
}