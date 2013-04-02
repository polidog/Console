<?php

namespace Polidog\Console\Command;

use Polidog\Console\Document\MethodDocument;

/**
 * コマンド抽象化
 * @author polidog <polidogs@gmail.com>
 * @property $methodDocument Polidog\Console\Document\MethodDocument
 */
abstract class CommandAbstract {

	protected $isErrorExcption = false;
	protected $actionMehotdPrefix = 'command';
	protected $methodDocument;
	protected $options = array();

	/**
	 * コンストラクタ
	 * @param type $options
	 */
	public function __construct($options = array()) {
		$this->options = $options;
		$this->methodDocument = new MethodDocument($this);
	}
	
	/**
	 * メインメソッド
	 * @param array $params
	 * @param string $action
	 */
	public function main($params,$action) {
		$this->__commandList();
	}
	
	/**
	 * アクション実行メソッド
	 * @param string $action 
	 * @param array $params 
	 */
	final public function execute($action, $params = array()) {
		$this->preFilter($action,$params);
		$action = "command" . ucfirst($action);
		if (is_callable(array($this, $action))) {
			$return = $this->$action($params,$action);
		} else {
			$return = $this->main($params,$action);
		}
		$this->postFilter($action,$return,$params);
	}

	/**
	 * executeメソッド実行前フィルタ
	 * @param string $action
	 * @param array $params
	 */
	protected function preFilter($action,$params) {
		
	}
	
	/**
	 * executeメソッド実行後フィルタ
	 * @param string $action
	 * @param mixed $return
	 * @param array $params
	 */
	protected function postFilter($action,$return,$params) {
		
	}
	
	/**
	 * 実行するクラスのprefix
	 * @return string
	 */
	public function getActionMethodPrefix() {
		return $this->actionMehotdPrefix;
	}
	
	/**
	 * 実行するクラスprefixをセットする
	 * @param type $prefix
	 * @return \Polidog\Console\Command\CommandAbstract
	 */
	public function setActionMethodPrefix($prefix) {
		$this->actionMehotdPrefix = $prefix;
		return $this;
	}
	
	/**
	 * コマンドの一覧を表示させる
	 */
	protected function __commandList() {
		$this->output($this->methodDocument);
	}	
	
	/**
	 * アクションメソッドかどうかの判定
	 * @param string $methodName
	 * @return boolean
	 */
	protected function isActionMethod($methodName) {
		return (substr($methodName, 0, 7) === $this->actionMehotdPrefix);
	}

	/**
	 * input処理の実装
	 * @param string $exitCommand
	 * @return string
	 */
	protected function input($callback = null, $exitCommand = 'exit') {
		while (true) {
			$input = trim(fgets(STDIN, 10));
			if ($input == $exitCommand) {
				return;
			}
			if ($callback) {
				if ($callback($input)) {
					break;
				}
			} else {
				if (!empty($input)) {
					break;
				}
			}
		}
		return $input;
	}

	/**
	 * メッセージを出力する
	 * @param string $message
	 * @param string $prefix
	 * @param boolean $ln
	 */
	protected function output($message, $prefix = null, $ln = true) {

		if (is_array($message)) {
			foreach ($message as $m) {
				$this->output($m, $prefix, $ln);
			}
		} else {

			if ($prefix) {
				echo "[$prefix]";
			}
			echo $message;

			if ($ln) {
				echo "\n";
			}
		}
	}

	/**
	 * エラー処理
	 * @param string $message
	 */
	protected function error($message) {
		if ($this->isErrorExcption) {
			throw new Polidog\Console\CommandException($message);
		} else {
			$this->output($message, 'error');
			exit;
		}
	}

}