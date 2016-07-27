<?php

/**
 * This is the model class for table "bar_event".
 *
 * The followings are the available columns in table 'bar_event':
 * @property integer $id
 * @property string $event_name
 * @property string $event_discription
 * @property integer $bar_id
 * @property string $featured_pic
 * @property string $start_date
 * @property string $start_time
 * @property string $created_date
 * @property integer $status
 *
 * The followings are the available model relations:
 * @property BarDetail $bar
 * @property BarEventAlbum[] $barEventAlbums
 * @property BarEventFav[] $barEventFavs
 * @property BarEventRatings[] $barEventRatings
 */
class BarEvent extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'bar_event';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('event_name, event_discription, bar_id, featured_pic,start_date, start_time, created_date, status', 'required'),
            array('bar_id, status', 'numerical', 'integerOnly'=>true),
            array('event_name', 'length', 'max'=>255),
            array('created_date', 'length', 'max'=>10),
	    	array('featured_pic', 'file','types'=>'jpg,jpeg,gif,png','allowEmpty' => true),
	    	array('featured_pic', 'file','types'=>'jpg,jpeg,gif,png','allowEmpty' => true, 'on'=>'update'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, event_name, event_discription, bar_id, start_date, start_time, created_date, status', 'safe', 'on'=>'search'),
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
            'bar' => array(self::BELONGS_TO, 'BarDetail', 'bar_id'),
            'barEventAlbums' => array(self::HAS_MANY, 'BarEventAlbum', 'bar_event_id','together'=>true,'joinType'=>'RIGHT JOIN'),
            'barEventFavs' => array(self::HAS_MANY, 'BarEventFav', 'bar_event_id','together'=>false,'joinType'=>'LEFT JOIN'),
			'barEventUserFavs' => array(self::HAS_MANY, 'BarEventFav', 'bar_event_id'),
			
            'barEventRatings' => array(self::HAS_MANY, 'BarEventRatings', 'bar_event_id'),
			
			'barEventRatingsSTAT' => array(self::STAT, 'BarEventRatings', 'bar_event_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'event_name' => 'Event Name',
            'event_discription' => 'Event Discription',
            'bar_id' => 'Bar',
            'featured_pic' => 'Featured Pic',
            'start_date' => 'Start Date',
            'start_time' => 'Start Time',
            'created_date' => 'Created Date',
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
        $criteria->compare('event_name',$this->event_name,true);
        $criteria->compare('event_discription',$this->event_discription,true);
        $criteria->compare('bar_id',$this->bar_id);
        $criteria->compare('featured_pic',$this->featured_pic,true);
        $criteria->compare('start_date',$this->start_date,true);
        $criteria->compare('start_time',$this->start_time,true);
        $criteria->compare('created_date',$this->created_date,true);
        $criteria->compare('status',$this->status);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return BarEvent the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
}
?>