<div>
    <div class="col" data-cols="1">
        <div class="page-header">
            <h3>Order Details</h3>
        </div>
    </div>
    <?php if(!empty($customer['bill_address'])):?>
    <div class="col" data-cols="1">
        <a href="<?php echo site_url('checkout/step_1');?>" class="pull-right"><i class="icon-pencil"></i></a>
        <strong><?php echo lang('billing_address');?></strong><br>
        
        <?php echo format_address($customer['bill_address'], true);?><br>
        <?php echo $customer['bill_address']['phone'];?><br/>
        <?php echo $customer['bill_address']['email'];?>

        <hr>
    </div>
    <?php endif;?>

<?php if(config_item('require_shipping')):?>
    <?php if(GC::requires_shipping()):?>
        <?php if(!empty($customer['ship_address'])):?>
            <div class="col" data-cols="1">
                <a href="<?php echo site_url('checkout/shipping_address');?>" class="pull-right"><i class="icon-pencil"></i></a>
                <strong><?php echo lang('shipping_address');?></strong><br>
                
                <?php echo format_address($customer['ship_address'], true);?><br>
                <?php echo $customer['ship_address']['phone'];?><br/>
                <?php echo $customer['ship_address']['email'];?>
                <hr>
            </div>
        <?php endif;?>

        <?php
        
        if(!empty($shipping_method) && !empty($shipping_method['method'])):?>
        <div class="col" data-cols="1">
            <a href="<?php echo site_url('checkout/step_2');?>" class="pull-right"><i class="icon-pencil"></i></a>
            <strong><?php echo lang('shipping_method');?></strong><br/>
            <?php echo $shipping_method['method'].': '.format_currency($shipping_method['price']);?>
            <hr>
        </div>
        <?php endif;?>
    <?php endif;?>
<?php endif;?>

<?php if(!empty($payment_method)):?>
    <div class="col" data-cols="1">
        <a href="<?php echo site_url('checkout/step_3');?>" class="pull-right"><i class="icon-pencil"></i></a>
        <strong><?php echo lang('payment_method');?></strong><br/>
        <?php echo $payment_method['description'];?>
    </div>
<?php endif;?>
</div>