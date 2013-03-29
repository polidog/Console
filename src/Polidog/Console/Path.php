<?php
namespace Polidog\Console;

/**
 * path用のイテレータ
 */
class Path extends \FilterIterator {
	
	private $paths = array();
	
	public function __construct(array $paths) {
		array_unique($paths);
		$arrayObject = new \ArrayObject($paths);
		$iterator = $arrayObject->getIterator();
		$iterator->uksort(function($a,$b){
			if ($a > $b) {
				return false;
			}
			return true;
		});
		parent::__construct($iterator);
		$this->rewind();
	}

	public function __toString() {
		return $this->current();
	}
	
	public function current() {
		$path = parent::current();
		return rtrim($path,'/');
	}


	
	public function accept() {
		$path = $this->getInnerIterator()->current();
		return is_dir($path);
	}
}