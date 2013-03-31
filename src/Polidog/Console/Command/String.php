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
		$string = $this->getTargetArg($args);
		$this->output(base64_encode($string));
	}
	
	/**
	 * URLエンコードをする
	 * @param array $args
	 */
	public function commandUrlencode($args) {
		$string = $this->getTargetArg($args);
		$this->output(urlencode($string));
	}
	
	/**
	 * URLデコードする
	 * @param array $args
	 */
	public function commandUrldecode($args) {
		$string = $this->getTargetArg($args);
		$this->output(urldecode($string));
	}
	
	
	private function getTargetArg($args) {
		if (!isset($args[0]) || empty($args[0]) ) {
			$this->error('string not found');
		}
		return $args[0];
	}
	
	
	
}