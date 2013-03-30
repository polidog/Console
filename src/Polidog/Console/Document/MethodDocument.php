<?php

namespace Polidog\Console\Document;

use Polidog\Console\Command\CommandAbstract;
use Polidog\Console\Exception\ConsoleException;
use ReflectionMethod;

/**
 * コメント生成クラス
 * PHPDocに記載されている説明からコマンド説明を作成する為のメソッド
 * 
 * @author polidog <polidogs@gmail.com>
 * 
 * @property Polidog\Console\Command\CommandAbstract $targetObject 
 * 
 */
class MethodDocument implements DocumentInterface {
	use ParserTrait;
	
	private $targetObject;
	
	/**
	 * コンストラクタ
	 * @param \Polidog\Console\Command\CommandAbstract $targetObject
	 * @throws ConsoleException
	 */
	public function __construct(CommandAbstract $targetObject) {
		if ($targetObject instanceof CommandAbstract) {
			$this->targetObject = $targetObject;
		} else {
			throw new ConsoleException('Document target is no command object');
		}
	}
	
	public function __toString() {
		return $this->getDocumentString();
	}

	/**
	 * コメント文字列を取得する
	 * @return string
	 */
	public function getDocumentString() {
		$string = "";
		foreach ($this->getDocument() as $command) {
			if (!empty($command) && isset($command['name'], $command['comment'])) {
				$string .= "    " . $command['name'] . "\t" . $command['comment'] . "\n";
			}
		}
		return $string;
	}

	/**
	 * メソッドコメント一覧の取得
	 * @return array
	 */
	public function getDocument() {
		return $this->_getMethodComment($this->targetObject, get_class_methods(get_class($this->targetObject)));
	}

	/**
	 * メソッドコメントを取得してフォーマットに従って配列に挿入
	 * @param CommandAbstract $object
	 * @param string|array $methodName
	 * @return array
	 */
	private function _getMethodComment(CommandAbstract $object, $methodName) {
		if (is_array($methodName)) {
			$comments = array();
			foreach ($methodName as $m) {
				$comments[] = $this->_getMethodComment($object, $m);
			}
			return $comments;
		} else {
			if (substr($methodName, 0, strlen($this->targetObject->getActionMethodPrefix())) != $this->targetObject->getActionMethodPrefix()) {
				return array();
			}
			$refMethod = new ReflectionMethod($object, $methodName);
			$comment = $this->parseComment($refMethod->getDocComment());
			return array('name' => str_replace("command", "", strtolower($methodName)), 'comment' => $comment);
		}
	}
}
