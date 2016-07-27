<?php

class TicketsController extends Controller
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
				'actions'=>array('index','view'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin'),
				'users'=>array('Admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
	public function actionIndex()
	{
		$criteria=new CDbCriteria;
		
		$admin_id = Config::model()->findByAttributes(array('name'=>'ADMIN_USER_ID'));
		
		$criteria->condition = 'user_id = '.$admin_id->value;
		
		$dataProvider=new CActiveDataProvider('Tickets',array('criteria'=>$criteria));
		
		
		$this->render('index',array(
			'dataProvider'=>$dataProvider,'admin'=>$admin_id->value
		));
	}
	
	public function actionCreate()
	{
		if(isset($_GET['layout'])){
			$this->layout = $_GET['layout'];
		}
		
		$model=new Tickets;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Tickets']))
		{
			$model->attributes = $_POST['Tickets'];
			$model->created_date = strtotime(date('Y-m-d H:i:s'));
			
			$files = CUploadedFile::getInstance($model, 'screenshot');
				
			$model->attachment = $files;
			
			if($model->validate() && $model->save()){
				
				$folder = 'files/tickets/' .Yii::app()->db->getLastInsertId().'/';
				
				if(!is_dir($folder)){
					mkdir($folder,0777,true);
				}
				
				if(!empty($files)){
					$model->screenshot->saveAs($folder. $files->name); //move files in directory..
				}
				if($model->save()){
					$this->redirect(array('view','id'=>$model->id));
				}
			}
		}
		$this->render('create',array('model'=>$model));
	}
	
	public function actionView($id)
	{
		if(isset($_GET['layout'])){
			$this->layout = $_GET['layout'];
		}
		
		$model = Tickets::model()->findByPk($id);
		
		$ticket = TicketReply::model()->findAll(array('condition'=>'ticket_id ='.$id,"order"=>"id DESC"));
		
		if(isset($_POST['TicketReply'])){
			
			$ticket_reply = new TicketReply;
			
			$ticket_reply->attributes=$_POST['TicketReply'];
			$ticket_reply->post_date = strtotime(date('Y-m-d H:i:s'));
			
			if($ticket_reply->save()){
				$this->render('view',array('model'=>$model,'ticket'=>$ticket));
			}
			
		}
		$this->render('view',array('model'=>$model,'ticket'=>$ticket));
	}
	
	// Uncomment the following methods and override them if needed
	/*
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'inlineFilterName',
			array(
				'class'=>'path.to.FilterClass',
				'propertyName'=>'propertyValue',
			),
		);
	}

	public function actions()
	{
		// return external action classes, e.g.:
		return array(
			'action1'=>'path.to.ActionClass',
			'action2'=>array(
				'class'=>'path.to.AnotherActionClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	*/
}