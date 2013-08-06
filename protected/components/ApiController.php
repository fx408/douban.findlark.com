<?php
class ApiController extends Controller{
	public function getBook($bookid) {
		$data = Book::model()->findByPk($bookid);
		if(empty($data)) $data = Book::model()->addBook($bookid);
		if(empty($data)) return false;
		
		$book = CJSON::decode($data->content);
		
		$book['summary'] = $data->summary;
		$book['author_intro'] = $data->author_intro;
		$book['catalog'] = $data->catalog;
		
		return $book;
	}
}