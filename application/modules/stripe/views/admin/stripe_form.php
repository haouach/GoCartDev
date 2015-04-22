<div class="page-header">
    <h2><?php echo lang('stripe');?></h2>
</div>

<?php echo form_open_multipart('admin/stripe/form'); ?>

    <label for="enabled"><?php echo lang('enabled');?> </label>
    <?php echo form_dropdown('enabled', array('0' => lang('disabled'), '1' => lang('enabled')), set_value('enabled',$enabled)); ?>

    <label for="mode"><?php echo lang('mode');?></label>
    <?php echo form_dropdown('mode', array('test' => lang('test'), 'live' => lang('live')), set_value('mode',$mode)); ?>

    <label for="test-secret-key"><?php echo lang('test_secret_key');?></label>
    <?php echo form_input(['name'=>'test_secret_key', 'value'=>set_value('test_secret_key', $test_secret_key)]);?>

    <label for="test-publishable-key"><?php echo lang('test_publishable_key');?></label>
    <?php echo form_input(['name'=>'test_publishable_key', 'value'=>set_value('test_publishable_key', $test_publishable_key)]);?>

    <label for="live-secret-key"><?php echo lang('live_secret_key');?></label>
    <?php echo form_input(['name'=>'live_secret_key', 'value'=>set_value('live_secret_key', $live_secret_key)]);?>

    <label for="live-publishable-key"><?php echo lang('live_publishable_key');?></label>
    <?php echo form_input(['name'=>'live_publishable_key', 'value'=>set_value('live_publishable_key', $live_publishable_key)]);?>

    <div class="form-actions">
        <button type="submit" class="btn btn-primary"><?php echo lang('save');?></button>
    </div>
</form>

<script type="text/javascript">
$('form').submit(function() {
    $('.btn').attr('disabled', true).addClass('disabled');
});
</script>