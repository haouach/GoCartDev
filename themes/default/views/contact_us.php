<?php echo form_open('contact'); ?>

<div class="page-header">
    <h3>Contact Information</h3>
</div>

<div class="col-nest">
    <div class="col" data-cols="1/2">
        
        <div class="col-nest">
            <div class="col" data-cols="1/2">
                <label for="firstname">First Name</label>
                <?php echo form_input(['name'=>'firstname', 'value'=>set_value('firstname')]);?>
            </div>

            <div class="col" data-cols="1/2">
                <label for="lastname">Last Name</label>
                <?php echo form_input(['name'=>'lastname', 'value'=>set_value('lastname')]);?>
            </div>
        </div>
        <div class="col-nest">
            <div class="col" data-cols="1/2">
                <label for="account_phone">Phone</label>
                <?php echo form_input(['name'=>'phone', 'value'=>set_value('phone')]);?>
            </div>

            <div class="col" data-cols="1/2">
                <label for="account_email">Email</label>
                <?php echo form_input(['name'=>'email', 'value'=>set_value('email')]);?>
            </div>
        </div>

        <div class="col-nest">
            <div class="col" data-cols="1">
                <label for="account_email">Message</label>
                <?php echo form_textarea(['name'=>'message', 'value'=>set_value('message'), 'rows'=>5]);?>
            </div>
        </div>

        <input type="submit" value="Submit!" class="blue" />
    </div>
</div>

</form>


<br><br>