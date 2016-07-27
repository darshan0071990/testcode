<?php
/* @var $this PackagesController */
/* @var $model Packages */

$this->breadcrumbs=array(
    'Home'=>array('home/index'),
	'Packages'=>array('index'),
	'Create',
);

?>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>