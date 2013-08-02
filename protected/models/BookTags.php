<?php

/**
 * This is the model class for table "book_tags".
 *
 * The followings are the available columns in table 'book_tags':
 * @property string $tag
 * @property integer $count
 */
class BookTags extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return BookTags the static model class
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
		return 'book_tags';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('tag', 'required'),
			array('count', 'numerical', 'integerOnly'=>true),
			array('tag', 'length', 'max'=>10),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('tag, count', 'safe', 'on'=>'search'),
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
			'tag' => 'Tag',
			'count' => 'Count',
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

		$criteria->compare('tag',$this->tag,true);
		$criteria->compare('count',$this->count);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function getTags() {
		$redis = Yii::app()->redis;
		$key = 'book_tags';
		$tags = $redis->get($key);
		
		if(!$tags) {
			$data = $this->findAll('1=1 LIMIT 80');
			$tags = '';
			foreach($data as $item) {
				$tags .= $item->tag.',';
			}
			$tags = substr($tags, 0, -1);
			$redis->setex($key, 86400*15, $tags);
		}
		$tags = explode(',', $tags);
		
		return $tags;
	}
}