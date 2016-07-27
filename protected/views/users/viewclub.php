<?php
$this->breadcrumbs=array(
    'Home'=>array('home/index'),
    'Club Owners'
);
?>

<script>
    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>

<div id='body_content' class='container'>
    <?php $this->widget('yiiwheels.widgets.grid.WhGridView', array(
        'fixedHeader' => true,
        'headerOffset' => 40,
        'type'=>'bordered condensed',
        'dataProvider' => $dataProvider,
        'responsiveTable' => true,
        'template' => "{items}{pager}{summary}",
        'trClickable' => true,
        'columns' => array(
            array(
                'name' => 'Sr. No',
                'value'=>'$this->grid->dataProvider->pagination->currentPage * $this->grid->dataProvider->pagination->pageSize + ($row+1)',
                'htmlOptions'=>array('style'=>'word-wrap:break-word;width:5%;text-align:center;')
            ),
            array(
                'name' => 'name',
                'header' => 'Name',
                'value'=>'$data->name',
                'htmlOptions'=>array('style'=>'word-wrap:break-word')
            ),
            array(
                'name'=>'email',
                'header'=>'Email',
                'value'=>'$data->email',
                'htmlOptions'=>array('style'=>'word-wrap:break-word')
            ),

            array(
                'name'=>'city',
                'header'=>'City',
                'value'=>'$data->city',
                'htmlOptions'=>array('style'=>'word-wrap:break-word')
            ),
            array(
                'name'=>'country',
                'header'=>'Country',
                'value'=>'$data->country',
                'htmlOptions'=>array('style'=>'word-wrap:break-word')
            ),

            array(
                'header' => 'Action',
                'class'=>'TbButtonColumn',

                'template'=>'{delete}',
                'buttons'=>array (
                    'delete'=> array(
                        'icon'=>'fa fa-trash-o',
                        'appendIcon'=>'',
                    ),

                ),
            )
        ),
    )); ?>
</div>