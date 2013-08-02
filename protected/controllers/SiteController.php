<?php
class SiteController extends DoubanController {
	
	public function actions() {
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'application.extensions.CaptchaAction',
				'backColor'=>0xFFFFFF,
				'maxLength'=>4,
				'minLength'=>4,
				'testLimit'=>10,
			)
		);
	}
	
	public function actionIndex() {
		$this->title = '书籍列表';
		$this->render('/book/index');
	}
	
	public function actionError() {
		if($error=Yii::app()->errorHandler->error) {
			if(Yii::app()->request->isAjaxRequest) {
				echo $this->_end(1, $error['message']);
			} else {
				$this->render('error', $error);
			}
		}
	}
}

