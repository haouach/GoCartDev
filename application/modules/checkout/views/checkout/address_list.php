<div class="page-header">
    <button id="addAddress" type="button" class="input-xs pull-right"><?php echo lang('add_address');?></button>
    <h3><?php echo lang('your_addresses');?></h3>
</div>
<?php if(count($addresses) > 0):?>
<div class="col-nest">
    <div class="col" data-cols="1/2" id="billingAddress">

        <div class="section-header">
            <h2><?php echo lang('billing_address');?></h2>
        </div>
        <table class="table zebra">
        <?php foreach($addresses as $a):?>
            <tr>
                <td><?php
                    $checked = (GC::getCart()->billing_address_id == $a['id'])?true:false;
                    echo form_radio(['name'=>'billing_address', 'value'=>$a['id'], 'checked'=>$checked]);?>
                </td>
                <td>
                    <?php echo format_address($a, true); ?>
                </td>
            </tr>
        <?php endforeach;?>
        </table>
    </div>
    <div class="col" data-cols="1/2" id="shippingAddress">
        <div id="shippingAddressContainer">
            <div class="section-header">
                <h2><?php echo lang('shipping_address');?></h2>
            </div>
            <table class="table zebra">
            <?php foreach($addresses as $a):?>
                <tr>
                    <td><?php
                        $checked = (GC::getCart()->shipping_address_id == $a['id'])?true:false;
                        echo form_radio(['name'=>'shipping_address', 'value'=>$a['id'], 'checked'=>$checked]);?>
                    </td>
                    <td>
                        <?php echo format_address($a, true); ?>
                    </td>
                </tr>
            <?php endforeach;?>
            </table>
        </div>
    </div>
</div>

<?php else:?>

<script>
$('.checkoutAddress').spin();
$('.checkoutAddress').load('addresses/form');
</script>

<?php endif;?>

<script>
$('#addAddress').click(function(){
    $('.checkoutAddress').spin();
    $('.checkoutAddress').load('addresses/form');
})

$('[name="billing_address"]').change(function(){
    $('#billingAddress').spin();
    $.post('<?php echo site_url('checkout/address');?>', {'type':'billing', 'id':$(this).val()}, function(data){
        if(data.error != undefined)
        {
            alert(data.error);
            closeAddressForm();
        }
        else
        {
            getCartSummary();
        }
        $('#billingAddress').spin(false);
    });
});

$('[name="shipping_address"]').change(function(){
    $('#shipingAddress').spin();
    $.post('<?php echo site_url('checkout/address');?>', {'type':'shipping', 'id':$(this).val()}, function(data){
        if(data.error != undefined)
        {
            alert(data.error);
            closeAddressForm();
        }
        else
        {
            getCartSummary();
        }
        $('#shipingAddress').spin(false);
    });
});

var billingAddresses = $('[name="billing_address"]');
var shippingAddresses = $('[name="shipping_address"]');

if(billingAddresses.length == 1)
{
    billingAddresses.attr('checked', true).change();
}

if(shippingAddresses.length == 1)
{
    shippingAddresses.attr('checked', true).change();
}
</script>