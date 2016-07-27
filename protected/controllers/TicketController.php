<?php

class TicketController extends Controller
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
				'users'=>array('admin'),
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
		
		$model = Ticket::model()->with('Users')->findByPk($id);
		
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
	
	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		if(isset($_GET['layout'])){
			$this->layout = $_GET['layout'];
		}
		
		$model=new Ticket;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Ticket']))
		{
			
			$model->attributes = $_POST['Ticket'];
			$model->created_date = strtotime(date('Y-m-d H:i:s'));
			$model->updated_date = $model->created_date;
			$model->user_id = Yii::app()->user->id;
			
			$files = CUploadedFile::getInstance($model, 'screenshot');
				
			$model->screenshot = $files;
			
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

		$this->render('create',array(
			'model'=>$model,
		));
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
		
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Ticket']))
		{
			$model->attributes=$_POST['Ticket'];
			
			if($model->save()){
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
		$criteria=new CDbCriteria;
		
		$criteria->with = array(
			'Users' => array(
			'condition' => 'user_id = '.Yii::app()->user->id,
		)); 
		
		$dataProvider=new CActiveDataProvider('Ticket',array('criteria'=>$criteria));
		
		//print_r($dataProvider);
		//exit;
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Ticket('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Ticket']))
			$model->attributes=$_GET['Ticket'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Ticket the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Ticket::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Ticket $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='ticket-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
