<?php
namespace Polidog\Console\Command;
use Polidog\Console\Command\CommandAbstract;

/**
 * 文字列操作コマンド
 */
class String extends CommandAbstract{
	
	/**
	 * string　指定した文字列をbase64エンコードする
	 * @param array $args
	 */
	public function commandBase64($args) {
		$string = $this->getTargetArg($args);
		$this->output(base64_encode($string));
	}
	
	/**
	 * string　URLエンコードをする
	 * @param array $args
	 */
	public function commandUrlencode($args) {
		$string = $this->getTargetArg($args);
		$this->output(urlencode($string));
	}
	
	/**
	 * string　URLデコードする
	 * @param array $args
	 */
	public function commandUrldecode($args) {
		$string = $this->getTargetArg($args);
		$this->output(urldecode($string));
	}
	
	/**
	 * string 　指定したシリアライズされた配列を普通の配列に戻して出力する
	 * @param type $args
	 */
	public function commandUnserialize($args) {
		$sreialize = $this->getTargetArg($args);
		$array = unserialize($sreialize);
		print_r($array);
	}
	
	/**
	 * string　配列なシンタックスの文字列をシリアライズした値に変更する
	 * @param type $args
	 */
	public function commandSerialize($args) {
		$array = $this->getTargetArg($args);
		eval('$array = '.$array.";");
		$serialize = serialize($array);
		echo $serialize."\n";
	}

	
	
	private function getTargetArg($args) {
		if (!isset($args[0]) || empty($args[0]) ) {
			$this->error('string not found');
		}
		return $args[0];
	}
	
	
	
}