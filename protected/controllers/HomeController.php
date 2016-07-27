<?php
class HomeController extends Controller{
	
	public function filters()
	{
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
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions

				'actions'=>array('city','REST.GET', 'REST.PUT', 'REST.POST', 'REST.DELETE'),
				'users'=>array('*'),

			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('index'),
				'users'=>array('@'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
	
	public function actionIndex(){
	if(Yii::app()->user->isGuest) {
		$this->redirect(Yii::app()->user->loginUrl);
		exit;
	}
		echo $this->layout;
		$user_role = Yii::app()->user->roles;
		if($user_role == "Admin"){
			$result = $this->adminDashboard();
		}else if($user_role == "Trainer"){
			$result = $this->trainerDashboard();
		}else if($user_role == "Trainee") {
			$result = $this->traineeDashboard();
		}
		$this->render('index',array('data'=>$result));
		/*$this->render('index',array(
			'annoucement'=>$annoucement,
		));*/
	}
	
	public function adminDashboard(){

        $user_id = Yii::app()->user->id;
		$data = array();
		
		//User
		$role_child = CHtml::listData(Yii::app()->authManager->getItemChildren(Yii::app()->user->roles),'name','name');
		$user_criteria = new CDbCriteria();
		$user_criteria->with = array('userLogins');
		$user_criteria->addInCondition('role',$role_child);

		$user_criteria->order = 'userLogins.created_date DESC';
		$data['user_count'] = Users::model()->count($user_criteria);
		$data['user'] = new CArrayDataProvider(Users::model()->findAll($user_criteria));

		$html = $this->renderPartial('adminDashboard',array('data'=>$data),true);

		return $html;
	}
	
	public function trainerDashboard(){
		$user_id = Yii::app()->user->id;
		$data = array();
		
		//Announcement
		$criteria = new CDbCriteria;
		$criteria->with = array(
					'User_Login'=>array(
						'select'=>'display_name'
					));
		$criteria->together = true;
		$criteria->condition = 'created_by='.$user_id;
		$criteria->order = 't.created_date DESC';
		$criteria->limit = 5;
		$data['annoucement_count'] = Announcement::model()->count($criteria);
		$data['annoucement'] = new CActiveDataProvider('Announcement',array('criteria'=>$criteria));
			
		/*//Messages
		$messages_criteria = new CDbCriteria;		
		$messages_criteria->with = 'Parent_Message';
		$messages_criteria->order = 'created_date DESC';
		$messages_criteria->limit = 5;
		$data['messages'] =  new CActiveDataProvider('Messages',array('criteria'=>$messages_criteria));*/
		
		//Course
		$course_criteria = new CDbCriteria;
		$course_criteria->with = 'CourseTrainer';
		$course_criteria->condition = 'assign_trainer = '.Yii::app()->user->id;
		$course_criteria->limit = 5;
		$course_criteria->order = 't.created_date DESC';
		$data['course_count'] = Course::model()->count($course_criteria);
		$data['course'] = new CActiveDataProvider('Course',array('criteria'=>$course_criteria));
		
		//User
		$role_child = CHtml::listData(Yii::app()->authManager->getItemChildren(Yii::app()->user->roles),'name','name');
		$user_criteria = new CDbCriteria();
		$user_criteria->with = array('UserLogin');
		$user_criteria->addInCondition('role',$role_child);
		$user_criteria->limit = 5;
		$user_criteria->order = 't.created_date DESC';
		$data['user_count'] = Users::model()->count($user_criteria);
		$data['user'] = new CArrayDataProvider(Users::model()->findAll($user_criteria));
		
		$html = $this->renderPartial('trainerDashboard',array('data'=>$data),true);
		
		return $html;
	}
	
	public function traineeDashboard(){
		$data = array();
		$user_id = Yii::app()->user->id;
		
		//Announcement
		$criteria = new CDbCriteria;
		$criteria->select = 't.*,tu.title AS course_title';
		$criteria->join = 'LEFT JOIN course_announcement ON t.id = course_announcement.announcement_id ';
		$criteria->join .= 'LEFT JOIN user_course ON course_announcement.course_id = user_course.course_id ';
		$criteria->join .= 'LEFT JOIN courses AS tu ON user_course.course_id = tu.id ';
		$criteria->condition = 'user_id='.$user_id;
		$criteria->limit = 5;
		$data['annoucement_count'] = Announcement::model()->count($criteria);
		$data['annoucement'] = new CActiveDataProvider('Announcement',array('criteria'=>$criteria));
			
		/*//Messages
		$messages_criteria = new CDbCriteria;		
		$messages_criteria->with = 'Parent_Message';
		$messages_criteria->order = 'created_date DESC';
		$messages_criteria->limit = 5;
		$data['messages'] =  new CActiveDataProvider('Messages',array('criteria'=>$messages_criteria));*/
		
		//Course
		$course_criteria = new CDbCriteria;
		$course_criteria->with = 'Course';
		$course_criteria->condition = 't.user_id = '.$user_id;
		$course_criteria->limit = 5;
		$data['course_count'] = UserCourse::model()->count($course_criteria);
		$data['course'] = new CActiveDataProvider('UserCourse',array('criteria'=>$course_criteria));
		
		$html = $this->renderPartial('traineeDashboard',array('data'=>$data),true);
		return $html;
	}
	
	public function actionCountry(){

		$country_list = array();
		$criteria = new CDbCriteria;
		$criteria->select = 'country';
		$criteria->group = 'country';
		$criteria->order = 'country ASC';
		$model = Cities::model()->findAll($criteria);
		foreach($model as $r){
			$country_list[] = $r->country;
		}
		echo json_encode($country_list);
		exit;
	}
	public function actionCity(){
		if(isset($_POST)){
			$country = $_POST['country'];
			
			$criteria = new CDbCriteria;
			$criteria->select = 'city,latitude,longitude';
		
			$criteria->order = 'city ASC';
			$criteria->condition = "country LIKE :country";
			$criteria->params = array(':country' => $country);
	
			$model = Cities::model()->findAll($criteria);
			
			echo  CJSON::encode($model);
			exit;
		}
	}

	public function actionLoadcities()
	{
		$data = Cities::model()->findAll('country =:country',
			array(':country'=>$_POST['country']),array('select'=> 'city'));

		$data=CHtml::listData($data,'city','city');

		echo "<option value=''>Select City</option>";
		foreach($data as $value=>$city)
			//echo CHtml::tag('option', array('value'=>$value),CHtml::encode($city),true);
			echo CHtml::tag('option', array('value'=>''.$value),CHtml::encode($city),true);
	}

}
?>