<?php
class BookHelper{
	const READING_LIST = 'reading_list_';
	const READING_DETAIL = 'reading_detail_';
	const NOTE_LIST = 'note_list_';
	const NOTE_DETAIL = 'note_detail_';
	const EXPIRE = 129600;
	const PAGE_SIZE = 15;
	
	// 试读 列表
	public static function getBookReading($bookid) {
		$redis = Yii::app()->redis;
		$bookid = intval($bookid);
		
		if($data = $redis->get(self::READING_LIST.$bookid)) {
			$data = CJSON::decode($data);
		} else {
			$url = sprintf('http://book.douban.com/subject/%d/reading/', $bookid);
			$content = Curl::model()->request($url);
			
			$match = preg_match_all("#href=\"http://book.douban.com/reading/(\d+)/\"#", $content, $reading);
			if(!$match || !isset($reading[1])) {
				$reading[1] = array();
			}
			
			$redis->setex(self::READING_LIST.$bookid, self::EXPIRE, CJSON::encode($reading[1]));
			$data = $reading[1];
		}
		
		return $data;
	}
	
	// 试读详细
	public static function getReadingDetail($id) {
		$redis = Yii::app()->redis;
		$id = intval($id);
		
		if($data = $redis->get(self::READING_DETAIL.$id)) {
			$data = CJSON::decode($data);
		} else {
			$url = sprintf('http://book.douban.com/reading/%d/', intval($id));
			$content = Curl::model()->request($url);
			
			$match = preg_match("#\<div\s+class=\"book\-content\">(.*?)<div\s+class=\"rel-info\">#is", $content, $reading);
			if(!$match || !isset($reading[1])) {
				throw new Exception("查询试读资料失败!");
			}
			$reading = preg_replace("#<(?!img|p).*?>#", "", $reading[1]);
			
			$match = preg_match("#<div\s*id=\"content\">\s*<h1>(.*?)</h1>#is", $content, $title);
			$title = $match && isset($title[1]) ? $title[1] : $book->title;
			
			$data = array($title, $reading);
			
			$redis->setex(self::READING_DETAIL.$id, self::EXPIRE, CJSON::encode($data));
		}
		
		return $data;
	}
	
	// 读书笔记列表
	public static function getNoteList($bookid) {
		$bookid = intval($bookid);
		$redis = Yii::app()->redis;
		list($page, $start) = self::getStart();
		$key = sprintf("%s%d_%d", self::NOTE_LIST, $bookid, $start);
		
		$data = $redis->get($key);
		if($data) {
			$data = CJSON::decode($data);
		} else {
			$url = sprintf("http://api.douban.com/v2/book/%d/annotations?start=%d&count=%d", $bookid, $start, self::PAGE_SIZE);
			$content = Curl::model()->request($url);
			
			$content && $content = CJSON::decode($content);
			if(!$content || !isset($content['annotations'])) throw new Exception('获取读书笔记失败!');
			
			$data = array(
				'next'=> ($content['count']+$content['start']) < $content['total'] ? $page+1 : 0,
				'prev'=> $page-1,
				'list'=> array()
			);
			if(!empty($content['annotations'])) {
				foreach($content['annotations'] as $item) {
					$data['list'][] = array(
						'author_user' => $item['author_user'],
						'id' => $item['id'],
						'page_no' => $item['page_no'],
						'time' => $item['time'],
						'summary' => $item['summary']
					);
				}
				$redis->setex($key, self::EXPIRE, CJSON::encode($data));
			}
		}
		
		return $data;
	}
	
	// 详细的读书笔记
	public static function getNoteDetail($noteid) {
		$noteid = intval($noteid);
		$redis = Yii::app()->redis;
		$data = $redis->get(self::NOTE_DETAIL.$noteid);
		if($data) {
			$data = CJSON::decode($data);
		} else {
			$url = sprintf("http://api.douban.com/v2/book/annotation/%s", $noteid);
			$content = Curl::model()->request($url);
			
			$content && $content = CJSON::decode($content);
			if(!$content || !isset($content['book'])) throw new Exception('获取读书笔记失败!');
			
			$data = array();
			$data['author_user'] = $content['author_user'];
			$data['content'] = $content['content'];
			$data['time'] = $content['time'];
			
			$redis->setex(self::NOTE_DETAIL.$noteid, self::EXPIRE, CJSON::encode($data));
		}

		return $data;
	}
	
	public static function getStart() {
		$page = intval( Yii::app()->request->getParam('page', 0) );
		$page = max($page, 1);
		return array($page, ($page - 1) * self::PAGE_SIZE, self::PAGE_SIZE);
	}
}
