<?php echo form_open_multipart('admin/settings');?>

<fieldset>
    <legend><?php echo lang('shop_details');?></legend>
    <div class="row">
        <div class="span3">
            <label><?php echo lang('company_name');?></label>
            <?php echo form_input(array('class'=>'span3', 'name'=>'company_name', 'value'=>set_value('company_name', $company_name)));?>
        </div>

        <div class="span3">
            <label><?php echo lang('theme');?></label>
            <?php echo form_dropdown('theme', $themes, set_value('theme', $theme), 'class="span3"');?>
        </div>

        <div class="span3">
            <label><?php echo lang('select_homepage');?></label>
            <?php echo form_dropdown('homepage', $pages, set_value('homepage', $homepage), 'class="span3"');?>
        </div>

        <div class="span3">
            <label><?php echo lang('products_per_page');?></label>
            <?php echo form_input(array('class'=>'span3', 'name'=>'products_per_page', 'value'=>set_value('products_per_page', $products_per_page)));?>
        </div>
    </div>
    <label class="checkbox">
        <?php echo form_checkbox('google_product_fields', '1', set_value('google_product_fields',$google_product_fields));?> <?php echo lang('google_product_fields');?>
    </label>
</fieldset>

<fieldset>
    <legend><?php echo lang('email_settings');?></legend>
    <div class="row">
        <div class="span4">
            <label><?php echo lang('email_to');?></label>
            <?php echo form_input(array('class'=>'span4', 'name'=>'email_to', 'value'=>set_value('email_to', $email_to)));?>
        </div>

        <div class="span4">
            <label><?php echo lang('email_from');?></label>
            <?php echo form_input(array('class'=>'span4', 'name'=>'email_from', 'value'=>set_value('email_from', $email_from)));?>
        </div>

        <div class="span4">
            <label><?php echo lang('email_method');?></label>
            <?php echo form_dropdown('email_method', ['mail'=>'Mail', 'smtp'=>'SMTP', 'sendmail'=>'Sendmail'], set_value('email_method', $email_method), 'class="span4" id="emailMethod"');?>
        </div>
    </div>

    <div class="row emailMethods" id="email_smtp">
        <div class="span3">
            <label><?php echo lang('smtp_server');?></label>
            <?php echo form_input(array('class'=>'span3', 'name'=>'smtp_server', 'value'=>set_value('smtp_server', $smtp_server)));?>
        </div>

        <div class="span3">
            <label><?php echo lang('smtp_port');?></label>
            <?php echo form_input(array('class'=>'span3', 'name'=>'smtp_port', 'value'=>set_value('smtp_port', $smtp_port)));?>
        </div>

        <div class="span3">
            <label><?php echo lang('smtp_username');?></label>
            <?php echo form_input(array('class'=>'span3', 'name'=>'smtp_username', 'value'=>set_value('smtp_username', $smtp_username)));?>
        </div>

        <div class="span3">
            <label><?php echo lang('smtp_password');?></label>
            <?php echo form_input(array('class'=>'span3', 'name'=>'smtp_password', 'value'=>set_value('smtp_password', $smtp_password)));?>
        </div>
    </div>

    <div class="row emailMethods" id="email_sendmail">
        <div class="span4">
            <label><?php echo lang('sendmail_path');?></label>
            <?php echo form_input(array('class'=>'span4', 'name'=>'sendmail_path', 'value'=>set_value('sendmail_path', $sendmail_path)));?>
        </div>
    </div>

</fieldset>

<fieldset>
    <legend><?php echo lang('ship_from_address');?></legend>

    <label><?php echo lang('country');?></label>
    <?php echo form_dropdown('country_id', $countries_menu, set_value('country_id', $country_id), 'id="country_id" class="span12"');?>

    <div class="row">
        <div class="span6">
            <label><?php echo lang('address1');?></label>
            <?php echo form_input(array('name'=>'address1', 'class'=>'span12','value'=>set_value('address1',$address1)));?>
        </div>
    </div>

    <div class="row">
        <div class="span6">
            <?php echo form_input(array('name'=>'address2', 'class'=>'span12','value'=> set_value('address2',$address2)));?>
        </div>
    </div>

    <div class="row">
        <div class="span4">
            <label><?php echo lang('city');?></label>
            <?php echo form_input(array('name'=>'city','class'=>'span4', 'value'=>set_value('city',$city)));?>
        </div>
        <div class="span6">
            <label><?php echo lang('state');?></label>
            <?php echo form_dropdown('zone_id', $zones_menu, set_value('zone_id', $zone_id), 'id="zone_id" class="span6"');?>
        </div>
        <div class="span2">
            <label><?php echo lang('zip');?></label>
            <?php echo form_input(array('maxlength'=>'10', 'class'=>'span2', 'name'=>'zip', 'value'=> set_value('zip',$zip)));?>
        </div>
    </div>
</fieldset>

<fieldset>
    <legend><?php echo lang('locale_currency');?></legend>

    <div class="row">
        <div class="span6">
            <label><?php echo lang('locale');?></label>
            <?php echo form_dropdown('locale', $locales, set_value('locale', $locale), 'class="span6"');?>
        </div>
        <div class="span6">
            <label><?php echo lang('currency');?></label>
            <?php echo form_dropdown('currency_iso', $iso_4217, set_value('currency_iso', $currency_iso), 'class="span6"');?>
        </div>

    </div>

</fieldset>

<fieldset>
    <legend><?php echo lang('security');?></legend>

    <div class="row">
        <div class="span4">
            <label><?php echo lang('stage_username'); ?></label>
            <?php echo form_input(['class'=>'span4', 'name'=>'stage_username', 'value'=>set_value('stage_username',$stage_username)]);?>
        </div>
        <div class="span4">
            <label><?php echo lang('stage_password'); ?></label>
            <?php echo form_input(['class'=>'span4', 'name'=>'stage_password', 'value'=>set_value('stage_password',$stage_password)]);?>
        </div>
    </div>

    <label class="checkbox">
        <?php echo form_checkbox('ssl_support', '1', set_value('ssl_support',$ssl_support));?> <?php echo lang('ssl_support');?>
    </label>

    <label class="checkbox">
        <?php echo form_checkbox('require_login', '1', set_value('require_login',$require_login));?> <?php echo lang('require_login');?>
    </label>

    <label class="checkbox">
        <?php echo form_checkbox('new_customer_status', '1', set_value('new_customer_status',$new_customer_status));?> <?php echo lang('new_customer_status');?>
    </label>

</fieldset>

<fieldset>
    <legend><?php echo lang('package_details');?></legend>

    <div class="row">
        <div class="span3">
            <label><?php echo lang('weight_unit');?></label>
            <?php echo form_input(array('name'=>'weight_unit', 'class'=>'span3','value'=>set_value('weight_unit',$weight_unit)));?>
        </div>
        <div class="span3">
            <label><?php echo lang('dimension_unit');?></label>
            <?php echo form_input(array('name'=>'dimension_unit', 'class'=>'span3','value'=>set_value('dimension_unit',$dimension_unit)));?>
        </div>
    </div>
</fieldset>

<fieldset>
    <legend><?php echo lang('order_inventory');?></legend>

    <table class="table">
        <thead>
            <tr>
                <th><?php echo lang('order_status');?></th>
                <th><?php echo lang('order_statuses');?></th>
                <th style="text-align:right;">
                    <input type="text" value="" id="new_order_status_field" style="margin:0px;" placeholder="<?php echo lang('status_name');?>"/>
                    <button type="button" class="btn" onclick="add_status()"><i class="icon-plus"></i></button>
                </th>
            </tr>
        </thead>
        <tbody id="orderStatuses">
        </tbody>
    </table>
    <?php echo form_textarea(array('name'=>'order_statuses', 'value'=>set_value('order_statuses',$order_statuses), 'id'=>'order_statuses_json'));?>

    <label class="checkbox">
        <?php echo form_checkbox('inventory_enabled', '1', set_value('inventory_enabled',$inventory_enabled));?> <?php echo lang('inventory_enabled');?>
    </label>

    <label class="checkbox">
        <?php echo form_checkbox('allow_os_purchase', '1', set_value('allow_os_purchase',$allow_os_purchase));?> <?php echo lang('allow_os_purchase');?>
    </label>

</fieldset>

<fieldset>
    <legend><?php echo lang('tax_settings');?></legend>

    <label><?php echo lang('tax_address');?></label>
    <?php $address_options = array('ship'=>lang('shipping_address'), 'bill'=>lang('billing_address'));?>
    <?php echo form_dropdown('tax_address', $address_options, set_value('tax_address',$tax_address));?>

    <label class="checkbox">
        <?php echo form_checkbox('tax_shipping', '1', set_value('tax_shipping',$tax_shipping));?> <?php echo lang('tax_shipping');?>
    </label>

</fieldset>


<input type="submit" class="btn btn-primary" value="<?php echo lang('save');?>" />

</form>

<script type="text/template" id="orderStatusTemplate">
    <tr>
        <td>
            <input type="radio" value="{{status}}" name="order_status">
        </td>
        <td>
            {{status}}
        </td>
        <td style="text-align:right;">
            <button type="button" class="removeOrderStatus btn btn-danger" value="{{status}}"><i class="icon-cancel"></i></button>
        </td>
    </tr>
</script>


<script>

    var orderStatus = <?php echo json_encode($order_status);?>;
    var orderStatuses = <?php echo $order_statuses;?>;
    var orderStatusTemplate = $('#orderStatusTemplate').html();

    function renderOrderStatus()
    {
        $('#orderStatuses').html('');
        $.each(orderStatuses, function(id, val){
            var data = {status:val}
            var output = Mustache.render(orderStatusTemplate, data);
            $('#orderStatuses').append(output);
            $('input[value="'+orderStatus+'"]').prop('checked', true);
        });
        //update the order_statuses_json field
        $('#order_statuses_json').val( JSON.stringify(orderStatuses) );
    }

    function add_status()
    {
        var status = $('#new_order_status_field').val();
        orderStatuses[status] = status;
        renderOrderStatus();

        $('#new_order_status_field').val('');
    }

    function deleteStatus(status)
    {
        delete orderStatuses[status];
        renderOrderStatus();
    }

    $(document).ready(function(){
        $('#country_id').change(function(){
            $.post('<?php echo site_url('admin/locations/get_zone_menu');?>',{id:$('#country_id').val()}, function(data) {
              $('#zone_id').html(data);
            });
        });

        renderOrderStatus();

        $('#emailMethod').bind('click change keyup keypress', function(){
            $('.emailMethods').hide();
            $('#email_'+$(this).val()).show();
        });


        $('#new_order_status_field').on('keyup', function(event){
            if (event.which == 13) {
                add_status();
            }
        }).keypress(function(event){
            if (event.which  == 13) {
                event.preventDefault();
                return false;
            }
        });

        $('#orderStatuses').on('click', '.removeOrderStatus', function(){
            if(confirm('<?php echo lang('confirm_delete_order_status');?>'))
            {
                deleteStatus($(this).val());
            }
        });

        $('#orderStatuses').on('change', 'input[name="order_status"]', function(){
            orderStatus = $(this).val();
        });
    });

</script>

<style type="text/css">
#order_statuses_json, .emailMethods {
   display:none;
}
#email_<?php echo $email_method;?> {
    display:block;
}
</style>
