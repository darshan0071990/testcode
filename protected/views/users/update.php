<?php
/* @var $this UsersController */
/* @var $model Users */

$this->breadcrumbs=array(
	'Home'=>array('home/index'),
	'Users'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);
?>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>
