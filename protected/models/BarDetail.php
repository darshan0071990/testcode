<?php

/**
 * This is the model class for table "bar_detail".
 *
 * The followings are the available columns in table 'bar_detail':
 * @property integer $id
 * @property integer $created_by
 * @property string $bar_name
 * @property string $address_line_1
 * @property string $city
 * @property string $country
 * @property string $zip_code
 * @property string $latitude
 * @property string $longtitude
 * @property string $phone_no
 * @property string $mobile_no
 * @property string $featured_pic
 * @property string $created_date
 *
 * The followings are the available model relations:
 * @property BarEvent[] $barEvents
 */
class BarDetail extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'bar_detail';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('created_by, bar_name, address_line_1,featured_pic, city, country, zip_code, latitude, longtitude, phone_no, mobile_no,created_date', 'required'),
            array('created_by', 'numerical', 'integerOnly' => true),
            array('bar_name', 'length', 'max' => 150),
            array('address_line_1, latitude, longtitude, featured_pic', 'length', 'max' => 255),
            array('phone_no, mobile_no', 'length', 'max' => 10),
            array('city, country, zip_code', 'length', 'max' => 100),
            array('created_date', 'length', 'max' => 10),
            array('featured_pic', 'file', 'types' => 'jpg,jpeg,gif,png', 'on' => 'update', 'allowEmpty' => true,),
            array('featured_pic', 'file', 'types' => 'jpg,jpeg,gif,png', 'allowEmpty' => true,),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, created_by, bar_name, address_line_1, city, country, zip_code, latitude, longtitude, phone_no, mobile_no, featured_pic, created_date', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'barEvents' => array(self::HAS_MANY, 'BarEvent', 'bar_id'),
            'barUserFavs' => array(self::HAS_MANY, 'BarEventFav', 'bar_event_id'),
            'barRatings' => array(self::HAS_MANY, 'BarEventRatings', 'bar_event_id'),
            'barEventRatingsSTAT' => array(self::STAT, 'BarEventRatings', 'bar_event_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'created_by' => 'Created By',
            'bar_name' => 'Bar Name',
            'address_line_1' => 'Address',
            'city' => 'City',
            'country' => 'Country',
            'zip_code' => 'Zip Code',
            'latitude' => 'Latitude',
            'longtitude' => 'Longtitude',
            'phone_no' => 'Phone Number',
            'mobile_no' => 'Mobile Number',
            'featured_pic' => 'Featured Pic',
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
    public function search() {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('created_by', $this->created_by);
        $criteria->compare('bar_name', $this->bar_name, true);
        $criteria->compare('address_line_1', $this->address_line_1, true);
        $criteria->compare('city', $this->city, true);
        $criteria->compare('country', $this->country, true);
        $criteria->compare('zip_code', $this->zip_code, true);
        $criteria->compare('latitude', $this->latitude, true);
        $criteria->compare('longtitude', $this->longtitude, true);
        $criteria->compare('phone_no', $this->phone_no, true);
        $criteria->compare('mobile_no', $this->mobile_no, true);
        $criteria->compare('featured_pic', $this->featured_pic, true);
        $criteria->compare('created_date', $this->created_date, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return BarDetail the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

}
