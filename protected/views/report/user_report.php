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
		'Course Progress Report'=>array('report/index'),
		$course->title,
	);
?>

<div id='body_content' class='container'>

<div class="row-fluid">
	<div class="span4">
		<h4>Student Progress Report</h4>
	</div>
	<div class="span8" style="text-align: right;">
		<button id="export_users_csv" class="btn btn-success" style="margin-left: 10px;">Export CSV</button>
		<!--<button id="export_users_pdf" class="btn btn-success" style="margin-left: 10px;">Export PDF</button>-->
	</div>
</div>
	
<div class="row-fluid">
<?php
$this->widget('yiiwheels.widgets.grid.WhGridView', array(
	'fixedHeader' => false,
	'headerOffset' => 40,
	'type'=>'bordered condensed',
	'dataProvider' => new CArrayDataProvider($model),
	'trClickable'=>false,
	//'trClickUrl' => 'Yii::app()->controller->createUrl("report/student_login_report",array("uid"=>$data->id,"bid"=>'.$batch_name->id.',"cid"=>'.$course->id.'))',
	'responsiveTable' => true,
	'template' => "{items}{pager}{summary}",
	'pagerCssClass' => 'pagination-right',
	'columns' => array(
		array(
			'name' => 'Sr. No',
			'value'=>'$this->grid->dataProvider->pagination->currentPage * $this->grid->dataProvider->pagination->pageSize + ($row+1)',
			'htmlOptions'=>array('style'=>'word-wrap:break-word;width:5%;')
		),
		array(
			'name' => 'roll_no',
			'header' => 'Roll No',
			'value'=>'$data->roll_no',
			'htmlOptions'=>array('style'=>'word-wrap:break-word;width:10%;')
		),
		array(
			'name' => 'name',
			'header' => 'Name',
			'value'=>'$data->name',
			'htmlOptions'=>array('style'=>'word-wrap:break-word')
		),
		array(
			'header' => 'Course Progress',
			'value' => function($data,$row){
							return "<div class='progress progress-success progress-striped'><div class='bar' style='width: ".$data["UserCourse"][0]->completed_percent."%;'></div> <span>".$data["UserCourse"][0]->completed_percent."%</span></div>";
			},
			'type'=>'raw',
			'htmlOptions'=>array('style'=>'word-wrap:break-word;width:15%;'),
		),
		array(
			'name' => 'email',
			'header' => 'Email',
			'value'=>'$data->email',
			'htmlOptions'=>array('style'=>'word-wrap:break-word')
		),
		array(
			'name' => 'mobile',
			'header' => 'Mobile',
			'value'=>'$data->mobile',
			'htmlOptions'=>array('style'=>'word-wrap:break-word')
		),
		array(
			'header'=>'Last Login',
			'value'=> function($data,$row){
				if(!empty($data["UserLogin"]->last_login)){
					return Yii::app()->format->timeago($data["UserLogin"]->last_login);
				}else{ 
					return "User has to Log In";
				}
			},
			'type'=>'raw',
			'htmlOptions'=>array('style'=>'word-wrap:break-word;width:15%;')
		),
	),
)); ?>
</div>
<div id="frameDiv" style="display:none;"></div>
</div>

<script>
$(document).ready(function(){
	$('#export_users_csv').click(function(e){
		e.preventDefault();
		var course_id = <?php echo $course->id;?>;
		$.ajax({
			url:'<?php echo  Yii::app()->request->baseUrl; ?>/report/generate_report_user/?cid='+course_id,
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
		var course_id = <?php echo $course->id;?>;
		$.ajax({
			url:'<?php echo  Yii::app()->request->baseUrl; ?>/report/pdf_report_user',
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
