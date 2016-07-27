<?php

class ReportController extends Controller {

    /**
     * @return array action filters
     */
    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
            'postOnly + delete', // we only allow deletion via POST request
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules() {
        return array(
            array('deny',
                'users' => array('?'),
            ),
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('index'),
                'roles' => array('@'),
            ),
        );
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {
        if (Yii::app()->user->roles != "Admin") {
            $get_trainer = 'SELECT user_login.username,user_login.display_name, order.amount,IF(order.type = 1,"Reservation","Fee") as type, transaction.transaction_id,club_event.event_name,club_detail.club_name,transaction.created_date, IF(transaction.validate = 1,"Validated","Pending") as validate FROM `order`
LEFT JOIN user_login ON user_login.user_id = order.user_id
LEFT JOIN transaction ON transaction.order_id = order.id
LEFT JOIN club_event ON club_event.id = order.club_event_id
LEFT JOIN club_detail ON club_detail.id = club_event.club_id
WHERE club_detail.created_by = "' . Yii::app()->user->id . '" AND transaction.response_code = "100"
ORDER BY transaction.created_date DESC';
        } else {
            $get_trainer = 'SELECT user_login.username,user_login.display_name, order.amount,IF(order.type = 1,"Reservation","Fee") as type, transaction.transaction_id,club_event.event_name,club_detail.club_name,transaction.created_date, IF(transaction.validate = 1,"Validated","Pending") as validate FROM `order`
LEFT JOIN user_login ON user_login.user_id = order.user_id
LEFT JOIN transaction ON transaction.order_id = order.id
LEFT JOIN club_event ON club_event.id = order.club_event_id
LEFT JOIN club_detail ON club_detail.id = club_event.club_id
WHERE transaction.response_code = "100"
ORDER BY transaction.created_date DESC';
        }

        $query_trainer_query = Yii::app()->db->createCommand($get_trainer)->queryAll();

        $dataProvider = new CArrayDataProvider($query_trainer_query, array(
            'id' => 'transaction_id',
            'keyField' => 'transaction_id',
            'keys' => array('transaction_id', 'username', 'display_name', 'amount', 'type', 'type', 'event_name', 'club_name', 'created_date', 'validate'),
            'sort' => array(
                'attributes' => array(
                    'username', 'display_name', 'amount', 'type', 'transaction_id', 'event_name', 'club_name', 'created_date', 'validate'
                ),
            ),
            'pagination' => array(
                'pageSize' => 20,
            ),
        ));

        $this->render('index', array('dataProvider' => $dataProvider));
    }

    public function actionOwners() {

        $sql = "SELECT owners_subscription.*, user_login.display_name, user_login.username,user_login.role, packages.package_name, packages.no_of_events, packages.type FROM `owners_subscription` LEFT JOIN user_login ON owners_subscription.user_id = user_login.user_id LEFT JOIN packages ON packages.id = owners_subscription.package_id";

        $query_trainer_query = Yii::app()->db->createCommand($sql)->queryAll();
        $dataProvider = new CArrayDataProvider($query_trainer_query, array(
            'id' => 'transaction_id',
            'keyField' => 'transaction_id',
            'keys' => array('transaction_id', 'username', 'display_name', 'amount', 'type', 'package_name', 'role'),
            'sort' => array(
                'attributes' => array(
                    'transaction_id', 'username', 'display_name', 'amount', 'type', 'package_name', 'role'
                ),
            ),
            'pagination' => array(
                'pageSize' => 20,
            ),
        ));

        $this->render('owners', array('dataProvider' => $dataProvider));
    }

}
