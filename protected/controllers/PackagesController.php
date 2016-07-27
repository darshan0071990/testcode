<?php

require_once('Payeezy.php');

class PackagesController extends Controller {

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/Admin';

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
            array('allow', // allow all users to perform 'index' and 'view' actions
                'actions' => array('index', 'view'),
                'users' => array('*'),
            ),
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('create', 'update', 'subscribed', 'buy', 'checkout', 'promo_code'),
                'users' => array('@'),
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('admin', 'delete'),
                'users' => array('admin'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id) {
        $this->render('view', array(
            'model' => $this->loadModel($id),
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $model = new Packages;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Packages'])) {
            $model->attributes = $_POST['Packages'];
            if ($model->save())
                $this->redirect(array('view', 'id' => $model->id));
        }

        $this->render('create', array(
            'model' => $model,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id) {
        $model = $this->loadModel($id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Packages'])) {
            $model->attributes = $_POST['Packages'];
            if ($model->save())
                $this->redirect(array('view', 'id' => $model->id));
        }

        $this->render('update', array(
            'model' => $model,
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id) {
        $this->loadModel($id)->delete();

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {
        $dataProvider = new CActiveDataProvider('Packages');
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new Packages('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Packages']))
            $model->attributes = $_GET['Packages'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Packages the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = Packages::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param Packages $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'packages-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function actionSubscribed() {

        $user_role = Yii::app()->user->roles;

        if ($user_role === "BarOwner") {
            $this->layout = 'BarOwner';
        } else {
            $this->layout = 'ClubOwner';
        }

        $user_id = Yii::app()->user->id;

        $criteria = new CDbCriteria;
        $criteria->condition = 't.user_id = ' . $user_id;
        $criteria->with = 'package';
        $criteria->order = 't.id DESC';

        $dataProvider = new CActiveDataProvider('OwnersSubscription', array('criteria' => $criteria));

        $this->render('subscribed', array(
            'dataProvider' => $dataProvider,
        ));
    }

    public function actionBuy() {

        $user_role = Yii::app()->user->roles;

        if ($user_role === "BarOwner") {
            $this->layout = 'BarOwner';
            $type = "Bar";
        } else {
            $this->layout = 'ClubOwner';
            $type = "Club";
        }

        $dataProvider = new CActiveDataProvider('Packages', array(
            'criteria' => array(
                'condition' => 'type= "' . $type . '"',
            ),));

        $this->render('buy', array('dataProvider' => $dataProvider));
    }

    public function actionCheckout($id) {

        $user_role = Yii::app()->user->roles;

        if ($user_role === "BarOwner") {
            $this->layout = 'BarOwner';
        } else {
            $this->layout = 'ClubOwner';
        }

        $pid = Packages::model()->findByPk($id);

        $checkout = new CheckoutForm;
        if (isset($_POST['CheckoutForm'])) {
            $full_name = $_POST['CheckoutForm']['full_name'];
            $credit_card = $_POST['CheckoutForm']['credit_card'];
            $month = $_POST['CheckoutForm']['month'];
            $year = $_POST['CheckoutForm']['year'];
            $amount = $_POST['amount'];
            $type = $_POST['CheckoutForm']['type'];
            $cvv = $_POST['CheckoutForm']['cvv'];

            $request['transaction_type'] = 'authorize';
            $request['method'] = 'credit_card';
            $request['amount'] = $amount;
            $request['currency_code'] = 'USD';
            $request['credit_card']['type'] = $type;
            $request['credit_card']['cardholder_name'] = $full_name;
            $request['credit_card']['card_number'] = $credit_card;
            $request['credit_card']['exp_date'] = $month . $year;
            $request['credit_card']['cvv'] = $cvv;

            $payment = new Payeezy();
            $response = $payment->authorize(array(
                "amount" => $amount,
                "card_number" => $credit_card,
                "card_type" => $type,
                "card_holder_name" => $full_name,
                "card_cvv" => $cvv,
                "card_expiry" => $month . $year,
                "merchant_ref" => "whats42nite",
                "currency_code" => "USD",
            ));
            $return_val = json_decode($response, true);
            $response_code = $return_val['bank_resp_code'];
            if ($response_code === "100") {
                $time = strtotime(date("Y-m-d"));
                $final = date("Y-m-d", strtotime("+" . $pid->duration . " month", $time));
                $sdate = date("Y-m-d");
                $edate = $final;
                $model = new OwnersSubscription;
                $model->user_id = Yii::app()->user->id;
                $model->package_id = $id;
                $model->amount = $amount;
                $model->transaction_id = $return_val['transaction_id'];
                $model->start_date = $sdate;
                $model->end_date = $final;
                print_r($model->attributes());
                exit;
            } else {
                echo "Failed";
            }exit;
        }

        $this->render('checkout', array('package' => $pid, 'checkout' => $checkout));
    }

    public function actionPromo_code() {
        $response = array();

        $promo_code = $_GET['pcode'];
        $package_id = $_GET['package_id'];
        $amnt = $_GET['amnt'];

        $model = Packages::model()->findByPk($package_id);

        if ($model->promo_code === $promo_code) {
            $response['amount'] = $model->discounted_amount;
            $response['status'] = "Success! Promo code Applied.";
        } else {
            $response['amount'] = $model->amount;
            $response['status'] = "Error! Promo Code Fail.";
        }

        echo json_encode($response);
        exit;
    }

}
