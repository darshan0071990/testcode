<?php

class UserLoginController extends Controller
{	
	public $layout = 'login';
	
	public function actionLogin(){	
		$model=new LoginForm;
		
		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$model->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login())
				$this->redirect(Yii::app()->user->returnUrl);
			else
				Yii::app()->user->setFlash('error','Incorrect Username or Password.');
		}
		// display the login form
		$this->render('login',array('model'=>$model));
	}
	
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(array('login'));
	}
	public function actionForgot_Password(){
		
		$html = "";
		if(isset($_POST['username'])){
			
			$username = $_POST['username'];
			
			$string = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
			$length = 28;
		
			//Query Username
			$record = UserLogin::model()->findByAttributes(array('username'=>$username));
			
			if($record===null){  //If Username does not exists
				
				$html = "<div class='alert'><b>Error!</b>&nbsp;Username does not exist.</div>";
			
			}else{
				
				$reset_token = substr(str_shuffle($string), 0, $length);
				
				$record->saveAttributes(array('reset_password' => 1,'reset_token'=>$reset_token));
				
				$this->forgotMail($record->user_id,$reset_token);
				
				$html = "<div class='alert'><b>Success!</b>&nbsp;Reset Password Link has been sent to your Email.</div>";
								
			}
			echo json_encode($html);
			exit;
		}
		
		$this->render('forgot_password');
	}
	
	public function forgotMail($uid,$reset_token){
		
		$user = UserLogin::model()->findByAttributes(array('user_id'=>$uid));
		
		$reset_url = 'http://whats42nite.com/admin/userLogin/reset_password?token='.$reset_token.'&id='.$uid;
		
		$subject = "Whats42nite Account password reset request";
		
		$body = 'Dear '.$user->display_name.',
				<br/>
				<br/>
				As per your request, your Account password can be reset by clicking on the link below: <br/>'.$reset_url.'
				<br/>
				<br/>
				Once you have clicked on the link, you will be asked to choose a new password.
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
		//$message->replyto = 'service@whats42nite.com';
		$message->from = array('darshan@horizzon.com' => 'Whats42nite');
		//$message->from = array('service@whats42nite.com' => 'Whats42nite');
		
		if(Yii::app()->mail->send($message)){
			return true;
		}else{
			return false;
		}*/
	}
	
	public function actionreset_Password(){
		
		$model = new ResetForm; 
		$flag = false;
		
		if(isset($_GET['token']) && !empty($_GET['token']) && isset($_GET['id']) && !empty($_GET['id'])){
			
			$uid = $_GET['id'];
			$token = $_GET['token'];
			
			$user = UserLogin::model()->findByAttributes(array('reset_token'=>$token,'user_id'=>$uid));
			
			if(!empty($user)){
				
				if($user->reset_password != 1){
				
					Yii::app()->user->setFlash('error','Your Link Expired. Please use Forgot Password again');
					$flag = true;
				}
			}else{
				
				Yii::app()->user->setFlash('error','Your Link Expired. Please use Forgot Password again.');
				$flag = true;
			}
			
		}else {
			
			$uid = 0;
			Yii::app()->user->setFlash('error','Error.');
			$flag = true;
		}
		
		if(isset($_POST['ResetForm'])){
			
			$string = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
			
			$length = 6;
		
			$user_id = $_POST['user_id'];
			
			$user_login = UserLogin::model()->findByAttributes(array('user_id'=>$user_id));
			
			$model->attributes = $_POST['ResetForm'];
			
			$user_login->salt = substr(str_shuffle($string), 0, $length);
				
			$user_login->password = md5($user_login->salt.$_POST['ResetForm']['resetPassword']);
			
			$user_login->reset_password = 0;
			
			$user_login->reset_token = "";
				
			if($user_login->save()){
				Yii::app()->user->setFlash('success', "Password successfully changed");
				$flag = true;
			}
		}
		
		$this->render('reset_password',array('model'=>$model,'flag'=>$flag,'user_id'=>$uid));
	}
}
