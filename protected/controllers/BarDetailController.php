<?php


class BarDetailController extends Controller
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

			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view'),
				'users'=>array('*'),

			),

			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update'),
				'users'=>array('BarOwner','Admin'),
			),

			array('allow', // allow admin user to perform 'admin' and 'delete' actions

				'actions'=>array('delete'),
				'users'=>array('BarOwner','admin'),
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
        /*if($user_role == "BarOwner"){
            $count_clubs = BarDetail::model()->findAll(array('condition'=>'created_by = '.Yii::app()->user->id));
            if(count($count_clubs) > 1){
                echo "You are not allowed to add more Bars";
                exit;
            }
        }*/
        
		$model=new BarDetail;

		if (isset($_POST['BarDetail'])) {

			$model->attributes=$_POST['BarDetail'];
			$model->created_by = Yii::app()->user->id;
			$model->created_date = strtotime(date('Y-m-d H:i:s'));
			
			$files = CUploadedFile::getInstance($model, 'featured_pic');
			
			if(isset($files) && !empty($files)){
				$model->featured_pic = $files;
				
			}	
				
			if ($model->save()) {

				$club_id = Yii::app()->db->getLastInsertID();
				$folder = 'images/bar/' .$club_id.'/';
				
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
		$model = new BarDetail('update');
		$model=$model->findByPk($id);

		if (isset($_POST['BarDetail'])) {

			if(empty($_POST['BarDetail']['featured_pic'])){
				$_POST['BarDetail']['featured_pic'] = $model->featured_pic;
			}

			$model->attributes=$_POST['BarDetail'];

			$files = CUploadedFile::getInstance($model, 'featured_pic');

			if(isset($files) && !empty($files)){
				$model->featured_pic = $files;
			}
			
			if($model->update()) {
				if (isset($files) && !empty($files)) {
					{
						$folder = 'images/bar/' . $id . '/';
						if (!is_dir($folder)) {
							mkdir($folder, 0777, true);
						}
						$model->featured_pic->saveAs($folder . $files->name); //move files in directory..
					}
					$this->redirect(array('view', 'id' => $model->id));
					exit;
				}
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
		if($user_role == "BarOwner"){
			$criteria->condition = 'created_by = '.Yii::app()->user->id;
			
		}
		$dataProvider=new CActiveDataProvider('BarDetail',array('criteria'=>$criteria));
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
		
		$model=new BarDetail('search');

		$model->unsetAttributes();  // clear any default values

		if (isset($_GET['BarDetail'])) {

			$model->attributes=$_GET['BarDetail'];

		}



		$this->render('admin',array(

			'model'=>$model,

		));

	}



	/**

	 * Returns the data model based on the primary key given in the GET variable.

	 * If the data model is not found, an HTTP exception will be raised.

	 * @param integer $id the ID of the model to be loaded

	 * @return BarDetail the loaded model

	 * @throws CHttpException

	 */

	public function loadModel($id)

	{

		$model=BarDetail::model()->findByPk($id);

		if ($model===null) {

			throw new CHttpException(404,'The requested page does not exist.');

		}

		return $model;

	}



	/**

	 * Performs the AJAX validation.

	 * @param BarDetail $model the model to be validated

	 */

	protected function performAjaxValidation($model)

	{

		if (isset($_POST['ajax']) && $_POST['ajax']==='bar-detail-form') {

			echo CActiveForm::validate($model);

			Yii::app()->end();

		}

	}

}