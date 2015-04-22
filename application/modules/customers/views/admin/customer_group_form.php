<?php echo form_open('admin/customers/group_form/'.$id); ?>

    <label><?php echo lang('group_name');?></label>
    <?php echo form_input('name', set_value('name', $name), 'class="span3"') ?>

    <label><?php echo lang('discount');?></label>
    <?php echo form_input('discount', set_value('discount', $discount), 'class="span3"'); ?>

    <label><?php echo lang('discount_type');?></label>
    <?php echo form_dropdown('discount_type', array('percent'=>'percent','fixed'=>'fixed'), set_value('discount_type', $discount_type), 'class="span3"');?>

    <div class="form-actions">
        <input class="btn btn-primary" type="submit" value="<?php echo lang('save');?>"/>
    </div>

</form>
