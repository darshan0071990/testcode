<?php

/**
 * This is the model class for table "club_detail".
 *
 * The followings are the available columns in table 'club_detail':
 * @property integer $id
 * @property string $club_name
 * @property string $address
 * @property string $city
 * @property string $country
 * @property string $zip_code
 * @property string $latitude
 * @property string $longtitude
 * @property string $phone_no
 * @property string $mobile_no
 * @property string $featured_pic
 * @property integer $created_by
 * @property string $created_date
 *
 * The followings are the available model relations:
 * @property ClubEvent[] $clubEvents
 */
class ClubDetail extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'club_detail';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('club_name, address, city, country, zip_code,latitude, longtitude, phone_no, mobile_no, created_by, created_date', 'required'),
			array('created_by', 'numerical', 'integerOnly'=>true),
			array('club_name, address, latitude, longtitude', 'length', 'max'=>255),
			array('phone_no, mobile_no', 'length', 'max'=>10),
			array('city, country, zip_code', 'length', 'max'=>100),
			array('created_date', 'length', 'max'=>10),
			
			array('featured_pic', 'file','types'=>'jpg,jpeg,gif,png','allowEmpty' => true),
            array('featured_pic', 'file','types'=>'jpg,jpeg,gif,png','allowEmpty' => true, 'on'=>'update'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, club_name, address, city, country, zip_code, latitude, longtitude, phone_no, mobile_no, created_by, created_date', 'safe', 'on'=>'search'),
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
			'clubEvents' => array(self::HAS_MANY, 'ClubEvent', 'club_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'club_name' => 'Club Name',
			'address' => 'Address',
			'city' => 'City',
			'country' => 'Country',
			'zip_code' => 'Zip Code',
			'latitude' => 'Latitude',
			'longtitude' => 'Longtitude',
			'phone_no' => 'Phone No',
			'mobile_no' => 'Mobile No',
			'featured_pic' => 'Featured Pic',
			'created_by' => 'Created By',
			'created_date' => 'Created Date',
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
		$criteria->compare('club_name',$this->club_name,true);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('city',$this->city,true);
		$criteria->compare('country',$this->country,true);
		$criteria->compare('zip_code',$this->zip_code,true);
		$criteria->compare('latitude',$this->latitude,true);
		$criteria->compare('longtitude',$this->longtitude,true);
		$criteria->compare('phone_no',$this->phone_no,true);
		$criteria->compare('mobile_no',$this->mobile_no,true);
		$criteria->compare('featured_pic',$this->featured_pic,true);
		$criteria->compare('created_by',$this->created_by);
		$criteria->compare('created_date',$this->created_date,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ClubDetail the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
