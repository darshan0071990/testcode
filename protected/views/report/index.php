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
		'Report'
	);
?>
<script>
$(document).ready(function(){
	$('[data-toggle="tooltip"]').tooltip();
});
</script>


<div id='body_content' class='container'>


<div class="row-fluid">
	<div class="span4 margin">
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
	'trClickable'=>false,
	'responsiveTable' => true,
	'template' => "{extendedSummary}{items}{pager}{summary}",
	'pagerCssClass' => 'pagination-right',
	'columns' => array(
		'transaction_id::Transaction Number',
		'club_name::Club Name',
		'event_name::Event Name',
		'display_name::Customer Name',
		'username::Customer Email',
		'amount::Amount',
		'type::Type',
		'created_date::Booking Date',
		'validate::Validate',
	),
	'extendedSummary' => array(
		'title' => 'Total $',
		'columns' => array(
			'amount' => array(
				'label'=>'Total Purchases',
				'class'=>'yiiwheels.widgets.grid.operations.WhSumOperation'
			),
			'extendedSummaryOptions' => array(
				'class' => 'well pull-right',
				'style' => 'width:300px'
			),),
	),
)); ?>
</div>
