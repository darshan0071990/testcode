<style>
	.table-striped tbody > tr:nth-child(odd) > td, .table-striped tbody > tr:nth-child(odd) > th {
		background-color: #f1f1f1;
	}
	.row-fluid {
		background-color: #ffffff !important;
	}
	.progress {
		margin-bottom: 0px !important;
	}
</style>
<?php
	$this->breadcrumbs=array(
		'Home'=>array('home/index'),
		'Assignment Result Report'=>array('report/assignment'),
		'Student Assignment Report'
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
		<h4>Assignment Result Report</h4>
	</div>
	<div class="span8" style="text-align: right;">
		<button id="export_course_csv" class="btn btn-success" style="margin-left: 10px;">Export CSV</button>
		<!--<button id="export_course_pdf" class="btn btn-success" style="margin-left: 10px;">Export PDF</button>-->
	</div>
</div>

<div class="row-fluid">
<?php
$this->widget('yiiwheels.widgets.grid.WhGridView', array(
	'fixedHeader' => false,
	'headerOffset' => 40,
	'type'=>'bordered condensed',
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
			'value'=>'$data->CourseUserLogin->display_name',
			'htmlOptions'=>array('style'=>'word-wrap:break-word')
		),
		/*array(
			'header'=>'Course Progress',
			'value' => function($data,$row){
				return "<div class='progress progress-success progress-striped'><div class='bar' style='width: ".$data->completed_percent."%;'></div> <span>".$data->completed_percent."%</span></div>";
			},
			'type'=>'raw',
			'htmlOptions'=>array('style'=>'word-wrap:break-word;width:10%;')
		),*/
		array(
			'header' => 'Total Question',
			'value'=>function($data,$row) use (&$data){
				return $data['total_assignments'];
			},
			'htmlOptions'=>array('style'=>'word-wrap:break-word')
		),
		array(
			'header' => 'Questions Attempted (T)',
			'value'=> '$data->getAttempted($data->course_id,$data->user_id)',
			'htmlOptions'=>array('style'=>'word-wrap:break-word')
		),
		
		array(
			'header' => 'Correct Answer (C)',
			'value'=>'$data->getright($data->course_id,$data->user_id)',
			'htmlOptions'=>array('style'=>'word-wrap:break-word')
		),
		array(
			'header' => 'Percentage (C/T * 100)',
			'value'=>'$data->getpercent($data->course_id,$data->user_id)',
			'htmlOptions'=>array('style'=>'word-wrap:break-word')
		),
	),
)); ?>
</div>
<div id="frameDiv" style="display:none;"></div>
</div>

<script>
$(document).ready(function(){
	$('#export_course_csv').click(function(e){
		e.preventDefault();
		$.ajax({
			url:'<?php echo  Yii::app()->request->baseUrl; ?>/report/generate_report_course',
			dataType: 'json',
			success:function(data){
				//Change url
				var htm = '<iframe src="<?php echo Yii::app()->getBaseUrl(true); ?>/'+data+'"></iframe>';
				$('#frameDiv').html(htm);
			}
		});
	});	
	$('#export_course_pdf').click(function(e){
		e.preventDefault();
		$.ajax({
			url:'<?php echo  Yii::app()->request->baseUrl; ?>/report/pdf_report_course',
			dataType: 'json',
			success:function(data){
				//Change url
				var htm = '<iframe src="<?php echo Yii::app()->getBaseUrl(true); ?>/'+data+'"></iframe>';
				$('#frameDiv').html(htm);
			}
		});
	});	
});
</script>