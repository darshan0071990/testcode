<?php


class ClubDetailController extends Controller
{

	
	/**

	 * @return array action filters

	 */

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

				'actions'=>array('create','update'),

				'users'=>array('@'),

			),

			array('allow', // allow admin user to perform 'admin' and 'delete' actions

				'actions'=>array('admin','delete'),

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
		$this->render('view',array(

			'model'=>$this->loadModel($id),

		));

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
		
		$user_role = Yii::app()->user->roles;
        /*if($user_role == "ClubOwner"){
            $count_clubs = ClubDetail::model()->findAll(array('condition'=>'created_by = '.Yii::app()->user->id));
            if(count($count_clubs) > 1){
                echo "You are not allowed to create more Clubs";
                exit;
            }
        }*/
		

		$model = new ClubDetail;

		if (isset($_POST['ClubDetail'])) {

			$model->attributes=$_POST['ClubDetail'];
			$model->created_by = Yii::app()->user->id;
			$model->created_date = strtotime(date('Y-m-d H:i:s'));
			
			$files = CUploadedFile::getInstance($model, 'featured_pic');
			
			if(isset($files) && !empty($files)){
				$model->featured_pic = $files;
			}	
				
			if ($model->save()) {

				$club_id = Yii::app()->db->getLastInsertID();
				$folder = 'images/club/' .$club_id.'/';
				
				if(!is_dir($folder)){
					mkdir($folder,0777,true);
				}
				$model->featured_pic->saveAs($folder. $files->name); //move files in directory..
				
				$this->redirect(array('view','id'=>$model->id));
				exit;

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
		
		$model = new ClubDetail('update');
		$model = $model->findByPk($id);
		
		
		if (isset($_POST['ClubDetail'])) {

			if(empty($_POST['ClubDetail']['featured_pic'])){
                $_POST['ClubDetail']['featured_pic'] = $model->featured_pic;
            }
			
			$model->attributes=$_POST['ClubDetail'];

			$files = CUploadedFile::getInstance($model, 'featured_pic');
			
			if(isset($files) && !empty($files)){
				$model->featured_pic = $files;
			}
			
			if ($model->update()) {
				
				if(isset($files) && !empty($files)){
						$folder = 'images/club/'.$id.'/';
				
					if(!is_dir($folder)){
						mkdir($folder,0777,true);
					}
					$model->featured_pic->saveAs($folder.$files->name); //move files in directory..
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
		$criteria = new CDbCriteria;
		$user_role = Yii::app()->user->roles;
		$criteria->order = 'id DESC';
		
		if($user_role == "ClubOwner"){
			$criteria->condition = 'created_by = '.Yii::app()->user->id;
			
		}
		$dataProvider=new CActiveDataProvider('ClubDetail',array('criteria'=>$criteria));

		$this->render('index',array(

			'dataProvider'=>$dataProvider,

		));

	}



	/**

	 * Manages all models.

	 */

	public function actionAdmin()

	{

		if(isset($_GET['layout'])){
			$this->layout = $_GET['layout'];
		}
		
		$model=new ClubDetail('search');

		$model->unsetAttributes();  // clear any default values

		if (isset($_GET['ClubDetail'])) {

			$model->attributes=$_GET['ClubDetail'];

		}



		$this->render('admin',array(

			'model'=>$model,

		));

	}



	/**

	 * Returns the data model based on the primary key given in the GET variable.

	 * If the data model is not found, an HTTP exception will be raised.

	 * @param integer $id the ID of the model to be loaded

	 * @return ClubDetail the loaded model

	 * @throws CHttpException

	 */

	public function loadModel($id)

	{

		$model=ClubDetail::model()->findByPk($id);

		if ($model===null) {

			throw new CHttpException(404,'The requested page does not exist.');

		}

		return $model;

	}



	/**

	 * Performs the AJAX validation.

	 * @param ClubDetail $model the model to be validated

	 */

	protected function performAjaxValidation($model)

	{

		if (isset($_POST['ajax']) && $_POST['ajax']==='club-detail-form') {

			echo CActiveForm::validate($model);

			Yii::app()->end();

		}

	}

}