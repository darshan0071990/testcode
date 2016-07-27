<?php
/* @var $this PackagesController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
    'Home'=>array('home/index'),
    'My Subscription',
);
?>

<div id='body_content' class='container'>
    <?php
    $this->widget('yiiwheels.widgets.grid.WhGridView', array(
        'fixedHeader' => true,
        'headerOffset' => 40,
        'type'=>'bordered',
        'dataProvider' => $dataProvider,
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
                'name' => 'package_name',
                'value'=>'$data->package->package_name',
                'htmlOptions'=>array('style'=>'word-wrap:break-word')
            ),
            array(
                'name' => 'features',
                'value'=>'$data->package->features',
                'htmlOptions'=>array('style'=>'features-wrap:break-word')
            ),
            array(
                'header' => 'No of Events',
                'value'=>'$data->package->no_of_events',
                'htmlOptions'=>array('style'=>'word-wrap:break-word')
            ),
            array(
                'header' => 'Amount Paid',
                'value'=>'$data->amount',
                'htmlOptions'=>array('style'=>'word-wrap:break-word')
            ),
            array(
                'header' => 'Subscription Started',
                'value'=>'date("jS F, Y",$data->date)',
                'htmlOptions'=>array('style'=>'word-wrap:break-word')
            ),

            array(
                'header' => 'Subscription Expires',
                'value' => function($data,$row){
                    $date =  date('Y-m-d',$data->date);
                    return date('jS F, Y', strtotime($date. ' + '. $data->package->duration.' months'));
                },
                'type'=>'raw',
                'htmlOptions'=>array('style'=>'word-wrap:break-word;width:15%;')
            ),
        ),
    )); ?>
</div>

