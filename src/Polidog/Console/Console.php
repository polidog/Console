<?php

namespace Polidog\Console;
use Polidog\Console\Exception\ConsoleException;

/**
 * Commandクラス
 * @author polidog <polidogs@gmail.com>
 */
class Console {

	private $className;
	private $methodName;
	private $params = array();
	private $paths = array();
	private $options = array();
	private $namespace = array();
	private $classDocument;

	/**
	 * コンストラクタ
	 */
	public function __construct($args, $options = array()) {
		$this->parse($args);
		$path = __DIR__ . DIRECTORY_SEPARATOR . "Command";
		$this->addPath($path, 'Polidog\Console\Command');
		$this->options = $options;
	}

	/**
	 * コマンドを解析する 
	 */
	private function parse($args) {

		if (!is_array($args) || empty($args) || count($args) < 2) {
			return false;
		}

		unset($args[0]);

		// クラス名の取得
		$this->className = $args[1];
		unset($args[1]);
		
		// 実行するメソッド名の取得
		if (isset($args[2])) {
			$this->methodName =  $args[2];
			unset($args[2]);
		}
		
		// 残りはパラメータとして、扱う
		if (!empty($args)) {
			foreach ($args as $arg) {
				$this->params[] = $arg;
			}
		}
	}

	/**
	 * セットしたパスを取得する
	 * @param string $key
	 * @return string
	 */
	public function getPath() {
		return new Path($this->paths);
	}

	/**
	 * パスを追加する
	 * @param string|array $path
	 * @param string $ns namespace
	 * @return \Polidog\Console\Console 
	 */
	public function addPath($path, $ns = null) {
		if (is_array($path)) {
			foreach ($path as $p) {
				if (isset($p[1])) {
					$ns = $p[1];
				}
				$this->addPath($p, $ns);
			}
		}
		$this->paths[] = $path;
		$this->namespace[$path] = $ns;
		return $this;
	}

	/**
	 * オプションをセットする
	 * @param string|array $key
	 * @param mixed $value
	 */
	public function setOption($key, $value = null) {
		if (is_array($key)) {
			foreach ($key as $k => $v) {
				$this->setOption($k, $v);
			}
		} else {
			$this->options[$key] = $value;
		}
	}

	/**
	 * コマンドを実行する
	 */
	public function execute() {
		if ($this->className == null)
			$this->className = 'help';
		$command = ucfirst($this->className);
		$className = $this->getClassname($command);
		$class = new $className($this->options);
		$class->execute($this->methodName, $this->params);
	}

	/**
	 * コマンドクラスのロード
	 * @param string $command
	 * @return type
	 */
	private function getClassname($command) {
		$path = $this->getPath();
		$isClass = false;
		while ($path->valid()) {
			$ns = $this->getNamespace($path->current());
			$file = $path . DIRECTORY_SEPARATOR . $command . '.php';
			if (!empty($ns)) {
				$ns.= '\\';
			}
			$className = $ns . $command;
			if (class_exists($className)) {
				$isClass = true;
				break;
			}

			if (file_exists($file)) {
				require $file;
				if (class_exists($className)) {
					$isClass = true;
					break;
				}
			}
			$path->next();
		}
		
		if (! $isClass) {
			throw new ConsoleException('class not found');
		}
		
		return $className;
	}

	/**
	 * 名前空間を取得する
	 * @param string $path
	 * @return string
	 */
	private function getNamespace($path) {
		if (isset($this->namespace[$path])) {
			return $this->namespace[$path];
		}
		return null;
	}
}