<div class="col-nest">
    <div class="col" data-cols="5/7">
        <div class="page-header">
        	<h2><?php echo lang('submit_order');?></small></h2>
        </div>
        <?php include('summary.php');?>
        <br>
        <p class="text-center">
            <a class="btn input-xl blue" href="<?php echo site_url('checkout/place_order');?>"><?php echo lang('submit_order');?></a>
        </p>
    </div>
    <div class="col order-details" data-cols="2/7">
        <?php include('order_details.php');?>
    </div>
</div>


