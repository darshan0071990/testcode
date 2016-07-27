<?php

/**
 * This is the model class for table "conversation_reply".
 *
 * The followings are the available columns in table 'conversation_reply':
 * @property integer $id
 * @property string $reply
 * @property integer $user_id
 * @property string $time
 * @property integer $conversation_id
 *
 * The followings are the available model relations:
 * @property Users $user
 * @property ChatAccept $conversation
 */
class ConversationReply extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'conversation_reply';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('reply, user_id, conversation_id', 'required'),
            array('user_id, conversation_id', 'numerical', 'integerOnly'=>true),
            array('time', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, reply, user_id, time, conversation_id', 'safe', 'on'=>'search'),
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
            'conversation' => array(self::BELONGS_TO, 'ChatAccept', 'conversation_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'reply' => 'Reply',
            'user_id' => 'User',
            'time' => 'Time',
            'conversation_id' => 'Conversation',
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
        $criteria->compare('reply',$this->reply,true);
        $criteria->compare('user_id',$this->user_id);
        $criteria->compare('time',$this->time,true);
        $criteria->compare('conversation_id',$this->conversation_id);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return ConversationReply the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
}