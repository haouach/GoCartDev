<?php echo form_open('admin/gift-cards/form/'); ?>

    <div class="row">
        <div class="span3">
            <label for="to_name"><?php echo lang('recipient_name');?> </label>
            <?php echo form_input(['name'=>'to_name', 'value'=>set_value('code'), 'class'=>'span3']);?>
        </div>
        <div class="span3">
            <label for="to_email"><?php echo lang('recipient_email');?></label>
            <?php echo form_input(['name'=>'to_email', 'value'=>set_value('to_email'), 'class'=>'span3']);?>
        </div>
    </div>

    <div class="row">
        <div class="span3">
            <label for="sender_name"><?php echo lang('sender_name');?></label>
            <?php echo form_input(['name'=>'from', 'value'=>set_value('from'), 'class'=>'span3']);?>
        </div>
        <div class="span3">
            <label for="beginning_amount"><?php echo lang('amount');?></label>
            <?php echo form_input(['name'=>'beginning_amount', 'value'=>set_value('beginning_amount'), 'class'=>'span3']);?>
        </div>
    </div>

    <div class="row">
        <div class="span3">
            <label for="personal_message"><?php echo lang('personal_message');?></label>
            <?php echo form_textarea(['name'=>'personal_message', 'value'=>set_value('personal_message'), 'class'=>'span3']);?>
        </div>
        <div class="span3">
            <label class="checkbox">
                <?php echo form_checkbox(['name'=>'sendNotification', 'value'=>'true']);?>
                <?php echo lang('send_notification');?>
            </label>
        </div>
    </div>

    <div class="form-actions">
        <input class="btn btn-primary" type="submit" value="<?php echo lang('save');?>"/>
    </div>
    
</form>