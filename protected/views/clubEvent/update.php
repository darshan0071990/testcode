<?php
/* @var $this ClubEventController */
/* @var $model ClubEvent */
?>

<?php
$this->breadcrumbs=array(
	'Home'=>array('home/index'),
	'Club Events'=>array('clubEvent/index'),
	'Update:- '. $model->event_name,
	
);

?>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>