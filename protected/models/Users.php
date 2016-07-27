<?php

/**
 * This is the model class for table "users".
 *
 * The followings are the available columns in table 'users':
 * @property integer $id
 * @property string $name
 * @property string $email
 * @property string $sex
 * @property string $dob
 * @property string $about_me
 * @property string $city
 * @property string $country
 * @property string $mobile_no
 * @property integer $notification
 * @property integer $status
 *
 * The followings are the available model relations:
 * @property BarEventFav[] $barEventFavs
 * @property BarEventRatings[] $barEventRatings
 * @property ClubEventFav[] $clubEventFavs
 * @property ClubEventRatings[] $clubEventRatings
 * @property UserAlbum[] $userAlbums
 * @property UserLogin[] $userLogins
 */
class Users extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'users';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, email,notification, status', 'required'),
			array('is_fb, notification, status', 'numerical', 'integerOnly'=>true),
			array('name, email', 'length', 'max'=>200),
			array('fb_id, name', 'length', 'max'=>255),
			array('email', 'unique','message'=>'This Email is already in use'),
			array('sex', 'length', 'max'=>25),
			array('dob', 'length', 'max'=>50),
			array('city, country', 'length', 'max'=>100),
			array('mobile_no', 'length', 'max'=>15),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, email, sex, dob, about_me, city, country, mobile_no, notification, status', 'safe', 'on'=>'search'),
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
			'barEventFavs' => array(self::HAS_MANY, 'BarEventFav', 'user_id'),
			'barEventRatings' => array(self::HAS_MANY, 'BarEventRatings', 'user_id'),
			'clubEventFavs' => array(self::HAS_MANY, 'ClubEventFav', 'user_id'),
			'clubEventRatings' => array(self::HAS_MANY, 'ClubEventRatings', 'user_id'),
			'userAlbums' => array(self::HAS_MANY, 'UserAlbum', 'user_id'),
			'userLogins' => array(self::HAS_MANY, 'UserLogin', 'user_id'),
			'UserLogin' => array(self::HAS_ONE, 'UserLogin', 'user_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'Name',
			'email' => 'Email',
			'sex' => 'Sex',
			'dob' => 'Dob',
			'about_me' => 'About Me',
			'city' => 'City',
			'country' => 'Country',
			'mobile_no' => 'Mobile No',
			'notification' => 'Notification',
			'status' => 'Status',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('sex',$this->sex,true);
		$criteria->compare('dob',$this->dob,true);
		$criteria->compare('about_me',$this->about_me,true);
		$criteria->compare('city',$this->city,true);
		$criteria->compare('country',$this->country,true);
		$criteria->compare('mobile_no',$this->mobile_no,true);
		$criteria->compare('notification',$this->notification);
		$criteria->compare('status',$this->status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Users the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
