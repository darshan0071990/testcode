<?php /* @var $this Controller */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />
	
	<link href="<?php echo Yii::app()->request->baseUrl; ?>/css/theme_main.css" rel="stylesheet">
	
	<?php Yii::app()->bootstrap->register(); ?>
	
	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
	
	<link href="<?php echo Yii::app()->request->baseUrl; ?>/css/bootstrap-3-3.css" rel="stylesheet">
	
	<link href="<?php echo Yii::app()->request->baseUrl; ?>/css/theme_light.css" rel="stylesheet">

	
	<style>
		body{
			background:#EDEFF4;
		}
		
		.form-actions {
			background-color: #003366;
		}
		.form-horizontal{
			margin:0px;
		}
		.form-horizontal .control-group {
			margin-bottom: 20px;
		}
		#page {
			margin: 5% auto !important;
		}
		
	</style>
	<?php	
		Yii::app()->bootstrap->register();
	?>

	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>
<body>
	<div class='top_menu'>
	<?php
		$user = $user = Yii::app()->user->getState('user');
		$this->widget('bootstrap.widgets.TbNavbar', array(
			'display' => TbHtml::NAVBAR_DISPLAY_FIXEDTOP,
			'brandLabel' => 'Whats42nite',
			'collapse' => false,
		)); 
	?>
	</div>
	<div class="main_wrapper span4" id = "page">	
		<?php echo $content; ?>	
	</div>
</body>
</html>
