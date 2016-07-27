<style>
	.table-striped tbody > tr:nth-child(odd) > td, .table-striped tbody > tr:nth-child(odd) > th {
		background-color: #f1f1f1;
	}
	.row-fluid {
		background-color: #ffffff !important;
	}
</style>
<?php
	$this->breadcrumbs=array(
		'Home'=>array('home/index'),
		'Course Report'=>array('report/index'),
		$course_name,
		'- Batch Report'
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
		<h4>Batch Report</h4>
	</div>
	<div class="span8" style="text-align: right;">
		<button id="export_batch_csv" class="btn btn-success" style="margin-left: 10px;">Export CSV</button>
		<button id="export_batch_pdf" class="btn btn-success" style="margin-left: 10px;">Export PDF</button>
	</div>
</div>

<div class="row-fluid">
<?php
$this->widget('yiiwheels.widgets.grid.WhGridView', array(
	'fixedHeader' => false,
	'headerOffset' => 40,
	'type'=>'bordered striped condensed',
	'dataProvider' => $dataProvider,
	'trClickable'=>true,
	'trClickUrl' => 'Yii::app()->controller->createUrl("report/user_report",array("id"=>$data->Batch->id,"cid"=>'.$cid.'))',
	'responsiveTable' => true,
	'template' => "{items}{pager}{summary}",
	'pagerCssClass' => 'pagination-right',
	'columns' => array(
		array(
			'name' => 'Sr. No',
			'value'=>'$this->grid->dataProvider->pagination->currentPage * $this->grid->dataProvider->pagination->pageSize + ($row+1)',
			'htmlOptions'=>array('style'=>'word-wrap:break-word;width:7%;')
		),
		array(
			'name' => 'name',
			'header' => 'Batch Name',
			'value'=>'$data->Batch->name',
			'htmlOptions'=>array('style'=>'word-wrap:break-word')
		),
		array(
			'name' => 'start_date',
			'header' => 'Batch Start Date',
			'value'=>'$data->Batch->start_date',
			'htmlOptions'=>array('style'=>'word-wrap:break-word;width:10%;')
		),
		array(
			'name' => 'end_date',
			'header' => 'Batch End Date',
			'value'=>'$data->Batch->end_date',
			'htmlOptions'=>array('style'=>'word-wrap:break-word;width:10%;')
		),
		array(
			'header' => 'Status',
			'value'=>'($data->Batch->suspend=="1")?("Active"):("Suspended")',
			'htmlOptions'=>array('style'=>'word-wrap:break-word;width:10%;')
		),
		array(
			'header' => 'No. of Users',
			'value'=>'$data->Batch->UserBatchSTAT',
			'type'=>'raw',
			'htmlOptions'=>array('style'=>'word-wrap:break-word;text-align: center;padding:0px !important;width:10%;')
		),
	),
)); ?>
</div>
<div id="frameDiv" style="display:none;"></div>
</div>

<script>
$(document).ready(function(){
	$('#export_batch_csv').click(function(e){
		e.preventDefault();
		$.ajax({
			url:'<?php echo  Yii::app()->request->baseUrl; ?>/report/export_batch/'+<?php echo $cid; ?>,
			dataType: 'json',
			success:function(data){
				//Change url
				var htm = '<iframe src="<?php echo Yii::app()->getBaseUrl(); ?>/'+data+'"></iframe>';
				$('#frameDiv').html(htm);
			}
		});
	});	
});
</script>
