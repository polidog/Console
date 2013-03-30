<?php
namespace Polidog\Console\Command;
use Polidog\Console\Command\CommandAbstract;

/**
 * 文字列操作コマンド
 */
class String extends CommandAbstract{
	
	/**
	 * 指定した文字列をbase64エンコードする
	 * @param array $args
	 */
	public function commandBase64($args) {
		if (!isset($args[0]) || empty($args[0]) ) {
			$this->error('string not found');
			return;
		}
		$this->output(base64_encode($args[0]));
	}
	
}