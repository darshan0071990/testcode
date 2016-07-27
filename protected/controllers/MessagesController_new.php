<?php

class MessagesController extends Controller
{
	public function filters()
	{
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
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view','create','reply','messages_view','trainee_msgs','users_all_msgs','inbox'),
				'roles'=>array('Trainee'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update','reply','users_all_msgs'),
				'roles'=>array('Admin','Trainer','Manager'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete','reply'),
				'roles'=>array('Admin','Trainer','Manager'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	public function actionInbox(){
		
		$user_role = Yii::app()->user->roles;
		$user_id = Yii::app()->user->id;
		
		$messages_criteria = new CDbCriteria;		
		
		$messages_criteria->order = 't.created_date DESC';
		
		
		if($user_role == "Trainer" || $user_role == "Trainer"){
			$messages_criteria->with = array('Parent_Message'=>array(
					'with'=>'Course'
				));
		}else{ // for admin and manager
			$messages_criteria->with = array('All_Parent_Message'=>array(
					'with'=>'Course'
				));
		}
		
		$model =  Messages::model()->findAll($messages_criteria);
		
		echo $this->renderPartial('inbox',array('model'=>$model),true);
		exit;
	}
	
	public function actionView($id){
		if(isset($_GET['layout'])){
			$this->layout = $_GET['layout'];
		}
		
		$user_role = Yii::app()->user->roles;
		$user_id = Yii::app()->user->id;
		$message_index = array();
		if($user_role == "Trainer"){
			$criteria = new CDbCriteria;
			$criteria->condition = "t.sent_user_id = ".$user_id. " OR t.received_user_id= ".$user_id. " AND course_id = ".$id;
			$message_index = MessageUser::model()->with(array('Messages','SentUser','ReceivedUser'))->findAll($criteria);
			
		}else if($user_role == "Admin" || $user_role == "Manager"){
			
			$message_index = MessageUser::model()->with(array('Messages','SentUser','ReceivedUser'))->findAll(array('condition'=>'message_id = '.$id.' AND parent_id=0 OR received_user_id = '.$user_id));
			
			if(!empty($message_index)){
				$user_replies = MessageUser::model()->with(array('Messages'=>array('order'=>'messages.created_date DESC'),'SentUser','ReceivedUser','Course'))->findAll(array('condition'=>'parent_id = '.$message_index[0]->id.' AND t.course_id = '.$_GET['course_id'].' AND t.received_user_id = '.$user_id));
			}
			
			echo $this->renderPartial('view',array('model'=>$message_index,'sub_replies'=>$user_replies),true,true);
			exit();
		}
		else {
			$criteria = new CDbCriteria;
			$criteria->condition = "t.sent_user_id = ".$user_id. " OR t.received_user_id= ".$user_id. " AND course_id = ".$id;
			$message_index = MessageUser::model()->with(array('Messages','SentUser','ReceivedUser'))->findAll($criteria);
		}
		
		echo $this->renderPartial('view',array('model'=>$message_index),true,true);

		exit();
	}
	
	public function actiontrainee_msgs($id){
		if($id == 0){
			$this->actionInbox();
		}else{
			$this->Sub_message($id);
		}		
	}
	
	public function Sub_message($id){
		$user_id = Yii::app()->user->id;
		
		$criteria = new CDbCriteria;
		$criteria->condition = "t.parent_id = 0 AND t.message_id = ".$id." AND ( t.received_user_id = ".$user_id." OR t.sent_user_id = ".$user_id.")";
		
		$message_index = MessageUser::model()->with(array('Messages','SentUser','Course'))->findAll($criteria);
		
		if(!empty($message_index)){
				$parent_id =  $message_index[0]->id;
				$result = array();
				$sub_replies = MessageUser::model()->with(array('Messages','SentUser','Course'))->findByAttributes(array('parent_id'=>$parent_id));
				
				if(!empty($sub_replies)){
					$parent_id = $sub_replies->id;
					
					$criterias = new CDbCriteria;
					$criterias->condition = "t.parent_id = ".$parent_id;
					$result[] = $sub_replies;
					$sub_replies = array_merge($result,MessageUser::model()->with(array('Messages','SentUser'))->findAll($criterias));
				}
		}
		$message_body = $this->renderPartial('trainee_msgs',array('model'=>$message_index[0],'sub_replies'=>$sub_replies,'main_id'=>$parent_id),true,true);
		$data['message_body'] = $message_body;
		$data['id'] = $id;
		
		echo $this->renderPartial('trainee_msgs',array('model'=>$message_index[0],'sub_replies'=>$sub_replies,'main_id'=>$parent_id),false,true);
		
		exit;
	}
	
	public function actionUsers_msgs(){
		$user_id = Yii::app()->user->id;
		
		$users_msgs = MessageUser::model()->with(array('Messages','SentUser','ReceivedUser'))->findAll();
	
	
	}
	public function actionCreate()
	{
		if(isset($_GET['layout'])){
			$this->layout = $_GET['layout'];
		}
		
		$model=new Messages;
		
		$user_role = Yii::app()->user->roles;
		
		if(isset($_POST['Messages'])){
			$model->attributes=$_POST['Messages'];
			$model->created_date = strtotime(date('Y-m-d H:i:s'));
			$model->save();
			$message_id = Yii::app()->db->getLastInsertID();
			if(isset($_files) && !empty($_files)){
				$files = CUploadedFile::getInstance($model, 'file_name');
				$model->file_name = $files;
				$folder = 'files/messages/' .$message_id.'/';
				
				if(!is_dir($folder)){
					mkdir($folder,0777,true);
				}
				
				$model->url->saveAs($folder. $files->name); //move files in directory..

			}
			
			if($user_role == "Trainee"){
				if(isset($_POST['Message_User'])){
				
					$message_user = new MessageUser;
						$message_user->message_id = $message_id;
						$message_user->sent_user_id = $_POST['Message_User']['sent_user_id'];
						$message_user->received_user_id = $_POST['Message_User']['received_user_id'];
						$message_user->course_id = $_POST['Message_User']['course_id'];
						$message_user->parent_id = $_POST['Message_User']['parent_id'];
					$message_user->save();
				
				}
			}else{
				if(isset($_POST['course_id'])){
					$course_id = $_POST['course_id'];
					$get_user_course = UserCourse::model()->findAllByAttributes(array('course_id'=>$course_id));
					foreach($get_user_course as $cc){
						$message_user = new MessageUser;
							$message_user->message_id = $message_id;
							$message_user->sent_user_id = Yii::app()->user->id;
							$message_user->received_user_id = $cc->user_id;
							$message_user->course_id = $course_id;
							$message_user->parent_id = $_POST['Message_User']['parent_id'];
						$message_user->save();
					}
					}
			}
			$this->actionIndex();
			exit;
		}
		$data['model'] = $model;
		echo $this->renderPartial('create',array('data'=>$data),false,true);
		exit;
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	 
	public function actionreply(){
		
		$model=new Messages;
		
		$user_role = Yii::app()->user->roles;
		
		if(isset($_POST['Messages'])){
			$model->attributes=$_POST['Messages'];
			$model->created_date = strtotime(date('Y-m-d H:i:s'));
			$model->save();
			$message_id = Yii::app()->db->getLastInsertID();
			if(isset($_files) && !empty($_files)){
				$files = CUploadedFile::getInstance($model, 'file_name');
				$model->file_name = $files;
				$folder = 'files/messages/' .$message_id.'/';
				
				if(!is_dir($folder)){
					mkdir($folder,0777,true);
				}
				
				$model->url->saveAs($folder. $files->name); //move files in directory..

			}
			
			if(isset($_POST['Message_User'])){
				$message_user = new MessageUser;
					$message_user->attributes=$_POST['Message_User'];
					$message_user->message_id = $message_id;
				$message_user->save();
			}
					
			
			echo json_encode(array('model'=>$model,'message_usr'=>$message_user));
			exit;
		}
		
		echo $this->renderPartial('create',array('model'=>$model),false,true);
		exit;
	}
	
	public function actionUpdate($id){
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Messages']))
		{
			$model->attributes=$_POST['Messages'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('update',array(
			'model'=>$model,
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
	public function actionIndex(){
		if(isset($_GET['layout'])){
			$this->layout = $_GET['layout'];
		}
		
		$user_role = Yii::app()->user->roles;
		$user_id = Yii::app()->user->id;
		
		$message_index = MessageUser::model()->with(array('Messages','SentUser','ReceivedUser','Course'))->findAll(array('condition'=>'t.parent_id = 0','group'=>'message_id','order'=>'messages.created_date DESC'));

			
		
		$this->render('index',array('message_index'=>$message_index));
	}
	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		if(isset($_GET['layout'])){
			$this->layout = $_GET['layout'];
		}
		
		$model=new Messages('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Messages']))
			$model->attributes=$_GET['Messages'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Messages the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Messages::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Messages $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='messages-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	
	public function actionUsers_all_msgs(){
		
		$msg_id = $_GET['message_id'];
		$course_id = $_GET['course_id'];
		$received_user_id = $_GET['received_user'];
		$created_time = $_GET['created_time'];
		
		$user_id = Yii::app()->user->id;
		
		$replies = MessageUser::model()->with(array('Messages'=>array('order'=>'messages.created_date DESC'),'SentUser','ReceivedUser'))->findAll(array('condition'=>'t.course_id ='.$course_id.' AND t.parent_id = '.$msg_id.' AND messages.created_date > '.$created_time));
		
		if(!empty($replies)){
		
			$last_date = $replies[0]->Messages->created_date;
			$data = "<div class='accordion-inner'>";
			$data .= "From: ".$replies[0]->SentUser->name." At: ". date('d/M/Y:H:i:s',$replies[0]->Messages->created_date)."<br/>"; 
			$data .= $replies[0]->Messages->description."<br/>";
			if(!empty($replies[0]->Messages->file_name)){
				
				$data .= "File : <a href='".Yii::app()->request->baseUrl."/files/messages/".$replies[0]->Messages->id."/".$replies[0]->Messages->file_name."'>".$replies[0]->Messages->file_name."</a>";
			}
			
			$data .= "<a class='reply' style='cursor:pointer' data-parent='".$replies[0]->id."' data-course_id= '".$replies[0]->course_id."' data-sent_user_id ='".$replies[0]->sent_user_id."' data-target='#message_reply_modal' data-toggle='modal'>Reply<i data-toggle='tooltip' data-placement='top' title='Reply to ".$replies[0]->SentUser->name."' class='icon-right fa fa-reply'></i></a></div>";
				  
			if(!empty($replies)){
				foreach($replies as $c){
					$sub_replies = MessageUser::model()->with(array('Messages','SentUser','ReceivedUser'))->findAll(array('condition'=>'t.course_id ='.$course_id.' AND t.parent_id = '.$c->id));
					if(!empty($sub_replies)){
						//$data .= "<a class='reply' style='cursor:pointer' data-parent='".$sub_replies[0]->id."' data-course_id= '".$sub_replies[0]->course_id."' data-sent_user_id ='".$sub_replies[0]->sent_user_id."' data-target='#message_reply_modal' data-toggle='modal'>Reply<i data-toggle='tooltip' data-placement='top' title='Reply to ".$sub_replies[0]->SentUser->name."' class='icon-right fa fa-reply'></i></a></div>";
						$last_date = $sub_replies[0]->Messages->created_date;
					}

				}
			}
		}else{
		$data ="";
		$last_date=$created_time;
		}
		//print_r($replies);
		echo json_encode(array('html'=>$data,'last_date'=>$last_date));
		exit;
	}
	
	public function actionMessages_view(){
		
		$user_id = Yii::app()->user->id;
		$data = array();
		
		$messages_criteria = new CDbCriteria;		
		$messages_criteria->with = array('Parent_Message'=>array(
					'with'=>'Course'
				));
		$messages_criteria->order = 't.created_date DESC';
		$model =  Messages::model()->findAll($messages_criteria);

		if($id != 0){
			$criteria = new CDbCriteria;
			$criteria->condition = "t.parent_id = 0 AND t.message_id = ".$id." AND ( t.received_user_id = ".$user_id." OR t.sent_user_id = ".$user_id.")";
			
			$message_index = MessageUser::model()->with(array('Messages','SentUser','Course'))->findAll($criteria);
			
			if(!empty($message_index)){
					$parent_id =  $message_index[0]->id;
					$result = array();
					$sub_replies = MessageUser::model()->with(array('Messages','SentUser','Course'))->findByAttributes(array('parent_id'=>$parent_id));
					
					if(!empty($sub_replies)){
						$parent_id = $sub_replies->id;
						
						$criterias = new CDbCriteria;
						$criterias->condition = "t.parent_id = ".$parent_id;
						$result[] = $sub_replies;
						$sub_replies = array_merge($result,MessageUser::model()->with(array('Messages','SentUser'))->findAll($criterias));
					}
			}
			$message_body = $this->renderPartial('trainee_msgs',array('model'=>$message_index[0],'sub_replies'=>$sub_replies,'main_id'=>$parent_id),true,true);
			$data['message_body'] = $message_body;
			$data['id'] = $id;
		}
		
		$data['model'] = $model;
		$this->render('messages_view',array('data'=>$data));
	}
	
}
