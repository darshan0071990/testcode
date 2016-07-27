<?php

/**
 * This is the model class for table "packages".
 *
 * The followings are the available columns in table 'packages':
 * @property integer $id
 * @property string $package_name
 * @property string $features
 * @property integer $no_of_events
 * @property integer $amount
 * @property string $notification
 * @property string $duration
 * @property string $promo_code
 * @property integer $discounted_amount
 * @property string $type
 *
 * The followings are the available model relations:
 * @property OwnersSubscription[] $ownersSubscriptions
 */
class Packages extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'packages';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('package_name, features, no_of_events, amount,type, duration,', 'required'),
			array('no_of_events, amount,notification,duration, discounted_amount', 'numerical', 'integerOnly'=>true),
			array('package_name', 'length', 'max'=>255),
			array('promo_code, type', 'length', 'max'=>10),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, package_name, features, no_of_events, amount, duration, promo_code, discounted_amount', 'safe', 'on'=>'search'),
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
			'ownersSubscriptions' => array(self::HAS_MANY, 'OwnersSubscription', 'package_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'package_name' => 'Package Name',
			'features' => 'Features',
			'no_of_events' => 'No Of Events',
			'amount' => 'Amount',
			'duration' => 'Duration',
			'promo_code' => 'Promo Code',
			'discounted_amount' => 'Discounted Amount',
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
		$criteria->compare('package_name',$this->package_name,true);
		$criteria->compare('features',$this->features,true);
		$criteria->compare('no_of_events',$this->no_of_events);
		$criteria->compare('amount',$this->amount);
		$criteria->compare('duration',$this->duration,true);
		$criteria->compare('promo_code',$this->promo_code,true);
		$criteria->compare('discounted_amount',$this->discounted_amount);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Packages the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
