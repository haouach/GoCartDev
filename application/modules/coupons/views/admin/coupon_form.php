<?php echo form_open('admin/coupons/form/'.$id); ?>

<div class="alert alert-info" style="text-align:center;">
    <strong><?php echo sprintf(lang('times_used'), @$num_uses);?></strong>
</div>
<div class="row">
    <div class="span4">
        <fieldset>
            <label for="code"><?php echo lang('coupon_code');?></label>
            <?php
            $data   = array('name'=>'code', 'value'=>set_value('code', $code), 'class'=>'span3');
            echo form_input($data);
            ?>
            
            <label for="max_uses"><?php echo lang('max_uses');?></label>
            <?php
            $data   = array('name'=>'max_uses', 'value'=>set_value('max_uses', $max_uses), 'class'=>'span3');
            echo form_input($data);
            ?>
            
            <label for="max_product_instances"><?php echo lang('limit_per_order')?></label>
            <?php
            $data   = array('name'=>'max_product_instances', 'value'=>set_value('max_product_instances', $max_product_instances), 'class'=>'span3');
            echo form_input($data);
            ?>
            
            <label for="start_date"><?php echo lang('enable_on');?></label>
            <?php
            $data   = array('name'=>'start_date', 'data-value'=>set_value('start_date', reverse_format($start_date)), 'class'=>'datepicker span3');
            echo form_input($data);
            ?>
            
            <label for="end_date"><?php echo lang('disable_on');?></label>
            <?php
            $data   = array('name'=>'end_date', 'data-value'=>set_value('end_date', reverse_format($end_date)), 'class'=>'datepicker span3');
            echo form_input($data);
            ?>
            
            <label for="reduction_target"><?php echo lang('coupon_type');?></label>
            <?php
                $options = array(
                'price'  => lang('price_discount'),
                'shipping' => lang('free_shipping')
                );
                echo form_dropdown('reduction_target', $options,  $reduction_target, 'id="gc_coupon_type" class="span3"');
            ?>
            <label for="reduction_amount"><?php echo lang('reduction_amount')?></label>
            <div class="row">
                <div class="span1">
                <?php   $options = array(
                      'percent'  => lang('percentage'),
                      'fixed' => lang('fixed')
                    );
                    echo ' '.form_dropdown('reduction_type', $options,  $reduction_type, 'class="span2"');
                ?>
                </div>
                <div class="span2">
                    <?php
                        $data   = array('id'=>'reduction_amount', 'name'=>'reduction_amount', 'value'=>set_value('reduction_amount', $reduction_amount), 'class'=>'span1');
                        echo form_input($data);?>
                </div>
            </div>
        </fieldset>
    </div>
    <div class="span6 offset1 well">
        <?php
            $options = array(
              '1' => lang('apply_to_whole_order'),
              '0' => lang('apply_to_select_items')
            );
            echo form_dropdown('whole_order_coupon', $options,  set_value(0, $whole_order_coupon), 'id="gc_coupon_appliesto_fields"');
        ?>
        <div id="gc_coupon_products">
            <table width="100%" border="0" style="margin-top:10px;" cellspacing="5" cellpadding="0">
            <?php echo $product_rows; ?>
            </table>
        </div>
    </div>
</div>

<div class="form-actions">
    <button type="submit" class="btn btn-primary"><?php echo lang('save');?></button>
</div>
</form>

<script type="text/javascript">
$('form').submit(function() {
    $('.btn').attr('disabled', true).addClass('disabled');
});

$(document).ready(function(){
    $("#gc_tabs").tabs();
    
    if($('#gc_coupon_type').val() == 'shipping')
    {
        $('#gc_coupon_price_fields').hide();
    }
    
    $('#gc_coupon_type').bind('change keyup', function(){
        if($(this).val() == 'price')
        {
            $('#gc_coupon_price_fields').show();
        }
        else
        {
            $('#gc_coupon_price_fields').hide();
        }
    });
    
    if($('#gc_coupon_appliesto_fields').val() == '1')
    {
        $('#gc_coupon_products').hide();
    }
    
    $('#gc_coupon_appliesto_fields').bind('change keyup', function(){
        if($(this).val() == 0)
        {
            $('#gc_coupon_products').show();
        }
        else
        {
            $('#gc_coupon_products').hide();
        }
    });
});

</script>