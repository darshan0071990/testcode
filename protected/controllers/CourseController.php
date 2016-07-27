<?php

class CourseController extends Controller
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
			array('deny', 
				'users'=>array('?'),
			),
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('course_list','coursesummary','course_view','viewcourse','coursedetail','course_library','course_details','subject_content','user_attendence'),
				'roles'=>array('Trainee'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('view','create','update','index','chapters_listing','documents','userAssign','groupcourse','deleteUserAssign','assign','create_category','questions','add_questions','allnames','listByName','groupCourseAssign','deleteGroupCourseAssign','autocomplete','courseUsersList','add_subject','subject_content'),
				'roles'=>array('Admin','Manager','Trainer'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('delete','admin'),
				'roles'=>array('Admin,Manager'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	
	
	public function actionCourse_list(){      //Trainee View Courses
	
		$user_id = Yii::app()->user->id;
		
		$dataProvider = new CArrayDataProvider(UserCourse::model()->with(array('Course'=>array('with'=>'CourseTrainer','condition'=>'Course.active != 1'),'Users'))->findAllByAttributes(array('user_id'=>$user_id)));
		
		//print_r($dataProvider->getdata());
		//exit;
		$this->render('course_list',array('dataProvider'=>$dataProvider));
	}
	
	public function actionView($id){
		
		$check_structure = Course::model()->findByPk($id);
		
		if($check_structure->structure_type == "1"){
		
			$criteria = new CDbCriteria;
			$criteria->condition = 'course_id ='.$id;
			
			$dataProvider= new CActiveDataProvider('Subject',array('criteria'=>$criteria));
			
			$this->render('subjects',array('dataProvider'=>$dataProvider));
			exit;
		
		}else {
			
			$model=Course::model()->with(array('CourseChapters'=>array('with'=>array('CourseDocuments'=>array('order'=>'CourseDocuments.sort_no','with'=>'DocumentType'),'Poll'=>array('with'=>'DocType'),'Question'),'order'=>'CourseChapters.sort_no')))->findByPk($id);
		
			$document_list = DocumentType::model()->findAll();
		
			$this->render('view',array('model'=>$model,'document_list'=>$document_list));
		
		}
	}
	
	public function actionGroupcourse($id){
		$model = Course::model()->with('CourseCategory')->findByPk($id);
		
		$group_course= new GroupCourse;
		
		if(isset($_POST['GroupCourse']))
		{
			  foreach ($_POST['GroupCourse'] as $r){
					$group_course->group_id = $r;
					$group_course->course_id = $id;
					$group_course->save();
				}
		}
		$this->render('groupcourse',array('model'=>$model,'group_course'=>$group_course));
	}
	
	public function actionListByName(){
		$dependency = new CDbCacheDependency('SELECT MAX(title) FROM courses');
		$result = Yii::app()->db->cache(1000, $dependency)->createCommand('select * from courses where title like "%'.$_GET['name'].'%"')->queryAll();
		echo json_encode($result);
		exit;
	}

	public function actionUserAssign(){
		$course_id = $_GET['id'];
		$user_data = array();
		if(isset($_GET['Users']['id']))
		{
			$user_id = explode(',',$_GET['Users']['id']);
			foreach ($user_id as $ui){
				$user_list = new UserCourse;			  
					$user_list->user_id = $ui;
					$user_list->course_id = $course_id;
					$user_list->assign_date =strtotime(date('Y-m-d H:i:s'));
				if($user_list->save()){
					$reply = Users::model()->findByPk($ui);
					$user_data[] = array('userList'=>$reply->attributes );
				}
			}
		}
		echo json_encode($user_data);
		exit;
	}
	
	public function actionGroupCourseAssign(){
		$group_id = $_GET['group_id'];
		$course_data = array();
		if(isset($_GET['Course']['id']))
		{
			$user_id = explode(',',$_GET['Course']['id']);
			foreach ($user_id as $ui){
				$course_list = new GroupCourse;			  
					$course_list->course_id = $ui;
					$course_list->group_id = $group_id;
					$course_list->assign_date =strtotime(date('Y-m-d H:i:s'));
				if($course_list->save()){
					$reply = Course::model()->findByPk($ui);
					$course_data[] = array('GroupCourse'=>$course_list->attributes,'Course'=>$reply->attributes );
				}
			}
		}
		echo json_encode($course_data);
		exit;
	}
	
	public function actionDeleteUserAssign(){
		$uid = $_GET['uid'];
		$cid = $_GET['id'];

		if(UserCourse::model()->deleteAllByAttributes(array('course_id'=>$cid,'user_id'=>$uid))){
			echo true;
		}else{
			echo false;
		}
		exit;
	}
	
	public function actionDeleteGroupCourseAssign(){
		$cid = $_GET['cid'];
		$gid = $_GET['gid'];

		if(GroupCourse::model()->deleteAllByAttributes(array('course_id'=>$cid,'group_id'=>$gid))){
			echo true;
		}else{
			echo false;
		}
		exit;
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
		$model=new Course;
		
		if(isset($_POST['Course']))
		{
			$model->attributes=$_POST['Course'];
			
			$model->created_date =strtotime(date('Y-m-d H:i:s'));
			
			if(!empty($_POST['Course']['complete_time_specific_date'])){
				
				$complete_date = explode("/",$_POST['Course']['complete_time_specific_date']);
				$complete_dates_string = mktime(0,0,0,$complete_date[1],$complete_date[0],$complete_date[2]);
				$model->complete_time_date = $complete_dates_string ;
			}
			
			if(!empty($_POST['Course']['start_date'])){
				$start_date = explode("/",$_POST['Course']['start_date']);
				$start_date_string = mktime(0,0,0,$start_date[1],$start_date[0],$start_date[2]);
				$model->start_date = $start_date_string;
			}
			
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
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
		
		
		if(isset($_POST['Course']))
		{
			$model->attributes=$_POST['Course'];
			
			if(!empty($_POST['Course']['start_date'])){
				$start_date = explode("/",$_POST['Course']['start_date']);
				$start_date_string = mktime(0,0,0,$start_date[1],$start_date[0],$start_date[2]);
				$model->start_date = $start_date_string;
			}
			if($model->validate()){
				if($model->save())
				$this->redirect(array('view','id'=>$model->id));
			}
			
		}

		$this->render('update',array('model'=>$model,));
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
		$criteria = new CDbCriteria;
		$criteria->with = array('CourseTrainer','UserCourseSTAT','BatchCourseSTAT');
		
		$user_role = Yii::app()->user->roles;
		if($user_role == "Trainer"){
			$criteria->condition = 'assign_trainer = '.Yii::app()->user->id;
		}
		$dataProvider=new CActiveDataProvider('Course',array('criteria'=>$criteria));
		
		//print_r($dataProvider->getdata());
		//exit;
		$this->render('index',array('dataProvider'=>$dataProvider));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Course('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Course']))
			$model->attributes=$_GET['Course'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}
	
	public function actionQuestions($id){
	
		$question_answer = new QuestionAnswer;
		
		if(isset($_POST['QuestionAnswer']))
		{
			$question_answer->attributes=$_POST['QuestionAnswer'];
			if($question_answer->save())
				$this->redirect(array('questions','id'=>$id));
		}
		$this->render('questions',array('question_answer'=>$question_answer));
	}
	
	public function actionAdd_questions(){
		$answer_option = new AnswerOption;
		$questions = new Question ;
		
		//$questions = Questions::model()->with('AnswerOption')->findByPk($id);
		
		$ques_type = new QuestionType;
		
		
		if(isset($_POST['Question']) && !empty($_POST['Question'])){
			
			$questions->setAttributes($_POST['Question']);
			
			if($questions->validate() && $questions->save()){

				$question_id = Yii::app()->db->getLastInsertId();
				if($questions->type_id==1){
					if(isset($_POST['Answer_option']) && !empty($_POST['Answer_option'])){
						foreach($_POST['Answer_option']['option'] as $ao){
							if(!empty($ao)){
								$answer_option = new Answer_option;
									$answer_option->option = $ao;
									$answer_option->question_id = $question_id;
								$answer_option->save();
							}
						}
					}
				}
				Yii::app()->user->setFlash('success', "Data Successfully saved!");
				$model = new Questions;
			}
		}
		$data['answer_option'] = $answer_option;
		$data['questions'] = $questions;
		$this->render('add_questions',array('data'=>$data));
	}
	
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Course the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Course::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Course $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='course-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	
	public function actionAssign($id){
		$course = $this->loadModel($id);	
		$user_group = UserGroup::model()->with('Users')->findAllByAttributes(array('create_by_user_id'=>Yii::app()->user->id));
		$this->render('assign');
	}
	
	public function actionCreate_category(){
		$model = new CourseCategory;
		$model->name = $_GET['CourseCategory']['name'];
		$model->save();	
		$course_id = Yii::app()->db->getLastInsertID();
		echo json_encode(array('id'=>$course_id,'name'=>$model->name));
		exit;
	}
	
	public function actionAutocomplete(){
		$type = $_GET['type'];
		if($type == 'users'){
			$course_result = UserCourse::model()->findAll(array('condition'=>'user_id = '.$_GET['id']));
			
		}elseif($type == 'groups'){
			$course_result = GroupCourse::model()->findAll(array('condition'=>'group_id = '.$_GET['id']));
		}
		
		$course_result_id = CHtml::listData($course_result,'id','course_id');
		$course_id = implode(",",$course_result_id);
		
		if(empty($course_id)){
			$course_id = 0;
		}
		
		$dependency = new CDbCacheDependency('SELECT MAX(title) FROM courses where id not in ('.$course_id.')');
		
		$result = Yii::app()->db->cache(1000, $dependency)->createCommand('select * from courses where title like "%'.$_GET['name'].'%" and id not in ('.$course_id.')')->queryAll();
		echo json_encode($result);
		exit;
	}
	
	public function actionCourse_library(){
		
		$user_id = Yii::app()->user->id;
		
		$dataProvider = new CArrayDataProvider(UserCourse::model()->with(array('Course'=>array('with'=>'CourseTrainer','condition' => 'course_library = 1'),'Users'))->findAll(array('condition'=>'user_id <> '.$user_id)));
		
		$this->render('course_library',array('dataProvider'=>$dataProvider));
	
	}
	
	public function actionCourse_Details($id){
		
		$model=Course::model()->with(array('CourseCategory','CourseTrainer'))->findByPk($id);
		
		$this->render('course_details',array('model'=>$model));
	}
	
	public function actionAdd_Subject(){
		if(isset($_GET)){
			$subjects = new Subject;
				$subjects->attributes = $_GET['Subject'];				
				$subjects->created_date = strtotime(date('Y-m-d H:i:s'));
			$subjects->save();
		}
		exit;
	}
	
	public function actionSubject_Content(){
		
		$user_id = Yii::app()->user->id;
		$user_role = Yii::app()->user->roles;
		
		if(Yii::app()->user->roles != "Trainee"){
			$model = Course::model()->with(array('CourseChapters'=>array('with'=>array('CourseDocuments'=>array('order'=>'CourseDocuments.sort_no','with'=>'DocumentType'),'Poll'=>array('with'=>'DocType'),'Question'),'order'=>'CourseChapters.sort_no','condition'=>'subject_id ='.$_GET['sid'])))->findByPk($_GET['id']);
			
			$document_list = DocumentType::model()->findAll();
			
			$subject = Subject::model()->findByPk($_GET['sid']);
			
			$this->render('view',array('model'=>$model,'document_list'=>$document_list,'subject'=>$subject));
			exit;
		
		}else{
			
			$model = Course::model()->with(array('CourseChapters'=>array('with'=>array('CourseDocuments'=>array('order'=>'CourseDocuments.sort_no','with'=>'DocumentType'),'Poll'=>array('with'=>'DocType'),'Question'),'order'=>'CourseChapters.sort_no','condition'=>'subject_id ='.$_GET['sid'])))->findByPk($_GET['id']);
		
			$subject = Subject::model()->with('UserDocumentProgress')->findByPk($_GET['sid']);
			
			if(!empty($subject->UserDocumentProgress)){
				$user_percent = round($subject->UserDocumentProgress/$subject->total_documents * 100);
			}else{
				$user_percent = 0;
			}
			//print_r($subject);
			//exit;
			
			if(!empty($model->CourseChapters[0]) && !empty($model->CourseChapters[0]->CourseDocuments[0])){
				$user_progress = UserDocumentProgress::model()->findAll(array('condition'=>'user_id ='.$user_id.' AND document_id='.$model->CourseChapters[0]->CourseDocuments[0]->id));
			}
			else{
				$user_progress = array();
			}
				
			//$user_percent = UserDocumentProgress::model()->findByAttributes(array('user_id'=>$user_id,'subject_id'=>$_GET['sid']));
		
			$this->render('//chapter/course_documents',array('model'=>$model,'user_progress'=>$user_progress,'user_percent'=>$user_percent,'subject'=>$subject));
			exit;
		}
	}
	
	public function actionUser_attendence(){
		$user_id = $_GET['user_id'];
		$course_id = $_GET['cid'];
		
		$user_attendence = new UsersCourseAttendence;
			$user_attendence->user_id = $user_id;
			$user_attendence->course_id = $course_id;
			$user_attendence->date = strtotime(date('Y-m-d H:i:s'));
		if($user_attendence->save()){
			return true;
		}else{
			return false;
		}
		exit;
	}
}
