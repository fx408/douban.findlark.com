<?php
class DoubanController extends Controller{
	public $title = '手机上的豆瓣';
	public $bookid = 0;
	
	// 查询图片
	public function getBook($bookid, $titleSuffix = '') {
		$data = Book::model()->findByPk($bookid);
		if(empty($data)) $data = Book::model()->addBook($bookid);
		if(empty($data)) throw new Exception('书籍已被删除或不存在!');
		
		$book = CJSON::decode($data->content, false);
		$this->title = $book->title.$titleSuffix;
		$this->bookid = $data->bookid;
		$book->summary = $data->summary;
		$book->author_intro = $data->author_intro;
		$book->catalog = $data->catalog;
		
		unset($data);
		return $book;
	}
}