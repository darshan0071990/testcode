<style>
	.table-striped tbody > tr:nth-child(odd) > td, .table-striped tbody > tr:nth-child(odd) > th {
		background-color: #f1f1f1;
	}
	.progress {
		margin-bottom:0px;
	}
	.row-fluid {
		background-color: #ffffff !important;
	}
</style>
<?php
	$this->breadcrumbs=array(
		'Home'=>array('home/index'),
		'Course Report'=>array('report/index'),
		'Course-'.$course->title=>array('report/batch_report/'.$course->id),
		'Batch-'.$batch_name->name =>array('report/user_report?id='.$batch_name->id.'&cid='.$course->id),
		'Student-'.$user->name,
		
	);
?>

<div id='body_content' class='container'>
<div class="row-fluid">
	<div class="span4">
		<h4>Course Logged In</h4>
	</div>
	<div class="span8" style="text-align: right;">
		<button id="export_attendence_csv" class="btn btn-success" style="margin-left: 10px;">Export CSV</button>
		<button id="export_attendence_pdf" class="btn btn-success" style="margin-left: 10px;">Export PDF</button>
	</div>
</div>
	
<div class="row-fluid">
<?php
$this->widget('yiiwheels.widgets.grid.WhGridView', array(
	'fixedHeader' => false,
	'headerOffset' => 40,
	'type'=>'bordered striped condensed',
	'dataProvider' => new CArrayDataProvider($model),
	'trClickable'=>false,
	'responsiveTable' => false,
	'template' => "{items}{pager}{summary}",
	'pagerCssClass' => 'pagination-right',
	'columns' => array(
		array(
			'name' => 'Sr. No',
			'value'=>'$this->grid->dataProvider->pagination->currentPage * $this->grid->dataProvider->pagination->pageSize + ($row+1)',
			'htmlOptions'=>array('style'=>'word-wrap:break-word;width:5%;')
		),
		array(
			'header' => 'Course Name',
			'value'=>'$data["Course"]->name',
			'htmlOptions'=>array('style'=>'word-wrap:break-word;width:20%;')
		),
		array(
			'header' => 'Logged In Time',
			'value'=>'date("d-m-Y H:i:s",$data->date)',
			'htmlOptions'=>array('style'=>'word-wrap:break-word')
		),
	),
)); ?>
</div>
<div id="frameDiv" style="display:none;"></div>
</div>

<script>
$(document).ready(function(){
	$('#export_attendence_csv').click(function(e){
		e.preventDefault();
		var course_id = <?php echo $course->id;?>;
		var batch_id = <?php echo $batch_name->id;?>;
		var user_id = <?php echo $user->id;?>;
		$.ajax({
			url:'<?php echo  Yii::app()->request->baseUrl; ?>/report/student_attendence_export?uid='+user_id+'&bid='+batch_id+'&cid='+course_id,
			dataType: 'json',
			success:function(data){
				//Change url
				var htm = '<iframe src="<?php echo Yii::app()->getBaseUrl(true); ?>/'+data+'"></iframe>';
				$('#frameDiv').html(htm);
			}
		});
	});	
	$('#export_attendence_pdf').click(function(e){
		e.preventDefault();
		var course_id = <?php echo $course->id;?>;
		var batch_id = <?php echo $batch_name->id;?>;
		var user_id = <?php echo $user->id;?>;
		$.ajax({
			url:'<?php echo  Yii::app()->request->baseUrl; ?>/report/student_attendence_export?uid='+user_id+'&bid='+batch_id+'&cid='+course_id,
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
