<?php
/* @var $this UsersController */
/* @var $model Users */
/* @var $form CActiveForm */
?>

<div id="body_content" class="container">
    <div class='panel panel-default'>
        <div class='panel-heading'>
            Bouncer Details
        </div>
        <div class='panel-body'>
            <?php
            $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
                'layout' => TbHtml::FORM_LAYOUT_HORIZONTAL,
                'enableAjaxValidation' => false,
                'enableClientValidation' => true,
                'clientOptions' => array(
                    'validateOnSubmit' => true,
                ),
                    )
            );
            ?>

            <?php echo $form->errorSummary($model); ?>


            <?php echo $form->textFieldControlGroup($model, 'name', array('size' => TbHtml::INPUT_SIZE_XLARGE)); ?>

            <?php echo $form->textFieldControlGroup($model, 'email', array('size' => TbHtml::INPUT_SIZE_XLARGE)); ?>

            <?php echo $form->textFieldControlGroup($model, 'mobile_no', array('size' => TbHtml::INPUT_SIZE_XLARGE)); ?>

            <?php if ($model->isNewRecord) { ?>

                <?php $user_Login = new UserLogin; ?>

                <?php echo $form->textFieldControlGroup($user_Login, 'password', array('size' => TbHtml::INPUT_SIZE_XLARGE)); ?>

                <?php echo $form->dropDownListControlGroup($user_Login, 'role', array('Bouncer' => 'Bouncer'), array('class' => 'input-large')); ?>
            <?php } ?>
            <div class='clear'></div>

            <hr>

            <div class='form_action'>
                <?php
                echo TbHtml::submitButton('Submit', array('color' => TbHtml::BUTTON_COLOR_PRIMARY));
                ?>
            </div>
            <?php $this->endWidget(); ?>
        </div>
    </div>
</div>