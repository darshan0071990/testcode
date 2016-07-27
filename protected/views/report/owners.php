<style>
    .table-striped tbody > tr:nth-child(odd) > td, .table-striped tbody > tr:nth-child(odd) > th {
        background-color: #f1f1f1;
    }
    .row-fluid {
        background-color: #ffffff !important;
    }
</style>
<?php
$this->breadcrumbs = array(
    'Home' => array('home/index'),
    'Ownerrs SubscriptionReport'
);
?>
<script>
    $(document).ready(function () {
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>


<div id='body_content' class='container'>


    <div class="row-fluid">
        <div class="span4 margin">
            <button id="export_course_csv" class="btn btn-success" style="margin-left: 10px;">Export CSV</button>
        </div>
    </div>


    <div class="row-fluid">
        <?php
        $this->widget('yiiwheels.widgets.grid.WhGridView', array(
            'fixedHeader' => false,
            'headerOffset' => 40,
            'type' => 'bordered condensed',
            'dataProvider' => $dataProvider,
            'trClickable' => false,
            'responsiveTable' => true,
            'template' => "{items}{pager}{summary}",
            'pagerCssClass' => 'pagination-right',
            'columns' => array(
                'transaction_id::Transaction Number',
                'display_name::Customer Name',
                'username::Customer Email',
                'role::Role',
                'amount::Amount',
                'package_name::Package',
                'start_date::Subscription Date',
                'end_date::Subscription End Date',
                'type::Type',
            ),
        ));
        ?>
    </div>
