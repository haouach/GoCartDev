<div class="page-header">
    <h3><?php echo lang('payment_methods');?></h3>
</div>

<?php if(count($modules) == 0):?>
    <div class="alert">
        <?php echo lang('error_no_payment_method');?>
    </div>
<?php elseif(GC::getGrandTotal() == 0):?>
    <div class="alert">
        <?php echo lang('no_payment_needed');?>
    </div>
    <a class="btn input-lg blue" href="<?php echo site_url('checkout/submit-order');?>"><?php echo lang('submit_order');?></a>
<?php else: ?>
    <div class="paymentError"></div>
    <div class="col-nest">
        <div class="col" data-cols="1/3">
            <table class="table">
            <?php foreach ($modules as $key => $module):?>
                <tr onclick="$(this).find('input').prop('checked', true).trigger('change');">
                    <td style="width:20px;"><input type="radio" name="paymentMethod" value="payment-<?php echo $key;?>"></td>
                    <td><?php echo $module['class']->getName();?></td>
                </tr>
            <?php endforeach;?>
            </table>
        </div>
        <div class="col" data-cols="2/3">

            <?php foreach ($modules as $key => $module):?>
                <div id="payment-<?php echo $key;?>" class="paymentMethod">
                    <?php echo $module['class']->checkoutForm();?>
                </div>
            <?php endforeach;?>

        </div>

    <script>
        $('[name="paymentMethod"]').change(function(){
            var paymentMethod = $(this);
            $('.paymentMethod').hide();
            $( '#'+paymentMethod.val() ).fadeIn(100);
        });
    </script>
<?php endif;?>