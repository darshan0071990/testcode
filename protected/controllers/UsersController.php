<?php

class UsersController extends Controller
{

	/**
	 * @return array action filters
	 */
	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('deny',
				'users'=>array('?'),
			),
			array('allow',  // allow all users to perform 'index' and 'view' actions

				'actions'=>array('index','view','viewprofile','REST.GET', 'REST.PUT', 'REST.POST', 'REST.DELETE'),
				'users'=>array('*'),

			),
			array('allow',
				'actions'=>array('update','change_password','view'),
				'roles'=>array('ClubOwner','BarOwner','Admin','Bouncer')
			),
			array('allow',
				'actions'=>array('view','create','import','import_csv_sql','delete','autocomplete','index'),
				'roles'=>array('Trainer','Admin'),
			),
			array('allow',
				'actions'=>array('delete'),
				'roles'=>array('Admin'),
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
		$this->render('view',array('model'=>$this->loadModel($id)));
	}

	public function actionRegister(){
        $this->layout = "login";

        if(!empty($_POST)) {
			$package_id = $_POST['Package_id'];

			$string = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
            $length = 6;
            $reset_length = 22;


            if (!empty($_POST['Users'])) {
                $model = new Users();
                $model->name = $_POST['Users']['name'];
                $model->email = $_POST['Users']['email'];
				$model->sex = "Male";
                $model->dob = "0000-00-00";
                $model->about_me = $_POST['UserLogin']['role'];
                $model->city = $_POST['Users']['city'];
                $model->country = $_POST['Users']['country'];
                $model->mobile_no = $_POST['Users']['mobile_no'];
                $model->notification = 1;
                $model->status = 1;
				if ($model->save()) {
                    $user_id = Yii::app()->db->lastInsertID;

                    $user_login = new UserLogin;
                    $user_login->user_id = $user_id;

                    $user_login->username = $model->email;

                    //$user_login->salt = substr(str_shuffle($string), 0, $length);

                    //$user_login->password = md5($user_login->salt . $_POST['UserLogin']['password']);

                    $user_login->display_name = $model->name;

                    $user_login->created_by = 0;
                    $user_login->latitude = "012";
                    $user_login->longitude = "012";

                    $user_login->created_date = strtotime(date('Y-m-d H:i:s'));

					$user_login->reset_password = 1;

					$user_login->reset_token = substr(str_shuffle($string), 0,$reset_length);

                    $user_login->role = $_POST['UserLogin']['role'];

                    if($user_login->save()){

						$this->forgotMail($user_id,$user_login->username,$user_login->reset_token);

						Yii::app()->user->setFlash('error','Successfully Sign Up. Please Check your Email Id for Sign In Link.');
                        /*$get_amnt = Packages::model()->findByPk($_POST['Package_id']);

                        $owners_sub = new OwnersSubscription();
                        $owners_sub->user_id = $user_id;
                        $owners_sub->package_id = $_POST['Package_id'];
                        $owners_sub->amount = $get_amnt->amount;
                        $owners_sub->date = strtotime(date('Y-m-d H:i:s'));
                        if($owners_sub->save()){
                            $order_id = Yii::app()->db->lastInsertID;
                            echo $order_id;
                        }*/
                    }
                }else{
					$errores = $model->getErrors();
					if(isset($errores['email']) && !empty($errores['email'])){
						$mail_error = $errores['email'][0];
						Yii::app()->user->setFlash('error',$mail_error);
					}else{
						Yii::app()->user->setFlash('error','Error, Please Try again.');
					}
				}
            }
        }
        $this->render("register");

    }

	public function forgotMail($uid,$username,$reset_token){

		$user = UserLogin::model()->findByAttributes(array('user_id'=>$uid));

		$reset_url = 'http://whats42nite.com/admin/userLogin/reset_password?token='.$reset_token.'&id='.$uid;

		$subject = "Whats42nite Sign Up";

		$body = 'Dear '.$user->display_name.',
				<br/>
				<br/>
				Thank You for Signing Up with Whats42nite. Please find below your username and Link to set your new password.
				<br>
				<br>
				username : '.$user->username.'
				<br>
				<br>
				Your Account password can be set by clicking on the link below: <br/>'.$reset_url.'
				<br/>
				<br/>
				Once you have clicked on the link, you will be asked to choose a Password.
				<br/>
				<br/>
				<br/>
				All the best,
				<br/>
				Whats42nite Team
				<br/>
				info@whats42nite.com';

		$headers .= 'From: <noreply@whats42nite.com>' . "\r\n";
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
		mail($user->username,$subject,$body,$headers);

		/*$message = new YiiMailMessage;
		$message->subject  = $subject;
		$message->addTo($user->username);
		$message->setBody($body, 'text/html');
		$message->replyto = 'darshan@horizzon.com';
		$message->from = array('darshan@horizzon.com' => 'Darshan Hingu');

		if(Yii::app()->mail->send($message)){
			return true;
		}else{
			return false;
		}*/
	}

	public function actionCheckout(){
        $this->layout = "login";
        $this->render('checkout');

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
		$model=new Users;

		$string = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
		$length = 6;
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Users']))
		{
			$model->attributes=$_POST['Users'];
			$model->notification=1;
			$model->status=1;

            if($model->validate()){

				if($model->save()){
					$user_id = Yii::app()->db->lastInsertID;

					$user_login = new UserLogin;
						$user_login->username = $model->email;
						$user_login->role = "Bouncer";
						$user_login->user_id = $user_id;
                        $user_login->latitude = "0";
                        $user_login->longitude = "0";
						$user_login->display_name = $model->name;
						$user_login->salt = substr(str_shuffle($string), 0, $length);
                        $user_login->created_by = Yii::app()->user->id;
                        $user_login->password = md5($user_login->salt.$_POST['UserLogin']['password']);
						$user_login->created_date = strtotime(date('Y-m-d H:i:s'));
					$user_login->save();

					$this->redirect(array('view','id'=>$model->id));
				}
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

		//Check accessControl.
		$model= Users::model()->with('UserLogin')->findByPk($id);
		$result = Yii::app()->authManager->getAuthAssignment(Yii::app()->user->roles,$id);

		$self = false;
		if(!empty($result)){
			$self = true;
		}

		if(isset($_POST['Users']))
		{
			$model->attributes=$_POST['Users'];

			$dp_file = CUploadedFile::getInstance($model, 'display_picture');

			$user_login = UserLogin::model()->findByAttributes(array('user_id'=>$id));

			$user_login->username = $model->email;
			$user_login->display_name = $model->fname." ".$model->lname;

			if($model->validate()){
				if($model->save() && $user_login->update()){

					$user_id = Yii::app()->db->lastInsertID;

					$this->redirect(array('view','id'=>$model->id));
				}
			}
		}

		$this->render('update',array(
			'model'=>$model,
			'self'=>$self
		));
	}

	public function actionChange_password(){

		$string = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
		$length = 6;

		$model = new ResetForm;
		if(isset($_POST['User']) && !empty($_POST['User'])){
			$uid = $_POST['User']['id'];

			$user_login = UserLogin::model()->findByAttributes(array('user_id'=>$uid));

			if(!empty($_POST['ResetForm']['resetPassword'])){

				$user_login->salt = substr(str_shuffle($string), 0, $length);

				$user_login->password = md5($user_login->salt.$_POST['ResetForm']['resetPassword']);

				if($user_login->save()){
					Yii::app()->user->setFlash('success', "Password successfully changed");
				}
			}
		}
		$this->render('change_password',array(
			'model'=>$model
		));
	}
	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
        if(Yii::app()->user->roles != "Admin") {
            $dataProvider = new CArrayDataProvider(Users::model()->with(array('userLogins' => array('condition' => 'created_by = "' . Yii::app()->user->id . '"')))->findAll(), array(
                'pagination' => array(
                    'pageSize' => 30
                )
            ));
        }else{
            $dataProvider = new CArrayDataProvider(Users::model()->with('userLogins')->findAll(), array(
                'pagination' => array(
                    'pageSize' => 30
                )
            ));
        }
		$this->render('index',array('dataProvider'=>$dataProvider));
	}

    public function actionViewbar(){

        $dataProvider = new CArrayDataProvider(Users::model()->with(array('userLogins'=>array('condition' => 'role= "BarOwner"')))->findAll(),array(
            'pagination' => array(
                'pageSize' => 30
            )
        ));

        $this->render('viewbar',array('dataProvider'=>$dataProvider));

    }

    public function actionViewclub(){

        $dataProvider = new CArrayDataProvider(Users::model()->with(array('userLogins'=>array('condition' => 'role= "ClubOwner"')))->findAll(),array(
            'pagination' => array(
                'pageSize' => 30
            )
        ));

        $this->render('viewclub',array('dataProvider'=>$dataProvider));

    }

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		if(isset($_GET['layout'])){
			$this->layout = $_GET['layout'];
		}
		$model=new Users('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Users']))
			$model->attributes=$_GET['Users'];

		$this->render('admin',array('model'=>$model,));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Users the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Users::model()->with('userLogins')->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Users $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_GET['layout'])){
			$this->layout = $_GET['layout'];
		}
		if(isset($_POST['ajax']) && $_POST['ajax']==='users-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	public function actionOwnersReg(){
		if(isset($_GET['layout'])){
			$this->layout = $_GET['layout'];
		}
		$model = new Users;

		$this->render('create',array('model'=>$model));
	}

	/*public function actionApplogin(){
		if(isset($_POST)){
			$email = $_POST['email'];
			$password = $_POST['password'];

			$record = UserLogin::model()->with('user')->findByAttributes(array('username'=>$email));
			$response = array();
			if($record===null){
				$response['status'] = "Failure";
				$response['message'] = "Username does not exist";
			}
			else if($record->attributes['password']!== md5($record->attributes['salt'].$password)){
				$response['status'] = "Failure";
				$response['message'] = "Invalid Password";
			}else{
				$criteria = new CDbCriteria;
				$criteria->condition = "is_profile = 1 AND user_id = ".$record->attributes['user_id'];

				$dp = UserAlbum::model()->findAll($criteria);

				$response['status'] = "Success";
				$response['user_id'] = $record->attributes['user_id'];
				$response['user_type'] = $record->attributes['role'];
				$response['user_name'] = $record->attributes['display_name'];
                if(empty($record->user['is_fb'])){
                    $response['is_fb'] = $record->user['is_fb'];
                }else{
                    $response['is_fb'] = $record->user['is_fb'];
                    $response['is_fb'] = $record->user['fb_id'];
                }

				if(!empty($dp)){
					$response['user_dp'] = Yii::app()->getBaseUrl(true)."/images/users/".$record->attributes['user_id']."/".$dp[0]->image_url;
				}else{
					$response['user_dp'] = "Empty";
				}
			}
			echo json_encode($response);
			exit;
		}

	}*/

	public function actionApplogin(){
		if(isset($_POST)){

			if(!empty($_POST['email'])){
                $email = $_POST['email'];
            }
            if(!empty($_POST['password'])){
                $password = $_POST['password'];
            }
            $response = array();
            try{
            if(!empty($_POST['fb_id'])){
                $fb_id = $_POST['fb_id'];

                $record = Users::model()->with('userLogins')->findByAttributes(array('fb_id'=>$fb_id));
                if(!empty($record)){
                    $response['status'] = "Success";
                    $response['user_id'] = $record->attributes['id'];
                    $response['full_name'] = $record->attributes['name'];
                    $response['profile_pic_url'] = $record->userLogins->profile_pic_url;

                    $criteria = new CDbCriteria;
                    $criteria->condition = "is_profile = 1 AND user_id = ".$record->attributes['id'];

                    $dp = UserAlbum::model()->findAll($criteria);

                    if(!empty($dp)){
                        $response['user_dp'] = Yii::app()->getBaseUrl(true)."/images/users/".$record->attributes['user_id']."/".$dp[0]->image_url;
                    }else{
                        $response['user_dp'] = "Empty";
                    }
                }else{
                    $response['status'] = "Failure";
                    $response['message'] = "Username does not exist";
                }


            }else{
                $record = UserLogin::model()->with('user')->findByAttributes(array('username'=>$email));

                if($record===null){
                    $response['status'] = "Failure";
                    $response['message'] = "Username does not exist";
                }
                else if($record->attributes['password']!== md5($record->attributes['salt'].$password)){
                    $response['status'] = "Failure";
                    $response['message'] = "Invalid Password";
                }else{
                    $criteria = new CDbCriteria;
                    $criteria->condition = "is_profile = 1 AND user_id = ".$record->attributes['user_id'];

                    $dp = UserAlbum::model()->findAll($criteria);

                    $response['status'] = "Success";
                    $response['user_id'] = $record->attributes['user_id'];
                    $response['user_type'] = $record->attributes['role'];
                    $response['full_name'] = $record->attributes['display_name'];
                    $response['profile_pic_url'] = $record->attributes['profile_pic_url'];

                    if(!empty($dp)){
                        $response['user_dp'] = Yii::app()->getBaseUrl(true)."/images/users/".$record->attributes['user_id']."/".$dp[0]->image_url;
                    }else{
                        $response['user_dp'] = "Empty";
                    }
                }
            }
			}catch(Exception $e){
				$response['status'] = "Failure";
				$response['message'] = $e->getMessage();
			}
			echo json_encode($response);
			exit;
		}

	}


	public function actionAppusercreate(){
		$response = array();
		if(isset($_POST)){
			$name = $_POST['name'];
			$email = $_POST['email'];
			$sex = $_POST['sex'];
			$dob = $_POST['dob'];
			$about_me = $_POST['about_me'];
			$city = $_POST['city'];
			$country = $_POST['country'];
			$password = $_POST['password'];
			$latitude = $_POST['latitude'];
			$longitude = $_POST['longitude'];
			$device = $_POST['device'];
            $device_id = $_POST['device_id'];
            $fb_id = $_POST['fb_id'];
            $is_fb = $_POST['is_fb'];
			$user_dp = $_FILES['profile_pic'];
			$fb_dp = $_FILES['profile_pic_url'];

			$string = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
			$length = 6;

			$model=new Users;
			$response = array();

			$model->name = $name;
			$model->email = $email;
			$model->sex = $sex;
			$model->dob = $dob;
			$model->about_me = $about_me;
			$model->city = $city;
			$model->country = $country;
			$model->notification = 1;
			$model->status = 1;
			$model->fb_id = $fb_id;
			$model->is_fb = $is_fb;
			try {
			if($model->save()){
					$user_id = Yii::app()->db->lastInsertID;

					$user_login = new UserLogin;
						$user_login->user_id = $user_id;

						$user_login->username = $model->email;

						$user_login->salt = substr(str_shuffle($string), 0, $length);

						$user_login->password = md5($user_login->salt.$password);

						$user_login->display_name = $model->name;

						$user_login->latitude = $latitude;

						$user_login->longitude = $longitude;

						$user_login->created_by = 0;

						$user_login->profile_pic_url = $fb_dp;

						$user_login->created_date = strtotime(date('Y-m-d H:i:s'));

						$user_login->role = "App";

						$user_login->device_id = $device_id;

                        $user_login->device = $device;
						try {
						$ofuser = new OfUser();
                        $ofuser->email = $model->email;
                        $ofuser->creationDate = "00".strtotime(date('Y-m-d H:i:s.u'));
                        $ofuser->modificationDate = "00".strtotime(date('Y-m-d H:i:s.u'));

						$usrnme = $user_login->username;
						/*split the string bases on the @ position*/
						$parts = explode('@', $usrnme);
						$preceeding_str_usr = $parts[0];

						$q = preg_replace('/[^a-zA-Z0-9]+/', '', $preceeding_str_usr);
                        $ofuser->username = $q;
                        $ofuser->plainPassword = $password;
						$ofuser->save();
						}catch(Exception $e){
							$response['status'] = "Failure";
							$response['message'] = $e->getMessage();
							echo json_encode($response);
							exit;
						}
						$users_pic = new UserAlbum();

						if(isset($_FILES['profile_pic']['name']) && !empty($_FILES['profile_pic']['name'])){
							$fileName = $_FILES['profile_pic']['name'];

							$users_pic->user_id = $user_id;
							$users_pic->image_url = $fileName;
							$users_pic->position = "0";
							$users_pic->is_profile = 1;

							$folder = 'images/users/' .$user_id.'/';
							if(!is_dir($folder)){
								mkdir($folder,0777,true);
							}

							if($users_pic->save()){

								if(move_uploaded_file($_FILES['profile_pic']['tmp_name'],$folder.$fileName)){
									$response['user_dp'] = Yii::app()->getBaseUrl(true)."/".$folder.$fileName;
								}

							}else{
								$response['user_dp'] = "Failed to Save Image";
							}
						}
					if($user_login->save()){
						$response['status'] = "Success";
						$response['user_id'] = $user_id;
						$response['user_name'] = $model->name;
						$response['user_type'] = $user_login->role;

					}
				}else{
					$response['status'] = "Failure";
					$response['Message'] = "Could Not Save";
 				}
 			}catch(Exception $e){
				$response['status'] = "Failure";
				$response['message'] = $e->getMessage();
			}
		}else{
			$response['status'] ="Failure";
			$response['message'] ="Please Post data";
		}
		echo json_encode($response);
		exit;
	}

	public function actionCheckUser(){
		$response = array();
		if(isset($_POST)){
			$email = $_POST['email'];

			$record = UserLogin::model()->with('user')->findByAttributes(array('username'=>$email));
			if($record===null){
				$response['status'] = "Failure";
				$response['message'] = "Username does not exist";
			}else{
				$response['status'] = "Success";
				$response['message'] = "Username exists";
				$response['user_id'] = $record->attributes['user_id'];
				$response['user_type'] = $record->attributes['role'];
				$response['user_name'] = $record->attributes['display_name'];
			}
		}else{
			$response['status'] = "Failure";
			$response['message'] = "Please Post Details";
			echo json_encode($response);
			exit;

		}

	}

	public function actionAppchangepassword(){

		$response = array();
		$string = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
		$length = 6;

		if(isset($_POST)){
			$user_id = $_POST['user_id'];
			$new_password = $_POST['new_password'];

			$user_login = UserLogin::model()->findByAttributes(array('user_id'=>$user_id));

			$user_login->salt = substr(str_shuffle($string), 0, $length);

			$user_login->password = md5($user_login->salt.$new_password);

			if($user_login->save()){
				$response['status'] = "Success";
				$response['user_id'] = $user_login->user_id;
				echo json_encode($response);
				exit;
			}
			else{
				$response['status'] = "Failure";
				$response['message'] = "Fail to change password";
				$response['user_id'] = $user_id;
				echo json_encode($response);
				exit;
			}
		}
		$response['status'] = "Failure";
		$response['message'] = "Please Post Details";
		echo json_encode($response);
		exit;
	}

	public function actionViewprofile(){
		$response = array();

		if(isset($_POST)){
			$user_id = $_POST['user_id'];
			try {
				$model = Users::model()->with('userAlbums')->findByPk($user_id);
				if (!empty($model) && isset($model)) {
					$response['status'] = "Success";
					$response['name'] = $model->name;
					$response['email'] = $model->email;
					$response['sex'] = $model->sex;
					$response['dob'] = $model->dob;
					$response['about_me'] = $model->about_me;
					$response['city'] = $model->city;
					$response['country'] = $model->country;
					$count = 0;
					foreach ($model->userAlbums as $r) {
						$response['images'][$count]['image_url'] = Yii::app()->getBaseUrl(true) . "/images/users/" . $model->id . "/" . $r->image_url;
						$response['images'][$count]['position'] = $r->position;
						$response['images'][$count]['is_profile'] = $r->is_profile;
						$count++;
					}
				}
			} catch(Exception $e){
				$response['status'] = "Failure";
				$response['message'] = $e->getMessage();
			}
		}else{
			$response['status'] = "Failure";
			$response['message'] = "Please Post Values";
		}
		echo json_encode($response);
		exit;
	}

	public function actionEditProfile(){

			$response = array();

			if(isset($_POST)){
				$user_id = $_POST['user_id'];

	            $string = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
				$length = 6;

				$model = Users::model()->findByPk($user_id);
				$model->attributes = $_POST['User'];

				if($model->update()){

						$usr_login = UserLogin::model()->findByAttributes(array('user_id'=>$user_id));

	                    if(!empty($usr_login)) {
	                        $usr_login->username = $model->email;
	                        $usr_login->display_name = $model->name;
	                        if($usr_login->update()) {
		                        $response['status'] = "Success";
		                        $response['user_id'] = $user_id;
		                        $response['user_name'] = $model->name;
		                        $response['user_type'] = $usr_login->role;
	                    	}
	                    }

						if(isset($_FILES['User']['name']['profile']) && !empty($_FILES['User']['name']['profile'])){

							$ctr = 0;

							foreach($_FILES['User']['name']['profile'] as $r){

								$fileName = $r['name'];

								$get_position = $_POST['User']['profile'][$ctr]['position'];

								$user_album = UserAlbum::model()->findByAttributes(array('user_id'=>$user_id,'position'=>$get_position));


								if(!empty($user_album)){

									$user_album->user_id = $user_id;
									$user_album->image_url = $fileName;
									if($get_position === "0"){
										$user_album->position = "0";
										$user_album->is_profile = 1;
									}else{
										$user_album->position = $get_position;
										$user_album->is_profile = 0;
									}

									$folder = 'images/users/' .$user_id.'/';
									if(!is_dir($folder)){
										mkdir($folder,0777,true);
									}

									if($user_album->save()){

										if(move_uploaded_file($_FILES['User']['tmp_name']['profile'][$ctr]['name'],$folder.$fileName)){

										}

									}else{
									}

								}else{

									$user_pic = new UserAlbum();
									$user_pic->user_id = $user_id;
									$user_pic->image_url = $fileName;
									if($get_position === "0"){
										$user_pic->position = "0";
										$user_pic->is_profile = 1;
									}else{
										$user_pic->position = $get_position;
										$user_pic->is_profile = 0;
									}

									$folder = 'images/users/' .$user_id.'/';
									if(!is_dir($folder)){
										mkdir($folder,0777,true);
									}

									if($user_pic->save()){

										if(move_uploaded_file($_FILES['User']['tmp_name']['profile'][$ctr]['name'],$folder.$fileName)){
											//$response['user_dp'][$ctr] = Yii::app()->getBaseUrl(true)."/".$folder.$fileName;
										}

									}else{
										//$response['user_dp'][$ctr] = "Failed to Save Image";
									}
								}
								$ctr++;
							}
						}

				}else{
					$response['status'] = "Failure";
				}
			}else{
				$response['status'] ="Failure";
				$response['message'] ="Please Post data";
			}
			echo json_encode($response);
			exit;


	}

	public function actionNearby(){

		if(isset($_POST)){
            $latitude = $_POST['latitude'];
            $longitude = $_POST['longitude'];
            $city = $_POST['city'];

            $criteria = new CDbCriteria();
            $criteria->select = "user_id,display_name,role, (3959 * ACOS(COS( RADIANS('".$latitude."')) * COS(RADIANS(`latitude`)) * COS(RADIANS(`longitude`) - RADIANS('".$longitude."')) + SIN( RADIANS('".$latitude."')) * SIN(RADIANS(`latitude`)))) AS distance";
            $criteria->having = "distance < 20";
			$criteria->condition = "role = 'App'";
            $criteria->order = "distance ASC";
            $criteria->with = array('user'=>array('with'=>array('userAlbums')));

            $model = UserLogin::model()->findAll($criteria);
			$response = array();
            if(!empty($model)){
                $count = 0;
                foreach($model as $r){

					$response['status'] = "Success";
					$response['user'][$count]['name']  = $r->display_name;
					$response['user'][$count]['id']  = $r->user_id;
					$response['user'][$count]['sex']  = $r->user->sex;
					$response['user'][$count]['dob']  = $r->user->dob;
					$response['user'][$count]['about_me']  = $r->user->about_me;
                    if(!empty($r->user)){
						if(!empty($r->user->userAlbums)){
						   $counter = 0;
							foreach($r->user->userAlbums as $a){
								$response['user'][$count]['images'][$counter]  = $a->image_url;
								$counter++;
							}
						}
                    }
                    $count++;
                }
            }else{
                $response['status'] = "Failure";
                $response['message'] = "No Users Nearby";
            }
            echo json_encode($response);
            exit;
        }
	}

	public function actionForgotPassword(){
		$response = array();
		if(isset($_POST)){
			$email = $_POST['email'];
			$string = "23456789abcdefghkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ";
			$length = 6;
			$record = UserLogin::model()->findByAttributes(array('username'=>$email));
			if($record===null){
				$response['status'] = "Failure";
				$response['message'] = "Username does not exist";
			}else{
				$record->salt = substr(str_shuffle($string), 0, $length);
				$record->password = md5($record->salt.$record->salt);
				if($record->update()){
					$subject = "Whats42nite Password Change";

					$body = 'Dear '.$record->display_name.',
					<br/>
					<br/>
					Your Password has been change.
					<br>
					<br>
					Your Account password can be set by clicking on the link below: <br/>'.$record->salt.'
					<br/>
					<br/>
					You can Reset the Password as per your need once you login through the App.
					<br/>
					<br/>
					<br/>
					All the best,
					<br/>
					Whats42nite Team
					<br/>
					info@whats42nite.com';

					$headers = 'From: <noreply@whats42nite.com>' . "\r\n";
					$headers .= "MIME-Version: 1.0\r\n";
					$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
					mail($record->username,$subject,$body,$headers);

					$response['status'] = "Success";
					$response['message'] = "Mail has been sent to your Email Address along with new password";
				}
			}
		}else{
			$response['status'] = "Failure";
			$response['message'] = "Please Post Details";
			echo json_encode($response);
			exit;
		}

	}
}
?>