<script>
    $(document).ready(function () {
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
<style>
    .thumbnail > img {
        max-width: 165px;
        max-height: 165px;
        min-height:165px;
    }

    .panel-body {
        padding: 10px;
    }
    .panel-group {
        margin-bottom: 0px;
        margin-top: 10px;
    }
    .table {
        width: 100%;
    }
    @media (min-width: 968px){
        .other_table th{
            width:47%;
        }
    }
</style>

<?php
$this->breadcrumbs = array(
    'Home' => array('home/index'),
    'Users' => array('users/index'),
    $model->name . '<a data-toggle="tooltip" data-placement="right" title="Edit" href="' . Yii::app()->request->baseUrl . '/users/update/' . $model->id . '"> <i class="fa fa-pencil-square-o"></i></a>',
);
?>

<div id='body_content' class='container'>
    <!-- User Info Panel-->
    <div class="panel panel-default">
        <div class="panel-heading">
            <h5 class="panel-title"><?php echo $model->name; ?></h5>
        </div>
        <div class="panel-body">
            <div class="row-fluid">
                <div class="span2 user-image">
                    <div class="thumbnail">
                        <img src="<?php echo Yii::app()->request->baseUrl; ?>/display_pictures/users/default_user_old1.jpg" alt="">
                    </div>
                </div>
                <div class="span10 contact_info">
                    <?php
                    $this->widget('yiiwheels.widgets.detail.WhDetailView', array(
                        'data' => $model,
                        'htmlOptions' => array('class' => 'table table-condensed'),
                        'attributes' => array(
                            'name',
                            'email',
                            'sex',
                            'dob',
                            'about_me',
                        )
                    ));
                    ?>
                </div>
            </div>
            <div class="row-fluid">
                <div class="panel-group" id="accordion">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h5 class="panel-title">
                                <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                                    Other Information
                                </a>
                            </h5>
                        </div>
                        <?php if (Yii::app()->user->roles == "Trainee") { ?>
                            <div id="collapseOne" class="panel-collapse collapse in">
                            <?php } else { ?>
                                <div id="collapseOne" class="panel-collapse collapse">
                                <?php } ?>
                                <div class="panel-body">
                                    <?php
                                    $this->widget('yiiwheels.widgets.detail.WhDetailView', array(
                                        'data' => $model,
                                        'htmlOptions' => array('class' => 'table table-condensed other_table'),
                                        'attributes' => array(
                                            'city',
                                            'country',
                                            array(
                                                'label' => 'Status',
                                                'value' => ($model->status == "1") ? ("Active") : ("Not Active"),
                                            ),
                                        ),
                                    ));
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>