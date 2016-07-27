<?php
/* @var $this BarDetailController */

/* @var $model BarDetail */

?>


<?php
$this->breadcrumbs=array(
	'Home'=>array('home/index'),
	'Bar Details'=>array('index'),
	'Create',
);
?>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>