<div class="page-header">
    <h2><?php echo lang('charge_on_delivery');?></h2>
</div>

<?php echo form_open_multipart('admin/cod/form'); ?>

    <label for="enabled"><?php echo lang('enabled');?> </label>
    <?php echo form_dropdown('enabled', array('0' => lang('disabled'), '1' => lang('enabled')), set_value('enabled',$enabled)); ?>

    <div class="form-actions">
        <button type="submit" class="btn btn-primary"><?php echo lang('save');?></button>
    </div>
</form>

<script type="text/javascript">
$('form').submit(function() {
    $('.btn').attr('disabled', true).addClass('disabled');
});
</script>