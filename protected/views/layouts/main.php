<?php /* @var $this Controller */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />
	
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<!-- blueprint CSS framework -->
	<?php Yii::app()->bootstrap->register(); ?>

	<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
	<![endif]-->

	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/click2learn.css" />
	
	</head>
<body>
	<!-- Header Navigation Bar -->
	<div class='top_menu'>
	<?php $this->widget('bootstrap.widgets.TbNavbar', array(
    'brandLabel' => 'Whats42nite',
	'brandUrl'   => $this->createUrl('/home/index'),
    'display' => null, // default is static to top
	'collapse' => true,
    'items' => array(
        array(
            'class' => 'bootstrap.widgets.TbNav',
            'items' => array(
				array('label' => 'Home', 'url' => array('/site/index'), 'icon-class'=>'ICON_HOME'),
			),
		),
	),
)); ?>
</div>

	
	<div class="container">
    <?php echo $content; ?>	
	</div>
	<!--<div id='center_container' class='container'>
	   <?php //echo $content; ?>
	</div>-->

</body>
</html>