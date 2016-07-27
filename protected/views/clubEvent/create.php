<?php
/* @var $this ClubEventController */
/* @var $model ClubEvent */
?>

<?php
$this->breadcrumbs=array(
	'Home'=>array('home/index'),
	'Club Events'=>array('index'),
	'Create',
);

?>
<?php $this->renderPartial('_form', array('model'=>$model)); ?>