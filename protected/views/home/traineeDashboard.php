<style>
	.table{
		margin-bottom:0px;
	}
</style>
<?php
	$this->breadcrumbs=array(
		'Dashboard',
	);
?>
<div>
	<div class='span6' style='margin-left:0'>
		<div class="panel panel-default">
			<!-- Default panel contents -->
			<div class="panel-heading">
				<a href='<?php echo Yii::app()->controller->createUrl("course/course_list") ?>'> Courses: (<?php echo $data['course_count']; ?>) </a>
			</div>
			
			<!-- Table -->
			<?php 	
				$this->widget('yiiwheels.widgets.grid.WhGridView', array(
				'fixedHeader' => true,
				'headerOffset' => 40,
				'type'=>'condensed',
				'dataProvider' => $data['course'],
				'responsiveTable' => false,
				'trClickable' => true,
				'trClickUrl' => 'Yii::app()->controller->createUrl("chapter/course_documents/$data->course_id")',
				'template' => "{items}",
				'columns' => array(
						array(
							'header'=>'Name',
							'name'=>'Course',
							'value' => function($data,$row){
								return $data['Course']->name."&nbsp;(".$data['Course']->course_code.")"."<div class='pull-right label label-success'>".$data->completed_percent."% Completed</div>";
							},
							'headerHtmlOptions' => array('style' => 'display:none'),
							'type'=>'raw',
							'htmlOptions'=>array('style'=>'word-wrap:break-word')
						),
					),
				)); 
			?>
		</div>
	</div>
	
	<!--<div class='span6' style='margin-left:0'>
		<div class="panel panel-default">
			
			<div class="panel-heading">
				 <a href='<?php// echo Yii::app()->controller->createUrl("messages/messages_view") ?>'> Messages </a>
			</div>-->
			
			<!-- Table -->
			<?php 	/*
				$this->widget('yiiwheels.widgets.grid.WhGridView', array(
				'fixedHeader' => true,
				'headerOffset' => 40,
				//'type'=>'striped',
				'dataProvider' => $data['messages'],
				'responsiveTable' => false,
				'trClickable' => true,
				'trClickUrl' => 'Yii::app()->controller->createUrl("messages/messages_view/$data->id")',
				'template' => "{items}",
				'columns' => array(
						array(
							'header'=>'Name',
							'name'=>'Course',
							'value' => function($data,$row){
								return $data->subject;
							},
							'type'=>'raw',
							'htmlOptions'=>array('style'=>'word-wrap:break-word')
						),
					),
				)); */
			?>
		<!--</div>
	</div>-->
</div>

<div class='span6'>
	<div class='span6' style='margin-left:0'>
		<div class="panel panel-default">
			<!-- Default panel contents -->
			<div class="panel-heading">
				<a href='<?php echo Yii::app()->controller->createUrl("announcement/index") ?>'> Announcements: (<?php echo $data['annoucement_count']; ?>) </a>
			</div>
			
			<!-- Table -->
			<?php 	
				$this->widget('yiiwheels.widgets.grid.WhGridView', array(
				'fixedHeader' => true,
				'headerOffset' => 40,
				'type'=>'condensed',
				'dataProvider' => $data['annoucement'],
				'responsiveTable' => false,
				'trClickable' => true,
				'trClickUrl' => 'Yii::app()->controller->createUrl("announcement/view/$data->id")',
				'template' => "{items}",
				'columns' => array(
						array(
							'header'=>'Name',
							'name'=>'Course',
							'value' => function($data,$row){
								return $data->subject;
							},
							'headerHtmlOptions' => array('style' => 'display:none'),
							'type'=>'raw',
							'htmlOptions'=>array('style'=>'word-wrap:break-word')
						),
					),
				)); 
			?>
		</div>
	</div>
</div>
