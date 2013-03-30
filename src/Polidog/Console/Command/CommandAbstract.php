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

	public function __construct() {
		$this->methodDocument = new MethodDocument($this);
	}
	
	public function main() {
		$this->output('main method start','info');
		$this->__commandList();
		$this->output('main method end','info');
	}
	

	public function getActionMethodPrefix() {
		return $this->actionMehotdPrefix;
	}

	/**
	 * アクション実行メソッド
	 * @param string $action 
	 * @param array $params 
	 */
	final public function execute($action, $params = array()) {
		$this->preFilter();
		$action = "command" . ucfirst($action);
		if (method_exists($this, $action)) {
			if ($this->$action($params,$action) === false) {
				$this->error('command action error' . __METHOD__);
			}
		} else {
			$this->main($params,$action);
		}
		$this->postFilter();
	}

	protected function preFilter() {
		
	}
	
	protected function postFilter() {
		
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