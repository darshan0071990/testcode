<?php
/* @var $this BarDetailController */
/* @var $model BarDetail */
?>

<?php
$this->breadcrumbs=array(
	'Home'=>array('home/index'),
	'Bar Details'=>array('index'),
	$model->bar_name,
);

?>

<div id="body_content" class="container">

<div class='panel panel-default'>
	<div class='panel-heading'>
		Bar Details
	</div>
	<div class='panel-body'>
<?php $this->widget('yiiwheels.widgets.detail.WhDetailView',array(
    'htmlOptions' => array(
        'class' => 'table table-striped table-condensed table-hover',
    ),
    'data'=>$model,
    'attributes'=>array(
		'bar_name',
		'address_line_1',
		'city',
		'country',
		'zip_code',
		'phone_no',
		'mobile_no',
		'featured_pic',
		array(
			'label'=>'Created On',
			'value'=>date("d F Y H:i:s",$model->created_date),
		),
	),
)); ?>
 </div>
            </div>
            </div>