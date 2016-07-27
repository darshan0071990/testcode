<?php
/* @var $this ClubDetailController */
/* @var $model ClubDetail */
?>

<?php
$this->breadcrumbs=array(
	'Home'=>array('home/index'),
	'Club Details'=>array('index'),
	$model->club_name,
);
?>

<div id="body_content" class="container">

<div class='panel panel-default'>
	<div class='panel-heading'>
		Club Details
	</div>
	<div class='panel-body'>
	
<?php $this->widget('yiiwheels.widgets.detail.WhDetailView',array(
    'htmlOptions' => array(
        'class' => 'table table-striped table-condensed table-hover',
    ),
    'data'=>$model,
    'attributes'=>array(
		'club_name',
		'address_line_1',
		'city',
		'country',
		'zip_code',
		'phone_no',
		'mobile',
		array(
			'label'=>'Created On',
			'value'=>date("d F Y H:i:s",$model->created_date),
		),
		'featured_pic',
	),
)); ?>
</div>
</div></div>