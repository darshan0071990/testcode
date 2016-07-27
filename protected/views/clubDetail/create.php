<?php
/* @var $this ClubDetailController */
/* @var $model ClubDetail */
?>

<?php
$this->breadcrumbs=array(
	'Home'=>array('home/index'),
	'Club Details'=>array('index'),
	'Create Club',
);
?>
<?php $this->renderPartial('_form', array('model'=>$model)); ?>