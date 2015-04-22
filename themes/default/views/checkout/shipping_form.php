
<div class="col-nest">
    <div class="col" data-cols="5/7">

        <div class="page-header">
            <h1><?php echo lang('shipping_method');?></h1>
        </div>

        <?php echo form_open('checkout/step_2');?>
            <div class="col-nest">
                <div class="col" data-cols="2/3">
                    <label><?php echo lang('choose_shipping_method');?></label>
                    <div class="alert red" id="shipping_error_box" style="display:none"></div>
                    <table class="table hover horizontal-border">
                        <?php
                        foreach($shipping_methods as $key=>$val):
                            $ship_encoded   = md5(json_encode(array($key, $val)));
                        
                            if($ship_encoded == $shipping_code)
                            {
                                $checked = true;
                            }
                            else
                            {
                                $checked = false;
                            }
                        ?>
                        <tr onclick="toggle_shipping('<?php echo $ship_encoded;?>');">
                            <td>
                                <?php echo form_radio('shipping_method', $ship_encoded, set_radio('shipping_method', $ship_encoded, $checked));?>
                            </td>
                            <td>
                                <?php echo $key;?>
                            </td>
                            <td class="text-right"><strong><?php echo $val['str'];?></strong></td>
                        </tr>
                        <?php endforeach;?>
                    </table>
                </div>
                <div class="col" data-cols="1/3">
                    <label><?php echo lang('shipping_instructions')?></label>
                    <?php echo form_textarea(array('name'=>'shipping_notes', 'rows'=>'5', 'value'=>set_value('shipping_notes', $this->go_cart->get_additional_detail('shipping_notes'))));?>
                </div>
            </div>
            <label>&nbsp;</label>
            <input class="input-lg blue" type="submit" value="<?php echo lang('form_continue');?>"/>
        </form>
    </div>
    <div class="col order-details" data-cols="2/7">
        <?php include('order_details.php');?>
    </div>
</div>
<script type="text/javascript">
    function toggle_shipping(key)
    {
        $('input[name="shipping_method"]:checked').prop('checked', false);
        $('input[value="'+key+'"]').prop('checked', true);
    }
</script>