<?php
/* @var $this BarEventController */

/* @var $model BarEvent */

?>


<?php
$this->breadcrumbs=array(
	'Home'=>array('home/index'),
	'Bar Events'=>array('index'),
	'Create',
);
?>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>