<?php

/**
 * This is the model class for table "book".
 *
 * The followings are the available columns in table 'book':
 * @property integer $bookid
 * @property string $content
 * @property string $catalog
 * @property string $author_intro
 * @property string $summary
 * @property string $timeline
 * @property integer $weights
 */
class Book extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Book the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'book';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('bookid, content, timeline', 'required'),
			array('bookid, weights', 'numerical', 'integerOnly'=>true),
			array('timeline', 'length', 'max'=>10),
			array('catalog, author_intro, summary', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('bookid, content, catalog, author_intro, summary, timeline, weights', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'bookid' => 'Bookid',
			'content' => 'Content',
			'catalog' => 'Catalog',
			'author_intro' => 'Author Intro',
			'summary' => 'Summary',
			'timeline' => 'Timeline',
			'weights' => 'Weights',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('bookid',$this->bookid);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('catalog',$this->catalog,true);
		$criteria->compare('author_intro',$this->author_intro,true);
		$criteria->compare('summary',$this->summary,true);
		$criteria->compare('timeline',$this->timeline,true);
		$criteria->compare('weights',$this->weights);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public $pageSize = 10;
	
	// 获取图书列表， 按添加时间排序
	public function getList() {
		$timeline = intval( Yii::app()->request->getParam('timeline') );
		$page = intval( Yii::app()->request->getParam('page') );
		$page = max($page, 1);
		
		$criteria=new CDbCriteria;
		$criteria->select = 'bookid,summary,content,timeline';
		$criteria->order = 'timeline DESC';
		if($timeline > 0) $criteria->compare('timeline', '<='.$timeline);
		$criteria->offset = ($page-1) * $this->pageSize;
		$criteria->limit = $this->pageSize;
		
		$data = $this->findAll($criteria);
		$result = array();
		
		foreach($data as $item) {
			$content = CJSON::decode($item->content);
			
			$tmp = array();
			$tmp['bookid'] = $item->bookid;
			$tmp['author'] = $content['author'];
			$tmp['title'] = $content['title'];
			$tmp['score'] = $content['score'];
			$tmp['numRaters'] = $content['numRaters'];
			$tmp['tags'] = $content['tags'];
			$tmp['img'] = $content['img'];
			$tmp['summary'] = mb_substr($item->summary, 0, 60, 'utf8').'...';
			
			$result[] = $tmp;
		}
		
		return array($page, $result, $data[0]->timeline);
	}
	
	// 格式化 从接口获取的数据
	public function formatBookData($data) {
		$book = array();
		$book['title'] = $data['title'];
		$book['author'] = implode(',', $data['author']);
		$book['description'] = '';
		$book['numRaters'] = $data['rating']['numRaters'];
		$book['score'] = $data['rating']['average'];
		$book['img'] = $data['images']['small'];
		$book['bookid'] = $data['id'];
		$book['summary'] = $data['summary'];
		$book['author_intro'] = $data['author_intro'];
		$book['catalog'] = $data['catalog'];
		
		$data['tags'] = array_slice($data['tags'], 0, 3);
		$book['tags'] = array();
		foreach($data['tags'] as $item) {
			$book['tags'][] = $item['name'];
		}
		$book['tags'] = implode(', ', $book['tags']);
		
		$book['bookid'] = $data['id'];
		return $book;
	}
	
	// 添加图书
	public function addBook($bookid) {
		$data = Curl::model()->request('http://api.douban.com/v2/book/'.$bookid.'?fields=title,tags,author,rating,images,id,summary,author_intro,catalog&apikey=04e2958bfe6ac33e0ea7c0a3fb4049ba');
		$data && $data = CJSON::decode($data);
		
		if(!$data || empty($data['title'])) return null;
		
		$book = $this->formatBookData($data);
		
		$model = Book::model();
		$model->bookid = $book['bookid'];
		$model->summary = $book['summary'];
		$model->author_intro = $book['author_intro'];
		$model->catalog = $book['catalog'];
		
		unset($book['bookid'], $book['summary'], $book['author_intro'], $book['catalog']);
		$model->content = CJSON::encode($book);
		$model->weights = 1;
		$model->timeline = time();
		$model->isNewRecord = true;
		return $model->save() ? $model : null;
	}
}