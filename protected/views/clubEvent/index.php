<?php
/* @var $this ClubEventController */

/* @var $dataProvider CActiveDataProvider */

?>


<?php
$this->breadcrumbs=array(
	'Home'=>array('home/index'),
	'Club Events',
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
		'event_name',
		'event_discription',
		array(
			'name' => 'club_name',
			'value'=>'$data["club"]->club_name',
			'htmlOptions'=>array('style'=>'word-wrap:break-word')
		),
		'event_fee',
		'reservation_fee',
		'image_url',
		array(
			'name' => 'start_date',
			'header' => 'Event Start',
			'value'=>'$data->start_date',
			'htmlOptions'=>array('style'=>'word-wrap:break-word')
		),
		array(
			'name' => 'start_time',
			'header' => 'Event Start Time',
			'value'=>'$data->start_time',
			'htmlOptions'=>array('style'=>'word-wrap:break-word')
		),
		array(
			'name' => 'created_date',
			'header' => 'Event Created On',
			'value'=>'date("d/m/Y H:m:s",$data->created_date)',
			'htmlOptions'=>array('style'=>'word-wrap:break-word')
		),
		array(
			'header' => 'Status',
			'value'=>'($data->status=="0")?("Active"):("Suspended")',
			'htmlOptions'=>array('style'=>'word-wrap:break-word')
		),
		array(
			'header'=>'Action',
			'class'=>'TbButtonColumn',
			'template'=>'{update}{delete}',
			'htmlOptions'=>array('style'=>'font-size:18px;'),
			'buttons'=>array (
				'update'=> array(
					'icon'=>'fa fa-pencil-square-o',
					'appendIcon'=>'',
					'url'=>'$this->grid->controller->createUrl("/clubEvent/update", array("id"=>$data->id))',
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
