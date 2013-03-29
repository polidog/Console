<?php

namespace Polidog\Console;

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
		// @todo Exception
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

//	/**
//	 * 実行処理
//	 * @return void
//	 */
//	public function execute() {
//		if (empty($this->action)) {
//			static::commandList();
//			return;
//		}
//		$command = "Command_" . ucfirst($this->action);
//
//		if (!class_exists($command)) {
//			static::commandList();
//			return;
//		}
//
//		$class = new $command();
//		$class->execute($this->className, $this->params);
//	}
//
//	private function loadCommandClass($className) {
//		if (class_exists($command)) {
//			return new $command($this->options);
//		}
//		
//	}
//	
//	/**
//	 * コマンド一覧を取得する 
//	 */
//	protected function commandList() {
//		$dir = $this->getCommandPath();
//		$handle = opendir($dir);
//		if (!$handle) {
//			static::error("directory not found");
//		}
//
//		$not_output = array('Abstract.php', '.', '..');
//		while (false !== ($entry = readdir($handle))) {
//			if (!in_array($entry, $not_output)) {
//
//				$className = "" . str_replace(".php", "", $entry);
//
//				$refClass = new ReflectionClass("Command_" . $className);
//				$comment = static::parseComment($refClass->getDocComment());
//
//				echo "  " . strtolower($className) . "\t" . $comment . "\n";
//			}
//		}
//		echo "\n";
//	}
//
//	
//	
//	
//	/**
//	 * コマンドクラスが格納されているパス
//	 * @return string
//	 */
//	public function getCommandPath() {
//		if (strpos($this->classNameFilePath, '/') === true) {
//			return $this->classNameFilePath;
//		}
//		return __DIR__.DIRECTORY_SEPARATOR.$this->classNameFilePath;
//	}
//	
//	
//	/**
//	 * エラー処理を行う
//	 * @param string $message 
//	 * @param string $method　エラーが発生したメソッド名
//	 */
//	public static function error($message, $method = null) {
//		if ($method != null) {
//			$message = $method . ": " . $message;
//		}
//		echo "[error]" . $message . "\n";
//		die;
//	}
//
//	/**
//	 * 出力を行う
//	 * @param string $message 出力メッセージ
//	 * @param string $prefix  接頭辞
//	 */
//	public static function output($message, $prefix = null) {
//		if ($prefix) {
//			$message = "[$prefix]" . $message;
//		}
//		echo $message . "\n";
//	}
//
//	/**
//	 * PHPDoc comment parser
//	 * @param string $comment
//	 * @return string 
//	 */
//	public static function parseComment($comment) {
//		if (!$comment) {
//			return "";
//		}
//		$_comment = explode("\n", $comment);
//		if (isset($_comment[1])) {
//			$_comment[1] = str_replace("\t", "", $_comment[1]);
//			$_comment[1] = str_replace("*", "", $_comment[1]);
//			$_comment[1] = str_replace("\n", "", $_comment[1]);
//			$_comment[1] = str_replace(" ", "", $_comment[1]);
//			return $_comment[1];
//		}
//		return "";
//	}
}