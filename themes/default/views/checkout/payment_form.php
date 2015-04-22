<div class="col-nest">
    <div class="col" data-cols="5/7">
        <div class="page-header">
            <h1><?php echo lang('payment_method');?></h1>
        </div>

        <label><?php echo lang('choose_payment_method');?></label>

        <div class="tabs">
            <ul>
            <?php
            if(empty($payment_method))
            {
                $selected   = key($payment_methods);
            }
            else
            {
                $selected   = $payment_method['module'];
            }
            foreach($payment_methods as $method=>$info):?>
                <li<?php echo ($selected == $method)?' class="active"':'';?>><a href="#payment-<?php echo $method;?>"><?php echo $info['name'];?></a></li>
            <?php endforeach;?>
            </ul>

            <?php foreach($payment_methods as $method=>$info):?>
                <div class="tab-content<?php echo ($selected == $method)?' active':'';?>" id="payment-<?php echo $method;?>">
                    <?php echo form_open('checkout/step_3', 'id="form-'.$method.'"');?>
                        <input type="hidden" name="module" value="<?php echo $method;?>" />
                        <?php echo $info['form'];?>
                        <input class="blue" type="submit" value="<?php echo lang('form_continue');?>"/>
                    </form>
                </div>
            <?php endforeach;?>

        </div>
    </div>
    <div class="col order-details" data-cols="2/7">
        <?php include('order_details.php');?>
    </div>
</div>