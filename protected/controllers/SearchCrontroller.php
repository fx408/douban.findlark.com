<?php

class SiteController extends DoubanController {
	
	public function actionIndex() {
		$this->title = 'ÕÒÕÒºÃÊé';
		
		$this->render('index', array('tags'=> BookTags::model()->tags));
	}
	
	public function actionWord($keyword) {
		
		
	}
	
	public function actionTag($tag) {
		
		
	}
}