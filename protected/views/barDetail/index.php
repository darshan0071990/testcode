<?php
/* @var $this BarDetailController */

/* @var $dataProvider CActiveDataProvider */

?>


<?php
$this->breadcrumbs=array(
	'Home'=>array('home/index'),
	'Bar Details',

);
?>

<div id='body_content' class='container'>
<?php
$this->widget('yiiwheels.widgets.grid.WhGridView', array(
	'fixedHeader' => true,
	'headerOffset' => 40,
	'type'=>'bordered',
	'dataProvider' => $dataProvider,
	'trClickable'=>true,
	'responsiveTable' => true,
	'template' => "{items}{pager}{summary}",
	'pagerCssClass' => 'pagination-right',
	'columns' => array(
		array(
			'name' => 'Sr. No',
			'value'=>'$row+1',
			'htmlOptions'=>array('style'=>'word-wrap:break-word')
		),
		'bar_name',
		'address_line_1',
		'phone_no',
		'city',
		'country',
		'zip_code',
		'mobile_no',
		array(
			'header'=>'Action',
			'class'=>'TbButtonColumn',
			'template'=>'{update}{delete}',
			'htmlOptions'=>array('style'=>'font-size:18px;'),
			'buttons'=>array (
				'update'=> array(
					'icon'=>'fa fa-pencil-square-o',
					'appendIcon'=>'',
					'url'=>'$this->grid->controller->createUrl("/barDetail/update", array("id"=>$data->id))',
				),
				
				'delete'=> array(
					'icon'=>'fa fa-trash-o',
					'appendIcon'=>'',
				),
				
			),
		),
	),
)); ?>
</div>
