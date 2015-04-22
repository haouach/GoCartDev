<?php echo form_open_multipart('admin/banners/banner_form/'.$banner_collection_id.'/'.$banner_id); ?>
    <label for="name"><?php echo lang('name');?> </label>
    <?php echo form_input(['name'=>'name', 'value' => set_value('name', $name)]); ?>

    <label for="link"><?php echo lang('link');?> </label>
    <?php echo form_input(['name'=>'link', 'value' => set_value('link', $link)]); ?>

    <label for="enable_date"><?php echo lang('enable_date');?> </label>
    <?php echo form_input(['name'=>'enable_date', 'class'=>'datepicker', 'data-value'=>set_value('enable_date', $enable_date)]); ?>

    <label for="disable_date"><?php echo lang('disable_date');?> </label>
    <?php echo form_input(['name'=>'disable_date', 'class'=>'datepicker', 'data-value'=>set_value('disable_date', $disable_date)]); ?>

    <label class="checkbox">
        <?php echo form_checkbox(['name'=>'new_window', 'value'=>1, 'checked'=>set_checkbox('new_window', 1, $new_window)]); ?> <?php echo lang('new_window');?>
    </label>

    <label for="image"><?php echo lang('image');?> </label>
    <?php echo form_upload(['name'=>'image', 'id'=>'image']); ?>

    <?php if($banner_id && $image != ''):?>
    <div style="text-align:center; padding:5px; border:1px solid #ccc;"><img src="<?php echo base_url('uploads/'.$image);?>" alt="current"/><br/><?php echo lang('current_file');?></div>
    <?php endif;?>

    <div class="form-actions">
        <input class="btn btn-primary" type="submit" value="<?php echo lang('save');?>"/>
    </div>
</form>
<script>
    $('form').submit(function() {
        $('.btn').attr('disabled', true).addClass('disabled');
    });
</script>
