<?php
namespace Polidog\Console\Document;
trait ParserTrait {
	
	/**
	 * Methodに記載されたPHPDocコメントのパース処理
	 * @param string $comment
	 * @return string
	 */	
	private function parseComment($comment) {
		if (!$comment) {
			return "";
		}
		$_comment = explode("\n", $comment);
		if (isset($_comment[1])) {
			return str_replace(array("\t", "*", "\n", " "), "", $_comment[1]);
		}
		return "";		
	}
}