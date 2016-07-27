<?php
/* @var $this BarDetailController */
/* @var $model BarDetail */
?>

<?php
$this->breadcrumbs=array(
	'Home'=>array('home/index'),
        'Bar Details' =>array('barDetail/index'),
        $model->bar_name.' Update',
);

?>


<?php $this->renderPartial('_form', array('model'=>$model)); ?>