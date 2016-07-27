<?php
/* @var $this PackagesController */
/* @var $model Packages */

$this->breadcrumbs=array(
	'Dashboard'=>array('home/index'),
	'Packages'=>array('index'),
	$model->package_name=>array('view','id'=>$model->id),
	'Update',
);

?>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>