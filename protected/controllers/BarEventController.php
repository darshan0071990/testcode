<?php

class BarEventController extends Controller {

    /**

     * @return array action filters

     */
    public function filters() {
        return array(
            array(
                'ext.starship.RestfullYii.filters.ERestFilter + 
                REST.GET, REST.PUT, REST.POST, REST.DELETE'
            ),
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
                'actions' => array('index', 'view', 'REST.GET', 'REST.PUT', 'REST.POST', 'REST.DELETE'),
                'users' => array('*'),
            ),
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('create', 'update'),
                'users' => array('@'),
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('delete'),
                'users' => array('@'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    public function actions() {
        return array(
            'REST.' => 'ext.starship.RestfullYii.actions.ERestActionProvider',
        );
    }

    /**

     * Displays a particular model.

     * @param integer $id the ID of the model to be displayed

     */
    public function actionView($id) {

        if (isset($_GET['layout'])) {
            $this->layout = $_GET['layout'];
        }

        $criteria = new CDbCriteria;
        $criteria->condition = "bar_event_id = " . $id;
        $dataProvider = new CActiveDataProvider('BarEventAlbum', array('criteria' => $criteria));

        $this->render('view', array('model' => $this->loadModel($id), 'data' => $dataProvider));
    }

    /**

     * Creates a new model.

     * If creation is successful, the browser will be redirected to the 'view' page.

     */
    public function actionCreate() {
        if (isset($_GET['layout'])) {
            $this->layout = $_GET['layout'];
        }
        $model = new BarEvent;

        if (isset($_POST['BarEvent'])) {

            $model->attributes = $_POST['BarEvent'];
            $club_id = $_POST['BarEvent']['bar_id'];
            $model->created_date = strtotime(date('Y-m-d H:i:s'));

            $files = CUploadedFile::getInstance($model, 'featured_pic');

            if (isset($files) && !empty($files)) {
                $model->featured_pic = $files;
            }

            if ($model->save()) {

                $bar_event_id = Yii::app()->db->getLastInsertID();
                if (isset($files) && !empty($files)) {
                    $folder = 'images/barevent/' . $bar_event_id . '/';

                    if (!is_dir($folder)) {
                        mkdir($folder, 0777, true);
                    }

                    $model->featured_pic->saveAs($folder . $files->name); //move files in directory..
                }

                $this->redirect(array('view', 'id' => $model->id));
            }
        }

        $this->render('create', array('model' => $model,));
    }

    /**

     * Updates a particular model.

     * If update is successful, the browser will be redirected to the 'view' page.

     * @param integer $id the ID of the model to be updated

     */
    public function actionUpdate($id) {
        if (isset($_GET['layout'])) {
            $this->layout = $_GET['layout'];
        }
        $barevent = new BarEvent('update');

        $model = $barevent->findByPk($id);

        if (isset($_POST['BarEvent'])) {
            if (empty($_POST['BarEvent']['featured_pic'])) {
                $_POST['BarEvent']['featured_pic'] = $model->featured_pic;
            }
            $model->attributes = $_POST['BarEvent'];
            $model->start_time = date("H:i:s", strtotime($_POST['BarEvent']['start_time']));
            $files = CUploadedFile::getInstance($model, 'featured_pic');

            if (isset($files) && !empty($files)) {
                $model->featured_pic = $files;
            }

            if ($model->update()) {
                if (isset($files) && !empty($files)) {
                    $folder = 'images/barevent/' . $id . '/';

                    if (!is_dir($folder)) {
                        mkdir($folder, 0777, true);
                    }
                    $model->featured_pic->saveAs($folder . $files->name); //move files in directory..
                }
                $this->redirect(array('view', 'id' => $model->id));
            }
        }
        $this->render('update', array('model' => $model));
    }

    /**

     * Deletes a particular model.

     * If deletion is successful, the browser will be redirected to the 'admin' page.

     * @param integer $id the ID of the model to be deleted

     */
    public function actionDelete($id) {

        if (isset($_GET['layout'])) {
            $this->layout = $_GET['layout'];
        }

        if (Yii::app()->request->isPostRequest) {

            // we only allow deletion via POST request

            $this->loadModel($id)->delete();



            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser

            if (!isset($_GET['ajax'])) {

                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
            }
        } else {

            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
        }
    }

    public function actionAlbumDelete($id) {

        if (isset($_GET['layout'])) {
            $this->layout = $_GET['layout'];
        }

        if (Yii::app()->request->isPostRequest) {

            // we only allow deletion via POST request

            $album = BarEventAlbum::model()->findByPk($id);
            $folder = 'images/barevent/' . $model->id . '/EventAlbum/' . $album->img_url;
            if (file_exists($folder)) {
                unlink($folder);
            }

            $album->delete();


            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser

            if (!isset($_GET['ajax'])) {

                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
            }
        } else {

            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
        }
    }

    /**

     * Lists all models.

     */
    public function actionIndex() {
        if (isset($_GET['layout'])) {
            $this->layout = $_GET['layout'];
        }

        $criteria = new CDbCriteria;
        $user_role = Yii::app()->user->roles;
        $criteria->with = 'bar';
        if ($user_role == "BarOwner") {

            $criteria->condition = 'bar.created_by = ' . Yii::app()->user->id;
        }

        $criteria->order = 't.id DESC';

        $dataProvider = new CActiveDataProvider('BarEvent', array('criteria' => $criteria));

        $this->render('index', array('dataProvider' => $dataProvider));
    }

    /**

     * Manages all models.

     */

    /**

     * Returns the data model based on the primary key given in the GET variable.

     * If the data model is not found, an HTTP exception will be raised.

     * @param integer $id the ID of the model to be loaded

     * @return BarEvent the loaded model

     * @throws CHttpException

     */
    public function loadModel($id) {

        $model = BarEvent::model()->findByPk($id);

        if ($model === null) {

            throw new CHttpException(404, 'The requested page does not exist.');
        }

        return $model;
    }

    public function actionBarEventImage($id) {
        $model = new BarEventAlbum();


        if (isset($_FILES['BarEventAlbum']['name']) && !empty($_FILES['BarEventAlbum']['name'])) {
            $ctr = 0;

            foreach ($_FILES['BarEventAlbum']['name']['img_url'] as $r) {
                $fileName = $r;
                $model = new BarEventAlbum();
                $model->img_url = $fileName;
                $model->bar_event_id = $id;

                if ($model->save()) {

                    $folder = 'images/barevent/' . $id . '/EventAlbum/';

                    if (!is_dir($folder)) {
                        mkdir($folder, 0777, true);
                    }

                    if (move_uploaded_file($_FILES['BarEventAlbum']['tmp_name']['img_url'][$ctr], $folder . $fileName)) {
                        Yii::app()->user->setFlash('success', "Image saved Successfully!");
                    } else {
                        Yii::app()->user->setFlash('success', "Image saved Successfully!");
                    }
                }
                $ctr++;
            }
        }

        $this->render('barEventImage', array('id' => $id, 'model' => $model));
    }

    /**

     * Performs the AJAX validation.

     * @param BarEvent $model the model to be validated

     */
    protected function performAjaxValidation($model) {

        if (isset($_POST['ajax']) && $_POST['ajax'] === 'bar-event-form') {

            echo CActiveForm::validate($model);

            Yii::app()->end();
        }
    }

    public function actionbarevent() {

        if (isset($_POST)) {
            $latitude = $_POST['latitude'];
            $longitude = $_POST['longitude'];
            $city = $_POST['city'];
            $user_id = $_POST['user_id'];

            $criteria = new CDbCriteria();
            $criteria->select = "bar_name,address_line_1,city,country,zip_code,phone_no,mobile_no, featured_pic, (3959 * ACOS(COS( RADIANS('" . $latitude . "')) * COS(RADIANS(`latitude`)) * COS(RADIANS(`longtitude`) - RADIANS('" . $longitude . "')) + SIN( RADIANS('" . $latitude . "')) * SIN(RADIANS(`latitude`)))) AS distance";
            $criteria->having = "distance <100 and start_date >= '" . date('Y-m-d') . "'";
            $criteria->order = "start_date ASC";
            //$criteria->with = array('barEvents' => array('with' => array('barEventFavs' => array('condition' => 'user_id=:user_id', 'params' => array(':user_id' => $user_id)), 'barEventRatingsSTAT')));
            $criteria->with = array('barEvents', 'barUserFavs' => array('condition' => 'user_id=:user_id', 'params' => array(':user_id' => $user_id)), 'barEventRatingsSTAT');

            $model = BarDetail::model()->findAll($criteria);

            $response = array();
            if (!empty($model)) {
                $count = 0;
                foreach ($model as $r) {
                    $response['status'] = "Success";
                    if (!empty($r->barEvents)) {
                        foreach ($r->barEvents as $d) {
                            /* if($d->start_date == date('Y-m-d')){

                              $response['Today']['events'][$count]['bar_name']  = $r->bar_name;
                              $response['Today']['events'][$count]['bar_address']  = $r->address_line_1;
                              $response['Today']['events'][$count]['bar_phone_no']  = $r->phone_no;
                              $response['Today']['events'][$count]['bar_mobile_no']  = $r->mobile_no;
                              $response['Today']['events'][$count]['bar_dp']  = Yii::app()->getBaseUrl(true)."/images/bar/".$r->id."/".$r->featured_pic;
                              $response['Today']['events'][$count]['bar_event']['event_id'] = $d->id;
                              $response['Today']['events'][$count]['bar_event']['event_name'] = $d->event_name;
                              $response['Today']['events'][$count]['bar_event']['event_description'] = $d->event_discription;
                              if(!empty($d->featured_pic)){
                              $response['Today']['events'][$count]['bar_event']['event_dp'] = Yii::app()->getBaseUrl(true)."/images/barevent/".$d->id."/".$d->featured_pic;
                              }
                              $response['Today']['events'][$count]['bar_event']['event_start'] = $d->start_date;
                              $response['Today']['events'][$count]['bar_event']['event_time'] = $d->start_time;
                              if(isset($d->barEventFavs) && !empty($d->barEventFavs)){
                              $response['Today']['events'][$count]['fav'] = 1;
                              }else{
                              $response['Today']['events'][$count]['fav'] = 0;
                              }
                              $response['Today']['events'][$count]['bar_rating'] = $d->barEventRatingsSTAT;

                              }else{

                              $response[$d->start_date]['events'][$count]['bar_name']  = $r->bar_name;
                              $response[$d->start_date]['events'][$count]['bar_address']  = $r->address_line_1;
                              $response[$d->start_date]['events'][$count]['bar_phone_no']  = $r->phone_no;
                              $response[$d->start_date]['events'][$count]['bar_mobile_no']  = $r->mobile_no;
                              $response[$d->start_date]['events'][$count]['bar_dp']  = Yii::app()->getBaseUrl(true)."/images/bar/".$r->id."/".$r->featured_pic;
                              $response[$d->start_date]['events'][$count]['bar_event']['event_id'] = $d->id;
                              $response[$d->start_date]['events'][$count]['bar_event']['event_name'] = $d->event_name;
                              $response[$d->start_date]['events'][$count]['bar_event']['event_description'] = $d->event_discription;
                              if(!empty($d->featured_pic)){
                              $response[$d->start_date]['events'][$count]['bar_event']['event_dp'] = Yii::app()->getBaseUrl(true)."/images/barevent/".$d->id."/".$d->featured_pic;
                              }
                              $response[$d->start_date]['events'][$count]['bar_event']['event_start'] = $d->start_date;
                              $response[$d->start_date]['events'][$count]['bar_event']['event_time'] = $d->start_time;
                              if(isset($d->barEventFavs) && !empty($d->barEventFavs)){
                              $response[$d->start_date]['events'][$count]['fav'] = 1;
                              }else{
                              $response[$d->start_date]['events'][$count]['fav'] = 0;
                              }
                              $response[$d->start_date]['events'][$count]['bar_rating'] = $d->barEventRatingsSTAT;

                              }
                              $count++; */

                            $response['events'][$count]['bar_name'] = $r->bar_name;
                            $response['events'][$count]['bar_address'] = $r->address_line_1;
                            $response['events'][$count]['bar_phone_no'] = $r->phone_no;
                            $response['events'][$count]['bar_mobile_no'] = $r->mobile_no;
                            $response['events'][$count]['bar_dp'] = Yii::app()->getBaseUrl(true) . "/images/bar/" . $r->id . "/" . $r->featured_pic;
                            $response['events'][$count]['bar_event']['event_id'] = $d->id;
                            $response['events'][$count]['bar_event']['event_name'] = $d->event_name;
                            $response['events'][$count]['bar_event']['event_description'] = $d->event_discription;
                            $response['events'][$count]['bar_event']['event_dp'] = Yii::app()->getBaseUrl(true) . "/images/barevent/" . $d->id . "/" . $d->featured_pic;
                            $response['events'][$count]['bar_event']['event_start'] = $d->start_date;
                            $response['events'][$count]['bar_event']['event_time'] = $d->start_time;
                            $response['events'][$count]['bar_event']['event_time'] = $d->start_time;
                            if (isset($r->barEventFavs) && !empty($r->barEventFavs)) {
                                $response['events'][$count]['fav'] = 1;
                            } else {
                                $response['events'][$count]['fav'] = 0;
                            }
                            $response['events'][$count]['bar_rating'] = $r->barEventRatingsSTAT;
                            $count++;
                        }
                    }
                }
            } else {
                $response['status'] = "Failure";
                $response['message'] = "Events not available in your city";
            }

            echo json_encode($response);
            exit;
        }
    }

    public function actionbaruserfav() {

        if (isset($_POST)) {
            $latitude = $_POST['latitude'];
            $longitude = $_POST['longitude'];
            $city = $_POST['city'];
            $user_id = $_POST['user_id'];

            $criteria = new CDbCriteria();
            $criteria->select = "bar_name,address_line_1,city,country,zip_code,phone_no,mobile_no, featured_pic, (3959 * ACOS(COS( RADIANS('" . $latitude . "')) * COS(RADIANS(`latitude`)) * COS(RADIANS(`longtitude`) - RADIANS('" . $longitude . "')) + SIN( RADIANS('" . $latitude . "')) * SIN(RADIANS(`latitude`)))) AS distance";
            $criteria->having = "distance <100";
            $criteria->order = "distance ASC";
            $criteria->with = array('barEvents', 'barUserFavs' => array('condition' => 'user_id=:user_id', 'params' => array(':user_id' => $user_id)), 'barEventRatingsSTAT');

            $model = BarDetail::model()->findAll($criteria);

            $response = array();
            if (!empty($model)) {
                $count = 0;
                foreach ($model as $r) {
                    $response['status'] = "Success";
                    if (!empty($r->barEvents)) {
                        foreach ($r->barEvents as $d) {
                            $response['events'][$count]['bar_name'] = $r->bar_name;
                            $response['events'][$count]['bar_address'] = $r->address_line_1;
                            $response['events'][$count]['bar_phone_no'] = $r->phone_no;
                            $response['events'][$count]['bar_mobile_no'] = $r->mobile_no;
                            $response['events'][$count]['bar_dp'] = Yii::app()->getBaseUrl(true) . "/images/bar/" . $r->id . "/" . $r->featured_pic;
                            $response['events'][$count]['bar_event']['event_id'] = $d->id;
                            $response['events'][$count]['bar_event']['event_name'] = $d->event_name;
                            $response['events'][$count]['bar_event']['event_description'] = $d->event_discription;
                            $response['events'][$count]['bar_event']['event_dp'] = Yii::app()->getBaseUrl(true) . "/images/barevent/" . $d->id . "/" . $d->featured_pic;
                            $response['events'][$count]['bar_event']['event_start'] = $d->start_date;
                            $response['events'][$count]['bar_event']['event_time'] = $d->start_time;
                            if (isset($r->barEventFavs) && !empty($r->barEventFavs)) {
                                $response['events'][$count]['fav'] = 1;
                            } else {
                                $response['events'][$count]['fav'] = 0;
                            }
                            $response['events'][$count]['bar_rating'] = $r->barEventRatingsSTAT;
                            $count++;
                        }
                    }
                }
            } else {
                $response['status'] = "Failure";
                $response['message'] = "Events not available in your city";
            }
            echo json_encode($response);
            exit;
        }
    }

    public function actionPastEvents() {

        if (isset($_POST)) {
            $latitude = $_POST['latitude'];
            $longitude = $_POST['longitude'];

            $criteria = new CDbCriteria();
            $criteria->select = "bar_name,address_line_1,city,country,zip_code,phone_no,mobile_no, featured_pic, (3959 * ACOS(COS( RADIANS('" . $latitude . "')) * COS(RADIANS(`latitude`)) * COS(RADIANS(`longtitude`) - RADIANS('" . $longitude . "')) + SIN( RADIANS('" . $latitude . "')) * SIN(RADIANS(`latitude`)))) AS distance";
            $criteria->having = "distance <100 and start_date <= '" . date('Y-m-d') . "'";
            $criteria->order = "distance ASC";
            $criteria->with = array('barEvents' => array('with' => array('barEventAlbums')));

            $model = BarDetail::model()->findAll($criteria);

            $response = array();
            if (!empty($model)) {
                $count = 0;
                foreach ($model as $r) {
                    if (!empty($r->barEvents)) {
                        foreach ($r->barEvents as $d) {
                            if (!empty($d->barEventAlbums)) {

                                $response['status'] = "Success";
                                $response['events'][$count]['bar_name'] = $r->bar_name;
                                $response['events'][$count]['bar_dp'] = Yii::app()->getBaseUrl(true) . "/images/bar/" . $r->id . "/" . $r->featured_pic;
                                $response['events'][$count]['bar_event']['event_name'] = $d->event_name;
                                $counter = 0;
                                foreach ($d->barEventAlbums as $a) {

                                    $response['events'][$count]['bar_event']['albums'][$counter] = Yii::app()->getBaseUrl(true) . "/images/barevent/" . $a->bar_event_id . "/EventAlbum/" . $a->img_url;
                                    $counter++;
                                }
                            }
                            $count++;
                        }
                    }
                }
            } else {
                $response['status'] = "Failure";
                $response['message'] = "No Past Events";
            }
            echo json_encode($response);
            exit;
        }
    }

    public function actionBarfav() {
        $response = array();
        if (isset($_POST)) {

            $user_id = $_POST['user_id'];
            $event_id = $_POST['bar_id'];

            $model = new BarEventFav;
            $model->user_id = $user_id;
            $model->bar_event_id = $event_id;
            if ($model->save()) {
                $response['status'] = "Success";
                $response['message'] = "Bar Event Fav successfully added";
            } else {
                $response['status'] = "Failure";
                $response['message'] = "Bar Event Fav failed to add";
            }
        } else {
            $response['status'] = "Failure";
            $response['message'] = "Please Post data";
        }
        echo json_encode($response);
        exit;
    }

    public function actionBarrating() {
        $response = array();
        if (isset($_POST)) {
            $user_id = $_POST['user_id'];
            $event_id = $_POST['bar_id'];
            $rating = $_POST['rating'];

            $model = new BarEventRatings;
            $model->user_id = $user_id;
            $model->bar_event_id = $event_id;
            $model->rating = $rating;
            if ($model->save()) {
                $response['status'] = "Success";
                $response['message'] = "Bar Event successfully added";
            } else {
                $response['status'] = "Failure";
                $response['message'] = "Bar Event failed to add";
            }
        } else {
            $response['status'] = "Failure";
            $response['message'] = "Please Post data";
        }
        echo json_encode($response);
        exit;
    }

    public function actionRemovefav() {
        $response = array();

        if (isset($_POST)) {

            $user_id = $_POST['user_id'];
            $event_id = $_POST['bar_id'];

            $criteria = new CDbCriteria;
            $criteria->condition = 'user_id =' . $user_id . ' AND bar_event_id = ' . $event_id;

            if (BarEventFav::model()->deleteAll($criteria)) {
                $response['status'] = "Success";
                $response['message'] = "User's Bar Fav successfully removed";
            } else {
                $response['status'] = "Failure";
                $response['message'] = "User's Bar Fav failed to remove";
            }
        } else {
            $response['status'] = "Failure";
            $response['message'] = "Please Post data";
        }
        echo json_encode($response);
        exit;
    }

    public function actionSearch() {

        if (isset($_POST)) {
            $name = $_POST['keyword'];
            $latitude = $_POST['latitude'];
            $longitude = $_POST['longitude'];

            $user_id = $_POST['user_id'];

            $criteria = new CDbCriteria();

            $criteria->addSearchCondition('bar_name', $name, true, 'OR');
            $criteria->addSearchCondition('barEvents.event_name', $name, true, 'OR');

            $criteria->select = "bar_name,address_line_1,city,country,zip_code,phone_no,mobile_no, featured_pic, (3959 * ACOS(COS( RADIANS('" . $latitude . "')) * COS(RADIANS(`latitude`)) * COS(RADIANS(`longtitude`) - RADIANS('" . $longitude . "')) + SIN( RADIANS('" . $latitude . "')) * SIN(RADIANS(`latitude`)))) AS distance";
            $criteria->having = "distance <100";
            $criteria->order = "distance ASC";
            $criteria->with = array('barEvents' => array('with' => array('barEventFavs' => array('condition' => 'user_id=:user_id', 'params' => array(':user_id' => $user_id)), 'barEventRatingsSTAT')));

            $model = BarDetail::model()->findAll($criteria);

            $response = array();
            if (!empty($model)) {
                $count = 0;
                foreach ($model as $r) {
                    $response['status'] = "Success";
                    if (!empty($r->barEvents)) {
                        foreach ($r->barEvents as $d) {
                            $response['events'][$count]['bar_name'] = $r->bar_name;
                            $response['events'][$count]['bar_address'] = $r->address_line_1;
                            $response['events'][$count]['bar_phone_no'] = $r->phone_no;
                            $response['events'][$count]['bar_mobile_no'] = $r->mobile_no;
                            $response['events'][$count]['bar_dp'] = Yii::app()->getBaseUrl(true) . "/images/bar/" . $r->id . "/" . $r->featured_pic;
                            $response['events'][$count]['bar_event']['event_id'] = $d->id;
                            $response['events'][$count]['bar_event']['event_name'] = $d->event_name;
                            $response['events'][$count]['bar_event']['event_description'] = $d->event_discription;
                            $response['events'][$count]['bar_event']['event_dp'] = Yii::app()->getBaseUrl(true) . "/images/barevent/" . $d->id . "/" . $d->featured_pic;
                            $response['events'][$count]['bar_event']['event_start'] = $d->start_date;
                            $response['events'][$count]['bar_event']['event_time'] = $d->start_time;
                            if (isset($d->barEventFavs) && !empty($d->barEventFavs)) {
                                $response['events'][$count]['fav'] = 1;
                            } else {
                                $response['events'][$count]['fav'] = 0;
                            }
                            $response['events'][$count]['bar_rating'] = $d->barEventRatingsSTAT;
                            $count++;
                        }
                    }
                }
            } else {
                $response['status'] = "Failure";
                $response['message'] = "No Bar/Bar Events found.";
            }
            echo json_encode($response);
            exit;
        }
    }

}
