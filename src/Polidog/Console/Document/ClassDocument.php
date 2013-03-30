<?php
namespace Polidog\Console\Document;
use Polidog\Console\Path;
use ReflectionClass;

class ClassDocument implements DocumentInterface {
	use ParserTrait;
	private $targetPath;
	
	private $denyFiles = array(
		'CommandAbstract.php', '.', '..'
	);
	
	public function __construct(Path $path) {
		$this->targetPath = $path;
	}
	
	public function __toString() {
		return $this->getDocumentString();
	}
	
	
	public function getDocumentString() {
		$string = null;
		foreach ($this->getDocument() as $command) {
			if (!empty($command) && isset($command['name'], $command['comment'])) {
				$string .= "    " . $command['name'] . "\t" . $command['comment'] . "\n";
			}
		}
		return $string;		
	}
	
	public function getDocument() {
		$comments = array();
		
		foreach ($this->targetPath as $path) {
			$handle = opendir($path);
			if (!$handle) {
				continue;
			}
			
			while (false !== ($entry = readdir($handle))) {
				if (in_array($entry,$this->denyFiles)) {
					continue;
				}
				
				$className = "" . str_replace(".php", "", $entry);
				
				$refClass = new ReflectionClass($className);
				$comment = $this->paserComment($refClass->getDocComment());
				$comments[] = array('name' => $className, 'comment' => $comment);
			}
		}
		return $comment;		
	}
	
		
	
}