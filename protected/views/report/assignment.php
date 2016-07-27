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
		'Assignment Result Report'
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
		<h4>Select Course</h4>
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
	'dataProvider' => $dataProvider,
	'trClickable'=>true,
	'trClickUrl' => 'Yii::app()->controller->createUrl("report/user_assignment_report/$data->id")',
	'responsiveTable' => false,
	'template' => "{items}{pager}{summary}",
	'pagerCssClass' => 'pagination-right',
	'columns' => array(
		array(
			'name' => 'Sr. No',
			'value'=>'$this->grid->dataProvider->pagination->currentPage * $this->grid->dataProvider->pagination->pageSize + ($row+1)',
			'htmlOptions'=>array('style'=>'word-wrap:break-word')
		),
		array(
			'name' => 'title',
			'header' => 'Course Title',
			'value'=>'$data->title',
			'htmlOptions'=>array('style'=>'word-wrap:break-word')
		),
		array(
			'name' => 'course_code',
			'header' => 'Course Code',
			'value'=>'$data->course_code',
			'htmlOptions'=>array('style'=>'word-wrap:break-word;width:10%;')
		),
		array(
			'header' => 'Trainer Name',
			'value'=>'$data->CourseTrainer->name',
			'htmlOptions'=>array('style'=>'word-wrap:break-word')
		),
		array(
			'header' => 'No. of Enrolled Users',
			'value'=>'$data->UserCourseSTAT',
			'type'=>'raw',
			'htmlOptions'=>array('style'=>'word-wrap:break-word;text-align: center;padding:0px !important;')
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
