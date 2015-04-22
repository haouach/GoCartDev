<div class="col-nest">
    <div class="col" data-cols="2/3">

        <div class="checkoutAddress">
        <?php if(!empty($addresses))
        {
            $this->show('checkout/address_list', ['addresses'=>$addresses]);
        }
        else
        {
            (new GoCart\Controller\Addresses)->form();
        }
        ?>
        </div>

        <div id="shippingMethod"></div>
        <div id="paymentMethod"></div>

    </div>
    <div class="col" data-cols="1/3">
        <div id="orderSummary"></div>
    </div>
</div>

<script>
    var grandTotalTest = <?php echo (GC::getGrandTotal() > 0)?1:0;?>;

    function closeAddressForm(){
        $('.checkoutAddress').load('<?php echo site_url('checkout/address-list');?>');
    }

    $(document).ready(function(){
        //getBillingAddressForm();
        //getShippingAddressForm();
        //getShippingMethods();
        getCartSummary();
        getPaymentMethods();
    });

    function getCartSummary(callback)
    {
        //update shipping too
        getShippingMethods();

        $('#orderSummary').spin();
        $.post('<?php echo site_url('cart/summary');?>', function(data) {
            $('#orderSummary').html(data);
            if(callback != undefined)
            {
                callback();
            }
        });
    }

    function getShippingMethods()
    {
        $('#shippingMethod').load('<?php echo site_url('checkout/shipping-methods');?>');
    }

    function getPaymentMethods()
    {
        $('#paymentMethod').load('<?php echo site_url('checkout/payment-methods');?>');
    }
</script>