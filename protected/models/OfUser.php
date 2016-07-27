<?php

/**
 * This is the model class for table "ofUser".
 *
 * The followings are the available columns in table 'ofUser':
 * @property string $username
 * @property string $plainPassword
 * @property string $encryptedPassword
 * @property string $name
 * @property string $email
 * @property string $creationDate
 * @property string $modificationDate
 */
class OfUser extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'ofUser';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('username, creationDate, modificationDate', 'required'),
			array('username', 'length', 'max'=>64),
			array('plainPassword', 'length', 'max'=>32),
			array('encryptedPassword', 'length', 'max'=>255),
			array('name, email', 'length', 'max'=>100),
			array('creationDate, modificationDate', 'length', 'max'=>15),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('username, plainPassword, encryptedPassword, name, email, creationDate, modificationDate', 'safe', 'on'=>'search'),
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
			'username' => 'Username',
			'plainPassword' => 'Plain Password',
			'encryptedPassword' => 'Encrypted Password',
			'name' => 'Name',
			'email' => 'Email',
			'creationDate' => 'Creation Date',
			'modificationDate' => 'Modification Date',
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

		$criteria->compare('username',$this->username,true);
		$criteria->compare('plainPassword',$this->plainPassword,true);
		$criteria->compare('encryptedPassword',$this->encryptedPassword,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('creationDate',$this->creationDate,true);
		$criteria->compare('modificationDate',$this->modificationDate,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return OfUser the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
