<?php
namespace Polidog\Console\Document;
interface DocumentInterface {
	
	/**
	 * ドキュメントを取得する
	 * @return string
	 */
	public function getDocumentString();
	
	/**
	 * ドキュメント配列を取得する
	 * @return array
	 */
	public function getDocument();
	

}