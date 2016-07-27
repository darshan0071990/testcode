<?php
	$this->breadcrumbs=array(
		'Home'=>array('home/index'),
		'Student Attendence Report'
	);
?>
<script>
$(document).ready(function(){
	$('[data-toggle="tooltip"]').tooltip();
});
</script>
<div id='body_content' class='container'>


<div class="row-fluid">
	<div class="span4">
		<h4>Attendence Report</h4>
	</div>
	<div class="span8" style="text-align: right;">
		<button id="export_attendence_csv" class="btn btn-success" style="margin-left: 10px;">Export CSV</button>
		<!--<button id="export_course_pdf" class="btn btn-success" style="margin-left: 10px;">Export PDF</button>-->
	</div>
</div>
<div class="row-fluid">
<?php
$this->widget('yiiwheels.widgets.grid.WhGridView', array(
	'fixedHeader' => false,
	'headerOffset' => 40,
	'type'=>'bordered striped',
	'dataProvider' => $model->search(),
	'filter' =>$model,
	'trClickable'=>false,
	//'trClickUrl' => 'Yii::app()->controller->createUrl("report/user_report/$data->id")',
	'responsiveTable' => true,
	'template' => "{items}{pager}{summary}",
	'pagerCssClass' => 'pagination-right',
	'columns' => array(
		array(
			'header' => 'Sr. No',
			'value'=>'$this->grid->dataProvider->pagination->currentPage * $this->grid->dataProvider->pagination->pageSize + ($row+1)',
			'htmlOptions'=>array('style'=>'word-wrap:break-word')
		),
		array(
			'name'=>'name',
			'header' => 'Name',
			'value'=>'$data->UserLogin->display_name',
			'htmlOptions'=>array('style'=>'word-wrap:break-word')
		),
		array(
			'header' => 'Email',
			'value'=>'$data->email',
			'htmlOptions'=>array('style'=>'word-wrap:break-word')
		),
		array(
			'header' => 'From IP',
			'value'=>'$data->from_ip',
			'htmlOptions'=>array('style'=>'word-wrap:break-word')
		),
		array(
			'header' => 'To IP',
			'value'=>'$data->to_ip',
			'htmlOptions'=>array('style'=>'word-wrap:break-word')
		),
		array(
			'header' => 'Browser',
			'value'=>'$data->browser',
			'htmlOptions'=>array('style'=>'word-wrap:break-word')
		),
		array(
			'header' => 'OS',
			'value'=>'$data->operating_sys',
			'htmlOptions'=>array('style'=>'word-wrap:break-word')
		),
		array(
			'header' => 'Device',
			'value'=>'$data->device',
			'htmlOptions'=>array('style'=>'word-wrap:break-word')
		),
		array(
			'header' => 'Activity',
			'value'=>'$data->activity',
			'htmlOptions'=>array('style'=>'word-wrap:break-word')
		),
		array(
			'header' => 'Date',
			'value'=>'$data->date',
			'htmlOptions'=>array('style'=>'word-wrap:break-word')
		),
	),
)); ?>
</div>
<div id="frameDiv" style="display:none;"></div>
</div>
