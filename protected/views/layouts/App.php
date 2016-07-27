<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />
	
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<!-- blueprint CSS framework -->
	
	<link href="<?php echo Yii::app()->request->baseUrl; ?>/css/theme_main.css" rel="stylesheet">
	
	<?php Yii::app()->bootstrap->register(); ?>
	
	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jwplayer.js"></script>
	
	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
	
	<link href="<?php echo Yii::app()->request->baseUrl; ?>/css/bootstrap-3-3.css" rel="stylesheet">
	
	<link href="<?php echo Yii::app()->request->baseUrl; ?>/css/theme_light.css" rel="stylesheet">
	
	<link href="<?php echo Yii::app()->request->baseUrl; ?>/css/font-awesome.min.css" rel="stylesheet">

</head>
<body>
	<!-- Header Navigation Bar -->
	<div class='top_menu'>
	<?php
		$user = $user = Yii::app()->user->getState('user');
		$this->widget('bootstrap.widgets.TbNavbar', array(
			//'color' => TbHtml::NAVBAR_COLOR_INVERSE,
			'display' => TbHtml::NAVBAR_DISPLAY_FIXEDTOP,
			'brandLabel' => '<img src ="http://s1.coursina.com/img/logo33.png" />',
			'brandUrl'   => $this->createUrl('/home/index'),
			'collapse' => true,
			'items' => array(
				array(
					'class' => 'bootstrap.widgets.TbNav',
					'htmlOptions'=>array('class'=>'pull-right'),
					'items' => array(
						array('label' => 'Home', 'url' => array('/home/index')),
						
						array('label' => 'Courses', 'items' => array(
								array('label' => 'My Courses', 'url' => array('/course/course_list')),
								array('label' => 'Course Library', 'url' => array('/course/course_library')),
							),
						),	
						
						array('label' => 'Announcement', 'url' => array('/announcement/index')),
						
						array('label' => 'Messages', 'url' => array('/messages/messages_view')),
						
						array('label' =>$user['display_name'],
							'items' => array(
								array('label' => 'Profile', 'url' => array('/users/view/'.$user['user_id'])),
								array('label' => 'Change Password', 'url'=>array('/users/change_password')),
								TbHtml::menuDivider(),
								array('label' => 'Logout', 'url' => array('/userLogin/logout')),
							),
							'htmlOptions'=>array('class'=>'nav_user_dropdown'),
						),
					),
				),
			),
		)); 
	?>
	</div>

	
	
	<div class="main_wrapper">	
		<div class='nav-breadcrumbs'>
			<?php echo TbHtml::breadcrumbs($this->breadcrumbs); ?>
		</div>
		<?php echo $content; ?>	
	</div>

</body>
</html>