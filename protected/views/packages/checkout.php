<?php
/* @var $this PackagesController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
    'Dashboard'=>array('home/index'),
    'Subscription'=>array('packages/subscribed'),
    $package->package_name,
    '&nbsp; / Checkout',
);
?>

<style>
    .form-horizontal .controls {
        margin-left: 0px; !important
    }
    .form-actions {
        background-color: #003366;
    }
    .form-horizontal{
        margin-top:20px;
    }
    .form-horizontal .control-group {
        margin-bottom: 20px;
    }
    #page {
        margin: 5% auto !important;
    }

</style>
<div id="body_content" class="container">
    <div class="main_wrapper span4" id = "page">
    <?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
     'layout' => TbHtml::FORM_LAYOUT_HORIZONTAL,
     'id'=>'change-password',
     'enableClientValidation'=>true,
     'clientOptions'=>array(
         'validateOnSubmit'=>true,
     ),
     'htmlOptions' => array('class'=>'')
 ));
 ?>

        <?php echo $form->textFieldControlGroup($checkout,'full_name',array('label'=>false,'placeholder'=>'Full Name','class'=>'span4')); ?>

        <?php echo $form->dropDownListControlGroup($checkout,'type',array('American Express'=>'American Express','Visa'=>'Visa','Mastercard'=>'Mastercard','Discover'=>'Discover'),array('empty' => 'Select Card Type','span'=>4,'label'=>false)); ?>

        <?php echo $form->textFieldControlGroup($checkout,'credit_card',array('label'=>false,'placeholder'=>'Credit Card Number','class'=>'span4','maxlength'=>16)); ?>

        <div class="control-group">
            <?php echo TbHtml::controlsRow(array($form->dropDownList($checkout, 'month', array('01'=>'01','02'=>'02','03'=>'03','04'=>'04','05'=>'05','06'=>'06','07'=>'07','08'=>'08','09'=>'09','10'=>'10','11'=>'11','12'=>'12'), array('span' => 1,'label'=>'Month')), $form->dropDownList($checkout, 'year', array('16'=>'16','17'=>'17','18'=>'18','19'=>'19','20'=>'20','21'=>'21','22'=>'22','23'=>'23','24'=>'24','25'=>'25','26'=>'26','27'=>'27','28'=>'28','29'=>'29','30'=>'30','31'=>'31','32'=>'32','33'=>'33','34'=>'34','35'=>'35'), array('span' => 1,'label'=>'Year')))); ?>
        </div>

        <?php echo $form->textFieldControlGroup($checkout,'cvv',array('label'=>false,'placeholder'=>'CVV Number','class'=>'span4','maxlength'=>4)); ?>

        <?php echo $form->textFieldControlGroup($checkout,'amount',array('prepend' => '$', 'span' => 3,'label'=>false,'value'=>$package->amount,'disabled'=>true)); ?>

        <?php echo $form->textFieldControlGroup($checkout,'promo_code', array('placeholder' => 'Do You have Promo Code?','label'=>false,'span'=>4)); ?>

        <input type="hidden" id="amount" name="amount" value = "<?php echo $package->amount; ?>"/>

        <div class="status alert"></div>
        <div class='form_actions'>
            <?php echo TbHtml::submitButton('Checkout',array('color'=>TbHtml::BUTTON_COLOR_PRIMARY, 'size'=>TbHtml::BUTTON_SIZE_LARGE)); ?>
            <a class="btn btn-primary btn-large" id="apply">Apply Promo Code</a>
        </div>

        <?php $this->endWidget(); ?>
    </div>
</div>

<script>
    $("#apply").click(function(){
        var promo_code = $("#CheckoutForm_promo_code").val();
        var package_id = <?php echo $_GET['id'];?>;
        var amnt = $("#amount").val();
        if(promo_code.length === 0){
            alert("Please Enter Promo Code.");
        }else{
            $.ajax({
                dataType: "json",
                url: '<?php echo Yii::app()->request->baseUrl; ?>/packages/promo_code?pcode='+promo_code+'&package_id='+package_id+'&amnt='+amnt,
                success: function(data){
                    $(".status").html(data.status);
                    $("#amount").val(data.amount);
                    $("#CheckoutForm_amount").val(data.amount);

                }
            });
        }
    });
</script>