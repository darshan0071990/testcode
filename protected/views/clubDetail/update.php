<?php
/* @var $this ClubDetailController */
/* @var $model ClubDetail */
?>

<?php
$this->breadcrumbs=array(
	'Club Details'=>array('clubDetail/index'),
	$model->club_name=>array('view','id'=>$model->id),
	'Update',
);

?>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>