<style>
    input[type="text"],input[type="password"]{
        font-size:18px;
        border-color:#BDC7D8;
    }
</style>
<div class="sign_in">
    Registration
</div>
<?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm',array(
    'layout' => TbHtml::FORM_LAYOUT_HORIZONTAL,
    'enableClientValidation'=>true,
    'clientOptions'=>array(
        'validateOnSubmit'=>true,
    ),)); ?>
<div class="control-group">
    <input class="span4" type="text" name="Users[name]" placeholder="Name" required>
</div>
<div class="control-group">
    <input class="span4"type="text" name="Users[email]" placeholder="Email" required>
</div>
<div class="control-group">
    <!--<input class="span4"type="text" name="Users[city]" placeholder="City" required>-->
    <?php $list=CHtml::listData(Cities::model()->findAll(array('order'=>'city ASC')), 'city', 'city'); ?>
    <?php echo CHtml::dropDownList('Users[city]','Select City', $list,array('class'=>'span4')); ?>
</div>
<div class="control-group">
    <!--<input class="span4"type="text" name="Users[country]" placeholder="Country" required>-->
    <?php $country = CHtml::listData(Cities::model()->findAll(array('order'=>'country ASC')), 'country', 'country'); ?>
    <?php echo CHtml::dropDownList('Users[country]','Select Country', $country,array('class'=>'span4')); ?>
</div>
<div class="control-group">
    <input class="span4"type="text" name="Users[mobile_no]" placeholder="Mobile" required>
</div>

<div class="control-group">
    <select name="UserLogin[role]" class="span4">
        <option value="BarOwner">Bar</option>
        <option value="ClubOwner">Club</option>
    </select>
</div>

<?php if(Yii::app()->user->hasFlash('error')){ ?>
    <div class="alert">
        <strong>Success!</strong><?php echo " ".Yii::app()->user->getFlash('error'); ?>
    </div>
<?php } ?>
<table style="margin: 0 auto;">
    <tr>
        <td><button class="btn btn-success" type="submit">Submit</button></td>
    </tr>
</table>

<?php $this->endWidget(); ?>

<script>
    $("#Users_country").change(function() {

        $.ajax({
            type: 'POST',
            data: {country: $(this).val()},
            url: '<?php echo CController::createUrl('home/loadcities') ?>',
            success: function(data){
                $('#Users_city').html(data)
            }
        })
    });
</script>