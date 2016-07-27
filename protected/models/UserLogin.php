<?php

/**
 * This is the model class for table "user_login".
 *
 * The followings are the available columns in table 'user_login':
 * @property integer $id
 * @property integer $user_id
 * @property string $username
 * @property string $password
 * @property string $salt
 * @property string $display_name
 * @property string $latitude
 * @property string $longitude
 * @property string $device_id
 * @property integer $device
 * @property string $profile_pic_url
 * @property integer $created_by
 * @property string $created_date
 * @property string $role
 *
 * The followings are the available model relations:
 * @property Users $user
 */
class UserLogin extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'user_login';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, username, latitude, longitude, created_date, role', 'required'),
			array('user_id, device,created_by', 'numerical', 'integerOnly'=>true),
			array('username', 'length', 'max'=>100),
			array('password, latitude, profile_pic_url,longitude', 'length', 'max'=>255),
			array('salt, created_date', 'length', 'max'=>10),
			array('display_name', 'length', 'max'=>150),
			array('role', 'length', 'max'=>12),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, user_id, username, password, salt, display_name, latitude, longitude,device,device_id, created_by, created_date, role', 'safe', 'on'=>'search'),
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
			'user' => array(self::BELONGS_TO, 'Users', 'user_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'user_id' => 'User',
			'username' => 'Username',
			'password' => 'Password',
			'salt' => 'Salt',
			'display_name' => 'Display Name',
			'latitude' => 'Latitude',
			'longitude' => 'Longitude',
			'device_id' => 'Device',
            'device' => 'Device Type',
			'created_by' => 'Created By',
			'created_date' => 'Created Date',
			'role' => 'Role',
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
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('username',$this->username,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('salt',$this->salt,true);
		$criteria->compare('display_name',$this->display_name,true);
		$criteria->compare('latitude',$this->latitude,true);
		$criteria->compare('longitude',$this->longitude,true);
		$criteria->compare('device_id',$this->device_id,true);
        $criteria->compare('device',$this->device);
		$criteria->compare('created_by',$this->created_by);
		$criteria->compare('created_date',$this->created_date,true);
		$criteria->compare('role',$this->role,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return UserLogin the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
