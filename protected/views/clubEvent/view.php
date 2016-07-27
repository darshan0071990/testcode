<?php
/* @var $this ClubEventController */
/* @var $model ClubEvent */
?>

<?php
$this->breadcrumbs=array(
	'Home'=>array('home/index'),
	'Club Events'=>array('clubEvent/index'),
	$model->event_name,
);

?>

<div id="body_content" class="container">

<div class='panel panel-default'>
	<div class='panel-heading'>
		Club Event Details
	</div>
	<div class='panel-body'>
	
<?php $this->widget('yiiwheels.widgets.detail.WhDetailView',array(
    'htmlOptions' => array(
        'class' => 'table table-striped table-condensed table-hover',
    ),
    'data'=>$model,
    'attributes'=>array(
		'event_name',
		'event_discription',
		array(
			'label'=>'Club',
			'value'=>$model->club->club_name,
		),
		'event_fee',
		'reservation_fee',
		'image_url',
		array(
			'label'=>'Start Date/Time',
			'value'=>$model->start_date,
		),
		array(
			'label'=>'Start Time',
			'value'=>$model->start_time,
		),
		array(
			'label'=>'Created On',
			'value'=>date("d F Y H:i:s",$model->created_date),
		),
		array(
			'label'=>'Status',
			'value'=>($model->status=="0")?("Active"):("Suspended"),
		),
	),
)); ?>
        </div>
        </div>
        <a class="btn btn-primary pull-right" href=<?php echo Yii::app()->createUrl('clubEvent/clubEventImage', array('id'=>$model->id));?>>Add Images</a>

        <?php
        $this->widget('yiiwheels.widgets.grid.WhGridView', array(
            'fixedHeader' => true,
            'headerOffset' => 40,
            'type'=>'bordered',
            'dataProvider' => $data,
            'trClickable'=>false,
            'responsiveTable' => true,
            'template' => "{items}{pager}{summary}",
            'pagerCssClass' => 'pagination-right',
            'columns' => array(
                array(
                    'name' => 'Sr. No',
                    'value'=>'$row+1',
                    'htmlOptions'=>array('style'=>'word-wrap:break-word')
                ),
                array(
                    'header' => 'image_url',
                    'value' => 'CHtml::link($data->image_url, Yii::app()->getBaseUrl(true)."/images/clubevent/".$data->club_event_id."/ClubAlbum/".$data->image_url ,array("target"=>"_blank"))',
                    'type'  => 'raw',
                    'htmlOptions'=>array('style'=>'word-wrap:break-word')
                ),


                array(
                    'header'=>'Action',
                    'class'=>'TbButtonColumn',
                    'template'=>'{view}{update}{delete}',
                    'htmlOptions'=>array('style'=>'font-size:18px;'),
                    'buttons'=>array (

                        'delete'=> array(
                            'icon'=>'fa fa-trash-o',
                            'appendIcon'=>'',
                            'url'=>'$this->grid->controller->createUrl("/clubEvent/AlbumDelete", array("id"=>$data->id))',
                        ),

                    ),
                ),
            ),
        )); ?>
    </div>