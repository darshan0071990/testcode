<?php

require_once('Payeezy.php');
class ClubEventController extends Controller
{
	public function filters()
	{
		return array(
			'ext.starship.RestfullYii.filters.ERestFilter +
                REST.GET, REST.PUT, REST.POST, REST.DELETE',
		);
	}



	/**

	 * Specifies the access control rules.

	 * This method is used by the 'accessControl' filter.

	 * @return array access control rules

	 */

	public function accessRules()

	{

		return array(

			array('allow',  // allow all users to perform 'index' and 'view' actions

				'actions'=>array('index','view','REST.GET', 'REST.PUT', 'REST.POST', 'REST.DELETE'),
				'users'=>array('*'),

			),

			array('allow',  // allow all users to perform 'index' and 'view' actions

				'actions'=>array('index','view'),

				'users'=>array('*'),

			),

			array('allow', // allow authenticated user to perform 'create' and 'update' actions

				'actions'=>array('create','update'),
				'users'=>array('@'),

			),

			array('allow', // allow admin user to perform 'admin' and 'delete' actions

				'actions'=>array('admin','delete'),

				'users'=>array('@'),

			),

			array('deny',  // deny all users

				'users'=>array('*'),

			),

		);

	}



	/**

	 * Displays a particular model.

	 * @param integer $id the ID of the model to be displayed

	 */

	public function actionView($id)

	{

		if(isset($_GET['layout'])){
			$this->layout = $_GET['layout'];
		}

		$criteria = new CDbCriteria;
        $criteria->condition = "club_event_id = ".$id;
        $dataProvider=new CActiveDataProvider('ClubEventAlbum',array('criteria'=>$criteria));

        $this->render('view',array('model'=>$this->loadModel($id),'data'=>$dataProvider ));


    }



	/**

	 * Creates a new model.

	 * If creation is successful, the browser will be redirected to the 'view' page.

	 */

	public function actionCreate()
	{

		if(isset($_GET['layout'])){
			$this->layout = $_GET['layout'];
		}

		$model=new ClubEvent;

		if (isset($_POST['ClubEvent'])) {

			$model->attributes=$_POST['ClubEvent'];

			$club_id = $_POST['ClubEvent']['club_id'];
			$model->created_date = strtotime(date('Y-m-d H:i:s'));
			$model->start_time = date("H:i:s", strtotime($_POST['ClubEvent']['start_time']));
			$files = CUploadedFile::getInstance($model, 'image_url');

			if(isset($files) && !empty($files)){
				$model->image_url = $files;
			}

			if ($model->save()) {

				$club_event_id = Yii::app()->db->getLastInsertID();

				if(isset($files) && !empty($files)){
					$folder = 'images/clubevent/'.$club_event_id.'/';
					if(!is_dir($folder)){
						mkdir($folder,0777,true);
					}
					$model->image_url->saveAs($folder.$files->name); //move files in directory..
				}

				if(!empty($_POST['ClubEvent']['push_text'])){
					$get_all_ios = UserLogin::model()->findAllByAttributes(array('device'=>1));
					if(!empty($get_all_ios)){
						foreach($get_all_ios as $r){
							if(!empty($r->device_id)){
								$this->push_notify($_POST['ClubEvent']['push_text'],$r->device_id);
							}
						}
					}
				}
				$this->redirect(array('view','id'=>$model->id));
			}
		}

		$this->render('create',array('model'=>$model));
	}



	/**

	 * Updates a particular model.

	 * If update is successful, the browser will be redirected to the 'view' page.

	 * @param integer $id the ID of the model to be updated

	 */

	public function actionUpdate($id)
	{
		if(isset($_GET['layout'])){
			$this->layout = $_GET['layout'];
		}
		$clubevent = new ClubEvent('update');

		$model = $clubevent->findByPk($id);

		if (isset($_POST['ClubEvent'])) {
			if(empty($_POST['ClubEvent']['image_url'])){
				$_POST['ClubEvent']['image_url'] = $model->image_url;
			}
			$model->attributes=$_POST['ClubEvent'];
			$model->start_time = date("H:i:s", strtotime($_POST['ClubEvent']['start_time']));
			$files = CUploadedFile::getInstance($model, 'image_url');

			if(isset($files) && !empty($files)){
				$model->image_url = $files;
			}

			if($model->update()) {
				if(isset($files) && !empty($files)){
					$folder = 'images/clubevent/'.$id.'/';

					if(!is_dir($folder)){
						mkdir($folder,0777,true);
					}
					$model->image_url->saveAs($folder.$files->name); //move files in directory..
				}
				$this->redirect(array('view','id'=>$model->id));
			}
		}
		$this->render('update',array('model'=>$model));

	}



	/**

	 * Deletes a particular model.

	 * If deletion is successful, the browser will be redirected to the 'admin' page.

	 * @param integer $id the ID of the model to be deleted

	 */

	public function actionDelete($id)

	{

		if(isset($_GET['layout'])){
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

			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');

		}

	}



	/**

	 * Lists all models.

	 */

	public function actionIndex()
	{
		if(isset($_GET['layout'])){
			$this->layout = $_GET['layout'];
		}
		$user_role = Yii::app()->user->roles;
		$criteria = new CDbCriteria;
		$criteria->with = 'club';
		if($user_role == "ClubOwner"){

			$criteria->condition = 'club.created_by = '.Yii::app()->user->id;
		}
		$criteria->order = 't.id DESC';
		$dataProvider=new CActiveDataProvider('ClubEvent',array('criteria'=>$criteria));

		$this->render('index',array('dataProvider'=>$dataProvider));
	}

	/**

	 * Manages all models.

	 */

	public function actionAdmin()

	{

		if(isset($_GET['layout'])){
			$this->layout = $_GET['layout'];
		}

		$model=new ClubEvent('search');

		$model->unsetAttributes();  // clear any default values

		if (isset($_GET['ClubEvent'])) {

			$model->attributes=$_GET['ClubEvent'];

		}



		$this->render('admin',array(

			'model'=>$model,

		));

	}



	/**

	 * Returns the data model based on the primary key given in the GET variable.

	 * If the data model is not found, an HTTP exception will be raised.

	 * @param integer $id the ID of the model to be loaded

	 * @return ClubEvent the loaded model

	 * @throws CHttpException

	 */

	public function loadModel($id)

	{

		$model=ClubEvent::model()->findByPk($id);

		if ($model===null) {

			throw new CHttpException(404,'The requested page does not exist.');

		}

		return $model;

	}



	/**

	 * Performs the AJAX validation.

	 * @param ClubEvent $model the model to be validated

	 */

	protected function performAjaxValidation($model)

	{

		if (isset($_POST['ajax']) && $_POST['ajax']==='club-event-form') {

			echo CActiveForm::validate($model);

			Yii::app()->end();

		}

	}

	public function actionAlbumDelete($id){

        if(isset($_GET['layout'])){
            $this->layout = $_GET['layout'];
        }

        if (Yii::app()->request->isPostRequest) {

            // we only allow deletion via POST request

            $album = ClubEventAlbum::model()->findByPk($id);
			$folder = 'images/clubevent/' . $album->club_event_id . '/ClubAlbum/'.$album->image_url;
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

	 public function actionClubEventImage($id){

        $model = new ClubEventAlbum();

        if(isset($_FILES['ClubEventAlbum']['name']) && !empty($_FILES['ClubEventAlbum']['name'])) {
			$ctr = 0;
			foreach($_FILES['ClubEventAlbum']['name']['image_url'] as $r){
				$fileName = $r;
				$model = new ClubEventAlbum();
				$model->image_url = $fileName;
				$model->club_event_id = $id;

				if($model->save()) {

					$folder = 'images/clubevent/'. $id.'/ClubAlbum/';

					if (!is_dir($folder)) {
						mkdir($folder, 0777, true);
					}

					if(move_uploaded_file($_FILES['ClubEventAlbum']['tmp_name']['image_url'][$ctr], $folder.$fileName)){
						Yii::app()->user->setFlash('success', "Image saved Successfully!");
					}else{
						Yii::app()->user->setFlash('success', "Image saved Successfully!");
                }
				}
				$ctr++;
			}
        }

        $this->render('clubEventImage',array('id'=>$id,'model'=>$model));
    }

	public function actionclubevent(){

		if(isset($_POST)){
			$latitude = $_POST['latitude'];
			$longitude = $_POST['longitude'];
			$city = $_POST['city'];
			$user_id = $_POST['user_id'];

			$criteria = new CDbCriteria();
			$criteria->select = "id,club_name,address,city,country,zip_code,phone_no,mobile_no,featured_pic, (3959 * ACOS(COS( RADIANS('".$latitude."')) * COS(RADIANS(`latitude`)) * COS(RADIANS(`longtitude`) - RADIANS('".$longitude."')) + SIN( RADIANS('".$latitude."')) * SIN(RADIANS(`latitude`)))) AS distance";
			$criteria->having = "distance <100 and start_date >= '".date('Y-m-d')."'";
			$criteria->order = "start_date ASC";
			$criteria->with = array('clubEvents', 'clubEventFavs' => array('condition' => 'user_id=:user_id', 'params' => array(':user_id' => $user_id)), 'clubEventRatingsSTAT');

			$model = ClubDetail::model()->findAll($criteria);

			$response = array();
			if(!empty($model)){
			$count = 0;
			foreach($model as $r){
				if(!empty($r->clubEvents)){
					foreach($r->clubEvents as $d){
						$response['status'] = "Success";
						$response['events'][$count]['club_id']  = $r->id;
						$response['events'][$count]['club_name']  = $r->club_name;
						$response['events'][$count]['club_address']  = $r->address;
						$response['events'][$count]['club_city']  = $r->city;
						$response['events'][$count]['club_country']  = $r->country;
						$response['events'][$count]['club_zip_code']  = $r->zip_code;
						$response['events'][$count]['club_phone_no']  = $r->phone_no;
						$response['events'][$count]['club_mobile_no']  = $r->mobile_no;
						$response['events'][$count]['club_featured_pic']  = Yii::app()->getBaseUrl(true)."/images/club/".$r->id."/".$r->featured_pic;
						$response['events'][$count]['club_event']['event_id'] = $d->id;
						$response['events'][$count]['club_event']['event_name'] = $d->event_name;
						$response['events'][$count]['club_event']['event_description'] = $d->event_discription;
						$response['events'][$count]['club_event']['event_dp'] = Yii::app()->getBaseUrl(true)."/images/clubevent/".$d->id."/".$d->image_url;
						$response['events'][$count]['club_event']['event_fee'] = $d->event_fee;
						$response['events'][$count]['club_event']['reservation_fee'] = $d->reservation_fee;

						$tommorow = date('Y-m-d', strtotime('+1 day'));
						if($d->start_date == date('Y-m-d')){
							$response['events'][$count]['club_event']['event_start'] = "Today";

						}else if($tommorow == $d->start_date){

							$response['events'][$count]['club_event']['event_start'] = "Tomorrow";
						}else{
							$date = new DateTime($d->start_date);
							$response['events'][$count]['club_event']['event_start'] = date_format($date, 'D j F');
						}
						$response['events'][$count]['club_event']['start_time'] = $d->start_time;
						if(isset($r->clubEventFavs) && !empty($r->clubEventFavs)){
							$response['events'][$count]['fav'] = 1;
						}else{
							$response['events'][$count]['fav'] = 0;
						}

						$sql = "SELECT COUNT(club_event_id ) as count,SUM(rating) as avg FROM `club_event_ratings` WHERE club_event_id = $r->id";

						$query_result = Yii::app()->db->createCommand($sql)->queryAll();

						if(!empty($query_result)  && $query_result[0]['count'] != 0){
							$avg = round($query_result[0]['avg']/$query_result[0]['count']);
						}else{
							$avg = 0;
						}
						$response['events'][$count]['rating'] = $avg;

						$count++;
					}
				}
			}
			}else{
				$response['status'] = "Failure";
				$response['message'] = "Events not available in your city";
			}
			echo json_encode($response);
			exit;
		}
	}

	public function actionClubuserfav(){

		if(isset($_POST)){
			$latitude = $_POST['latitude'];
			$longitude = $_POST['longitude'];
			$city = $_POST['city'];
			$user_id = $_POST['user_id'];

			$criteria = new CDbCriteria();
			$criteria->select = "id,club_name,address,city,country,zip_code,phone_no,mobile_no,featured_pic, (3959 * ACOS(COS( RADIANS('".$latitude."')) * COS(RADIANS(`latitude`)) * COS(RADIANS(`longtitude`) - RADIANS('".$longitude."')) + SIN( RADIANS('".$latitude."')) * SIN(RADIANS(`latitude`)))) AS distance";
			$criteria->having = "distance <100";
			$criteria->order = "distance ASC";
			$criteria->with = array('clubEvents', 'clubUsersEventFavs' => array('condition' => 'user_id=:user_id', 'params' => array(':user_id' => $user_id)), 'clubEventRatingsSTAT');

			$model = ClubDetail::model()->findAll($criteria);
			$response = array();
			if(!empty($model)){
			$count = 0;
			foreach($model as $r){

				if(!empty($r->clubEvents)){
					foreach($r->clubEvents as $d){
						$response['status'] = "Success";
						$response['events'][$count]['club_id']  = $r->id;
						$response['events'][$count]['club_name']  = $r->club_name;
						$response['events'][$count]['club_address']  = $r->address;
						$response['events'][$count]['club_city']  = $r->city;
						$response['events'][$count]['club_country']  = $r->country;
						$response['events'][$count]['club_zip_code']  = $r->zip_code;
						$response['events'][$count]['club_phone_no']  = $r->phone_no;
						$response['events'][$count]['club_mobile_no']  = $r->mobile_no;
						$response['events'][$count]['club_featured_pic']  = Yii::app()->getBaseUrl(true)."/images/club/".$r->id."/".$r->featured_pic;
						$response['events'][$count]['club_event']['event_id'] = $d->id;
						$response['events'][$count]['club_event']['event_name'] = $d->event_name;
						$response['events'][$count]['club_event']['event_description'] = $d->event_discription;
						$response['events'][$count]['club_event']['event_dp'] = Yii::app()->getBaseUrl(true)."/images/clubevent/".$d->id."/".$d->image_url;
						$response['events'][$count]['club_event']['event_fee'] = $d->event_fee;
						$response['events'][$count]['club_event']['reservation_fee'] = $d->reservation_fee;
						$date = new DateTime($d->start_date);
						$response['events'][$count]['club_event']['event_start'] = date_format($date, 'D j F');

						$response['events'][$count]['club_event']['start_time'] = $d->start_time;
						if(isset($r->clubEventFavs) && !empty($r->clubEventFavs)){
							$response['events'][$count]['fav'] = 1;
						}else{
							$response['events'][$count]['fav'] = 0;
						}

						$sql = "SELECT COUNT(club_event_id ) as count,SUM(rating) as avg FROM `club_event_ratings` WHERE club_event_id = $r->id";

						$query_result = Yii::app()->db->createCommand($sql)->queryAll();
						if(!empty($query_result)){
							$avg = round($query_result[0]['avg']/$query_result[0]['count']);
						}else{
							$avg = 0;
						}
						$response['events'][$count]['rating'] = $avg;
						$count++;
					}
				}
			}
			}else{
				$response['status'] = "Failure";
				$response['message'] = "You do not have any favourite Club";
			}
			echo json_encode($response);
			exit;
		}
	}

	public function actionPastEvents(){
        if(isset($_POST)){
            $latitude = $_POST['latitude'];
            $longitude = $_POST['longitude'];

            $criteria = new CDbCriteria();
            $criteria->select = "id,club_name,address,city,country,zip_code,phone_no,mobile_no,featured_pic, (3959 * ACOS(COS( RADIANS('".$latitude."')) * COS(RADIANS(`latitude`)) * COS(RADIANS(`longtitude`) - RADIANS('".$longitude."')) + SIN( RADIANS('".$latitude."')) * SIN(RADIANS(`latitude`)))) AS distance";
            $criteria->having = "distance < 100 and start_date <= '".date('Y-m-d')."'";
            $criteria->order = "distance ASC";
            $criteria->with = array('clubEvents'=>array('with'=>array('clubEventAlbums')));

            $model = ClubDetail::model()->findAll($criteria);
			$response = array();
            if(!empty($model)){
                $count = 0;
                foreach($model as $r){
                    if(!empty($r->clubEvents)){
                        foreach($r->clubEvents as $d){
                            if(!empty($d->clubEventAlbums)){
                                $counter = 0;
                                $response['status'] = "Success";
                                foreach($d->clubEventAlbums as $a){
	                                $response['events'][$count]['club_id']  = $r->id;
                                    $response['events'][$count]['club_name']  = $r->club_name;
                                    $response['events'][$count]['club_featured_pic']  = Yii::app()->getBaseUrl(true)."/images/club/".$r->id."/".$r->featured_pic;
                                    $response['events'][$count]['club_event']['event_name'] = $d->event_name;
                                    $response['events'][$count]['club_event']['albums'][$counter] =Yii::app()->getBaseUrl(true)."/images/clubevent/".$a->club_event_id."/ClubAlbum/".$a->image_url;
                                    $counter++;
							    }
                            }
						$count++;
                        }
                    }
                }
            }else{
                $response['status'] = "Failure No Past Events";
                $response['message'] = "No Past Events";
            }
            echo json_encode($response);
            exit;
        }
    }
	public function actionClubfav(){
		$response = array();

		if(isset($_POST)){

			$user_id = $_POST['user_id'];
			$event_id = $_POST['club_id'];

			$model = new ClubEventFav;

			$model->user_id = $user_id;
			$model->club_event_id = $event_id;

			if($model->save()){
				$response['status'] ="Success";
				$response['message'] ="Club Event Fav successfully added";
			}else{
				$response['status'] ="Failure";
				$response['message'] ="Club Event Fav failed";
			}
		}else{
			$response['status'] ="Failure";
		}
		echo json_encode($response);
		exit;
	}

	public function actionClubrating(){
		$response = array();
		if(isset($_POST)){
			$user_id = $_POST['user_id'];
			$event_id = $_POST['club_id'];
			$rating = $_POST['rating'];

			$model = new ClubEventRatings;
				$model->user_id = $user_id;
				$model->club_event_id = $event_id;
				$model->rating = $rating;
			if($model->save()){
				$response['status'] ="Success";
				$response['message'] ="Club Event successfully added";
			}else{
				$response['status'] = "Failure";
				$response['message'] = "Club Event failed to add";
			}
		}else{
			$response['status'] ="Failure";
		}
		echo json_encode($response);
		exit;
	}

	public function actionRemovefav(){
		$response = array();

		if(isset($_POST)){

			$user_id = $_POST['user_id'];
			$event_id = $_POST['club_id'];

			$criteria = new CDbCriteria;
			$criteria->condition = 'user_id = '.$user_id . ' AND club_event_id = '.$event_id;

			if(ClubEventFav::model()->deleteAll($criteria)){
				$response['status'] = "Success";
				$response['message'] ="Club Event successfully removed";
			}else{
				$response['status'] = "Failure";
				$response['message'] = "Club Event failed to remove";
			}
		}else{
			$response['status'] ="Failure";
			$response['message'] ="Please Post data";
		}
		echo json_encode($response);
		exit;
	}

	public function actionSearch(){

        if(isset($_POST)){
            $name = $_POST['keyword'];
            $latitude = $_POST['latitude'];
            $longitude = $_POST['longitude'];

            $user_id = $_POST['user_id'];

            $criteria = new CDbCriteria();

			$criteria->addSearchCondition('club_name',$name,true,'OR');
            $criteria->addSearchCondition('clubEvents.event_name',$name,true,'OR');

			$criteria->select = "club_name,address,city,country,zip_code,phone_no,mobile_no,featured_pic, (3959 * ACOS(COS( RADIANS('".$latitude."')) * COS(RADIANS(`latitude`)) * COS(RADIANS(`longtitude`) - RADIANS('".$longitude."')) + SIN( RADIANS('".$latitude."')) * SIN(RADIANS(`latitude`)))) AS distance";
			$criteria->having = "distance <100";
			$criteria->order = "start_date ASC";
			$criteria->with = array('clubEvents'=>array('with'=>array('clubEventFavs'=>array('condition'=>'user_id=:user_id','params'=>array(':user_id' => $user_id)),'clubEventRatingsSTAT')));

			$model = ClubDetail::model()->findAll($criteria);

            $response = array();
            if(!empty($model)){
                $count = 0;
                foreach($model as $r){
				if(!empty($r->clubEvents)){
					foreach($r->clubEvents as $d){
						$response['status'] = "Success";
						$response['events'][$count]['club_id']  = $r->id;
						$response['events'][$count]['club_name']  = $r->club_name;
						$response['events'][$count]['club_address']  = $r->address;
						$response['events'][$count]['club_city']  = $r->city;
						$response['events'][$count]['club_country']  = $r->country;
						$response['events'][$count]['club_zip_code']  = $r->zip_code;
						$response['events'][$count]['club_phone_no']  = $r->phone_no;
						$response['events'][$count]['club_mobile_no']  = $r->mobile_no;
						$response['events'][$count]['club_featured_pic']  = Yii::app()->getBaseUrl(true)."/images/club/".$r->id."/".$r->featured_pic;
						$response['events'][$count]['club_event']['event_id'] = $d->id;
						$response['events'][$count]['club_event']['event_name'] = $d->event_name;
						$response['events'][$count]['club_event']['event_description'] = $d->event_discription;
						$response['events'][$count]['club_event']['event_dp'] = Yii::app()->getBaseUrl(true)."/images/clubevent/".$d->id."/".$d->image_url;
						$response['events'][$count]['club_event']['event_fee'] = $d->event_fee;
						$response['events'][$count]['club_event']['reservation_fee'] = $d->reservation_fee;
						$response['events'][$count]['club_event']['event_start'] = $d->start_date;
						$response['events'][$count]['club_event']['start_time'] = $d->start_time;
						if(isset($d->clubEventFavs) && !empty($d->clubEventFavs)){
							$response['events'][$count]['fav'] = 1;
						}else{
							$response['events'][$count]['fav'] = 0;
						}

						$response['events'][$count]['rating'] = $d->clubEventRatingsSTAT;
                        $count++;
                        }
                    }
                }
            }else{
                $response['status'] = "Failure";
                $response['message'] = "No Club/Club Events found.";
            }
            echo json_encode($response);
            exit;
        }
    }
    public function actionPurchase(){

    	$response = array();
    	if(isset($_POST)){
    		$method = $_POST['method'];
    		$amount = $_POST['amount'];
    		$currency = $_POST['currency'];
    		$cvv2 = $_POST['cvv2'];
    		$type = $_POST['type'];
    		$cardholder_name = $_POST['cardholder_name'];
    		$card_number = $_POST['card_number'];
    		$exp_date = $_POST['exp_date'];
    		$user_id = $_POST['user_id'];
    		$event_id = $_POST['event_id'];
    		$fee_type = $_POST['fee_type'];

    		require(dirname(__FILE__)."/PayeezyTest.php");
	        PayeezyTest::setUpBeforeClass();
	        //PayeezyTest::testAuthorize();
	        //print_r(PayeezyTest::setPrimaryTxPayload());
	        echo json_encode(PayeezyTest::testCapture());

	        exit;

    	}

    }

     public function actionPurchaseorder(){

        $response = array();
        if(isset($_POST)) {
            $user_id = $_POST['user_id'];
            $event_id = $_POST['event_id'];
            $fee_type = $_POST['fee_type'];
            $quantity = $_POST['quantity'];
            $amount = $_POST['amount'];

            $order = new Order();
            $order->user_id = $user_id;
            $order->club_event_id = $event_id;
            $order->type = $fee_type;
            $order->amount = $amount;
            $order->quantity = $quantity;
			$order->created_date = strtotime(date('Y-m-d H:i:s'));

            if($order->save()){
                $order_id = $order->id;
                $response['status'] ="Success";
                $response['order_id'] = $order_id;
            }else{
                $response['status'] ="Failure";
            }

            echo json_encode($response);
            exit;
        }
    }


	public function actionTransaction(){
		$response = array();
        if(isset($_POST)) {
            $order_id = $_POST['order_id'];
            $transaction_id = $_POST['transaction_id'];
            $amount = $_POST['amount'];
            $response_code = $_POST['response_code'];
            $device_id = $_POST['device_id'];


            $transaction = new Transaction();
            $transaction->order_id = $order_id;
            $transaction->transaction_id = $transaction_id;
            $transaction->amount = $amount;
            $transaction->response_code = $response_code;
            $transaction->device_id = $device_id;
            $transaction->validate = 0;
            $transaction->created_date = date('Y-m-d H:i:s');

            if($transaction->save()){
                if($response_code === "100"){

					$response['status'] ="Success";

					$this->widget('application.extensions.qrcode.QRCodeGenerator',array(
						'data' => $transaction_id,
						'subfolderVar' => false,
						'matrixPointSize' => 5,
						'displayImage'=>false, // default to true, if set to false display a URL path
						'errorCorrectionLevel'=>'M', // available parameter is L,M,Q,H
						'matrixPointSize'=>5, // 1 to 10 only
					));

					$response['qrcode'] = Yii::app()->getBaseUrl(true)."/uploads/".$transaction_id.".png";
				}

            }else{
                $response['status'] ="Failure";
            }

            echo json_encode($response);
            exit;
        }
	}

	public function actionBookingHistory(){

		$response = array();

		$user_id = $_POST['user_id'];

		$query = Yii::app()->db->createCommand('SELECT order.id, order.type, order.amount, order.created_date, transaction.transaction_id,transaction.response_code, club_event.event_name,club_event.start_date,club_event.start_time ,club_detail.club_name FROM `order` LEFT JOIN transaction ON transaction.order_id = order.id LEFT JOIN club_event ON club_event.id = order.club_event_id LEFT JOIN club_detail ON club_detail.id = club_event.club_id WHERE order.user_id = '.$user_id.' AND transaction.response_code = "100" ORDER BY order.id DESC');

		$data = $query->queryAll();
		if(!empty($data)){
			$ctr = 0;
			foreach($data as $r){
				$response[$ctr]['qrcode'] = Yii::app()->getBaseUrl(true)."/uploads/".$r['transaction_id'].".png";
				if($r['type'] === 0){
					$response[$ctr]['type'] ="Fee";
				}else{
					$response[$ctr]['type'] ="Reservation";
				}
				$response[$ctr]['amount'] =$r['amount'];
				$response[$ctr]['transaction_id'] =$r['transaction_id'];
				$response[$ctr]['event_name'] =$r['event_name'];
				$response[$ctr]['club_name'] =$r['club_name'];
				$response[$ctr]['date'] =$r['start_date'];
				$response[$ctr]['time '] =$r['start_time'];
				$ctr++;
			}
		}else{

			$response['status'] ="Failure";
			$response['message'] ="No Record Found";
		}
		echo json_encode($response);
		exit;
	}

	public function actionScanQr(){

		$response = array();

		$tid = $_POST['tid'];

		$query = Yii::app()->db->createCommand('SELECT order.id, order.type, order.amount, order.created_date, transaction.transaction_id,transaction.response_code, club_event.event_name,club_event.start_time, club_event.start_date ,club_detail.club_name
FROM `order`
LEFT JOIN transaction ON transaction.order_id = order.id
LEFT JOIN club_event ON club_event.id = order.club_event_id
LEFT JOIN club_detail ON club_detail.id = club_event.club_id
WHERE transaction_id = "'.$tid.'"');

		$data = $query->queryAll();


		if(!empty($data)){
			$response['status'] ="Success";
			$response['event_name'] = $data[0]['event_name'];
			$response['club_name'] = $data[0]['club_name'];
			$response['start_date'] = $data[0]['start_date'];
			$response['start_time'] = $data[0]['start_time'];
		}else{

			$response['status'] ="Failure";
			$response['message'] ="No Record Found";
		}

		echo json_encode($response);
		exit;
	}

	public function actionValidate(){
		$response = array();

		$tid = $_POST['transaction_id'];

		$data = Transaction::model()->findByAttributes(array('transaction_id'=>$tid));
		if(!empty($data)){
			if($data->validate == 0){
				$data->validate = 1;
				if($data->update()){
					$response['status'] = "Success";
					$response['message'] = "Validate Successful";
				}else{
					$response['status'] ="Failure";
				$response['message'] ="Failed to Validate";
				}
			}else{
				$response['status'] ="Failure";
				$response['message'] ="Invalid Ticket or Used";
			}
		}else{
			$response['status'] ="Failure";
			$response['message'] ="No Records Found";
		}
		echo json_encode($response);
		exit;
	}

	public function actionBuyout(){

		$response = array();
		if(!empty($_POST)){

			$user_id = $_POST['user_id'];
			$event_id = $_POST['event_id'];
			$amount = $_POST['amount'];
			$type = $_POST['type'];
			$full_name = $_POST['full_name'];
			$credit_card = $_POST['credit_card'];
			$credit_card_type = $_POST['credit_card_type'];
			$cvv = $_POST['cvv'];
			$card_expiry = $_POST['card_expiry'];
			$device_id  = $_POST['deviceId'];

			$order = new Order();
			$order->user_id = $user_id;
			$order->club_event_id = $event_id;
			$order->type = $type;
			$order->amount = $amount;
			$order->quantity = "1";
			$order->created_date = strtotime(date('Y-m-d H:i:s'));

			if($order->save()) {
				$order_id = $order->id;
				$payment = new Payeezy();
				$response_payment = $payment->purchase(array(

					"amount" => $amount,
					"card_number" => $credit_card,
					"card_type" => $credit_card_type,
					"card_holder_name" => $full_name,
					"card_cvv" => $cvv,
					"card_expiry" => $card_expiry,
					"merchant_ref" => "whats42nite",
					"currency_code" => "USD",
				));

				$return_val = json_decode($response_payment,true);
				$response_code = $return_val['bank_resp_code'];

				$transaction = new Transaction();
				$transaction->order_id = $order_id;
				$transaction->transaction_id = $return_val['transaction_id'];
				$transaction->amount = $amount;
				$transaction->response_code = $response_code;
				$transaction->device_id = $device_id;
				$transaction->created_date = date('Y-m-d H:i:s');

				if($transaction->save()){
					if($response_code === "100"){

						$response['status'] ="Success";
header('Content-Type: application/json');
						$this->widget('application.extensions.qrcode.QRCodeGenerator',array(
							'data' => $return_val['transaction_id'],
							'subfolderVar' => false,
							'matrixPointSize' => 5,
							'displayImage'=>false, // default to true, if set to false display a URL path
							'errorCorrectionLevel'=>'M', // available parameter is L,M,Q,H
							'matrixPointSize'=>5, // 1 to 10 only
						));

						$response['qrcode'] = Yii::app()->getBaseUrl(true)."/uploads/".$return_val['transaction_id'].".png";

						$user = UserLogin::model()->findByAttributes(array('user_id'=>$user_id));

						$club_detail = ClubEvent::model()->with('club')->findByPk($event_id);

						$subject = " Thank you for your payment for buying the tickets from Whats42nite of $".$amount ;

						$body = 'Dear '.$user->display_name.',
								<br/>
								<br/>
								Thank you for buying Ticket! <br/>
								<br/>
								<br/>
								We have received your payment for $'.$amount.' that you submitted on date '.date('d-m-Y').'. The payment has been authorized and approved. Please find attached bar code for the ticket.
								<br/>
								<br/>
								<br/>
								Ticket Details:
								<br/>
								Name: '.$user->display_name.'
								<br/>
								Club Name: '.$club_detail->club->club_name.'
								<br/>
								Event Name: '.$club_detail->event_name.'
								<br/>
								Address: '.$club_detail->club->address.'
								<br/>
								Admits: 1,
								<br/>
								Total Amount: '.$amount.',
								<br/>
								<br/>
								<br/>
								We are looking forward for you to buy more tickets.
								<br/><br/>
								Best Regards,
								Whats42nite Team
								<br/>
								info@whats42nite.com';

							$headers = 'From: <noreply@whats42nite.com>' . "\r\n";
							$headers .= "MIME-Version: 1.0\r\n";
							$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
							mail($user->username,$subject,$body,$headers);
					}else{
						$response['status'] ="Failure";
						$response['message'] = "Transaction Failed";
					}
				}else{
					$response['status'] ="Failure";
					$response['message'] = "Transaction Failed to Save";
				}
			}else{
				$response['status'] ="Failure";
				$response['message'] = "Failed to Generate Order ID";
			}
		}else{
			$response['status'] ="Failure";
			$response['message'] = "Please POST values";
		}

		echo json_encode($response);
		exit;
	}

	public function push_notify($message,$push_tokens){

		try{
			$payload['aps'] = array('alert' => $message, 'badge' => 1, 'sound' => 'default');
			$payload = json_encode($payload);

			//$apnsHost = 'gateway.sandbox.push.apple.com';
			$apnsHost = 'gateway.push.apple.com';
			$apnsPort = 2195;
			//$apnsCert = dirname(__FILE__).'/../../files/nightlife.pem';
			$apnsCert = dirname(__FILE__).'/../../files/whats42nite.pem';

			$streamContext = stream_context_create();
			stream_context_set_option($streamContext, 'ssl', 'local_cert', $apnsCert);

			$apns = stream_socket_client('ssl://' . $apnsHost . ':' . $apnsPort, $error, $errorString, 2, STREAM_CLIENT_CONNECT, $streamContext);
			$apnsMessage = chr(0) . chr(0) . chr(32) . pack('H*', str_replace(' ', '', $push_tokens)) . chr(0) . chr(strlen($payload)).$payload;
			fwrite($apns, $apnsMessage);
			@socket_close($apns);
			fclose($apns);

		}catch(Exception $e){
			print_r($e->message());
		}
	}
}
