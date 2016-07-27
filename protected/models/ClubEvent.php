<?php

/**
 * This is the model class for table "club_event".
 *
 * The followings are the available columns in table 'club_event':
 * @property integer $id
 * @property string $event_name
 * @property string $event_discription
 * @property integer $club_id
 * @property integer $event_fee
 * @property string $reservation_fee
 * @property string $image_url
 * @property string $created_date
 * @property string $start_date
 * @property string $start_time
 * @property integer $status
 *
 * The followings are the available model relations:
 * @property ClubDetail $club
 * @property ClubEventAlbum[] $clubEventAlbums
 * @property ClubEventFav[] $clubEventFavs
 * @property ClubEventRatings[] $clubEventRatings
 */
class ClubEvent extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'club_event';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('event_name, event_discription, image_url, club_id, event_fee, reservation_fee, created_date, start_date, start_time, status', 'required'),
            array('club_id, event_fee, status', 'numerical', 'integerOnly'=>true),
            array('event_name, reservation_fee', 'length', 'max'=>100),
            array('created_date', 'length', 'max'=>10),

            array('image_url', 'file','types'=>'jpg,jpeg,gif,png','allowEmpty' => true),
            array('image_url', 'file','types'=>'jpg,jpeg,gif,png','allowEmpty' => true, 'on'=>'update'),
            
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, event_name, event_discription, club_id, event_fee, reservation_fee, created_date, start_date, start_time, status', 'safe', 'on'=>'search'),
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
            'club' => array(self::BELONGS_TO, 'ClubDetail', 'club_id'),
            
			//'clubEventAlbums' => array(self::HAS_MANY, 'ClubEventAlbum', 'club_event_id'),
			'clubEventAlbums' => array(self::HAS_MANY, 'ClubEventAlbum', 'club_event_id','together'=>true,'joinType'=>'RIGHT JOIN'),
            
			'clubEventFavs' => array(self::HAS_MANY, 'ClubEventFav', 'club_event_id','together'=>false,'joinType'=>'LEFT JOIN'),
			
			'clubEventUserFavs' => array(self::HAS_MANY, 'ClubEventFav', 'club_event_id'),
            
			'clubEventRatings' => array(self::HAS_MANY, 'ClubEventRatings', 'club_event_id'),
			
			'clubEventRatingsSTAT' => array(self::STAT, 'ClubEventRatings', 'club_event_id'),
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
            'club_id' => 'Club',
            'event_fee' => 'Event Fee',
            'reservation_fee' => 'Reservation Fee',
            'image_url' => 'Image Url',
            'created_date' => 'Created Date',
            'start_date' => 'Start Date',
            'start_time' => 'Start Time',
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
        $criteria->compare('club_id',$this->club_id);
        $criteria->compare('event_fee',$this->event_fee);
        $criteria->compare('reservation_fee',$this->reservation_fee,true);
        $criteria->compare('image_url',$this->image_url,true);
        $criteria->compare('created_date',$this->created_date,true);
        $criteria->compare('start_date',$this->start_date,true);
        $criteria->compare('start_time',$this->start_time,true);
        $criteria->compare('status',$this->status);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return ClubEvent the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
}
?>