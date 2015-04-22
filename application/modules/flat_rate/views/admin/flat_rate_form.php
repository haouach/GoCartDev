<div class="page-header">
    <h2><?php echo lang('flat_rate');?></h2>
</div>

<?php echo form_open_multipart('admin/flat-rate/form'); ?>

    <label for="enabled"><?php echo lang('enabled');?> </label>
    <?php echo form_dropdown('enabled', array('0' => lang('disabled'), '1' => lang('enabled')), set_value('enabled',$enabled)); ?>

    <label for="enabled"><?php echo lang('rate');?> </label>
    <?php echo form_input(['name'=>'rate', 'value'=>set_value('rate', $rate)]); ?>

    <div class="form-actions">
        <button type="submit" class="btn btn-primary"><?php echo lang('save');?></button>
    </div>
</form>

<script type="text/javascript">
$('form').submit(function() {
    $('.btn').attr('disabled', true).addClass('disabled');
});
</script>