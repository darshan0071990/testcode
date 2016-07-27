<?php
/* @var $this UsersController */
/* @var $model Users */

$this->breadcrumbs=array(
	'Home'=>array('home/index'),
	'Users'=>array('index'),
	'Create',
);

?>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>
