<?php
/* @var $this BarEventController */
/* @var $model BarEvent */
?>

<?php
$this->breadcrumbs=array(
        'Home'=>array('home/index'),
	'Bar Events'=>array('barEvent/index'),
	'Update:- '. $model->event_name,
);

?>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>