<?php
namespace Polidog\Console\Command;

class Help extends CommandAbstract {
	
	/**
	 * テスト用のコマンド
	 */
	public function commandHallo() {
		$this->output('Hallo world');
	}
}