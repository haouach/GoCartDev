<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Go Cart<?php echo (isset($page_title))?' :: '.$page_title:''; ?></title>

<link href="<?php echo base_url('assets/css/bootstrap.css');?>" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url('assets/css/bootstrap-responsive.css');?>" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url('assets/css/fontawesome.css');?>" rel="stylesheet" type="text/css" />
<link type="text/css" href="<?php echo base_url('assets/css/redactor.css');?>" rel="stylesheet" />
<link type="text/css" href="<?php echo base_url('assets/css/pickadate/default.css');?>" rel="stylesheet" />
<link type="text/css" href="<?php echo base_url('assets/css/pickadate/default.date.css');?>" rel="stylesheet" />

<script type="text/javascript" src="<?php echo base_url('assets/js/jquery-2.1.3.min.js');?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/jquery-ui.js');?>"></script>

<script type="text/javascript" src="<?php echo base_url('assets/js/pickadate/picker.js');?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/pickadate/picker.date.js');?>"></script>

<script type="text/javascript" src="<?php echo base_url('assets/js/bootstrap.min.js');?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/redactor.min.js');?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/spin.js');?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/textarea.js');?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/mustache.min.js');?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/redactor_lang/'.config_item('language').'.js');?>"></script>

<?php if(CI::auth()->isLoggedIn(false, false)):?>
    
<style type="text/css">
    body {
        margin-top:50px;
    }
    
    @media (max-width: 979px){ 
        body {
            margin-top:0px;
        }
    }
    @media (min-width: 980px) {
        .nav-collapse.collapse {
            height: auto !important;
            overflow: visible !important;
        }
     }
    
    .nav-tabs li a {
        text-transform:uppercase;
        background-color:#f2f2f2;
        border-bottom:1px solid #ddd;
        text-shadow: 0px 1px 0px #fff;
        filter: dropshadow(color=#fff, offx=0, offy=1);
        font-size:12px;
        padding:5px 8px;
    }
    
    .nav-tabs li a:hover {
        border:1px solid #ddd;
        text-shadow: 0px 1px 0px #fff;
        filter: dropshadow(color=#fff, offx=0, offy=1);
    }

</style>
<script type="text/javascript">
$(document).ready(function(){
    $('.datepicker').pickadate({formatSubmit:'yyyy-mm-dd', hiddenName:true, format:'mm/dd/yyyy'});
    //$('.datepicker').datepicker({dateFormat: 'yy-mm-dd'});
    
    $('.redactor').redactor({
            lang: '<?php echo config_item('language');?>',
            minHeight: 200,
            imageUpload: '<?php echo site_url('admin/wysiwyg/upload_image');?>',
            fileUpload: '<?php echo site_url('admin/wysiwyg/upload_file');?>',
            imageGetJson: '<?php echo site_url('admin/wysiwyg/get_images');?>',
            imageUploadErrorCallback: function(json)
            {
                alert(json.error);
            },
            fileUploadErrorCallback: function(json)
            {
                alert(json.error);
            }
      });
});
</script>
<?php endif;?>
</head>
<body>
<?php if(CI::auth()->isLoggedIn(false, false)):?>
<div class="navbar navbar-fixed-top">
    <div class="navbar-inner">
        <div class="container">
            <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>

            <a class="brand" href="<?php echo site_url();?>">GoCart</a>
            
            <div class="nav-collapse">
                <ul class="nav">
                    <li><a href="<?php echo site_url('admin/dashboard');?>"><?php echo lang('common_home');?></a></li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo lang('common_sales'); ?> <b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li><a href="<?php echo site_url('admin/orders');?>"><?php echo lang('common_orders'); ?></a></li>
                            <?php if(CI::auth()->check_access('Admin')) : ?>
                                <li><a href="<?php echo site_url('admin/customers');?>"><?php echo lang('common_customers'); ?></a></li>
                                <li><a href="<?php echo site_url('admin/customers/groups');?>"><?php echo lang('common_groups'); ?></a></li>
                                <li><a href="<?php echo site_url('admin/reports');?>"><?php echo lang('common_reports'); ?></a></li>
                                <li><a href="<?php echo site_url('admin/coupons');?>"><?php echo lang('common_coupons'); ?></a></li>
                                <li><a href="<?php echo site_url('admin/gift-cards');?>"><?php echo lang('common_gift_cards'); ?></a></li>
                            <?php endif; ?>
                        </ul>
                    </li>



                    <?php
                    // Restrict access to Admins only
                    if(CI::auth()->check_access('Admin')) : ?>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo lang('common_catalog'); ?> <b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li><a href="<?php echo site_url('admin/categories');?>"><?php echo lang('common_categories'); ?></a></li>
                            <li><a href="<?php echo site_url('admin/products');?>"><?php echo lang('common_products'); ?></a></li>
                            <li><a href="<?php echo site_url('admin/digital_products');?>"><?php echo lang('common_digital_products'); ?></a></li>
                        </ul>
                    </li>
                    
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo lang('common_content'); ?> <b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li><a href="<?php echo site_url('admin/banners');?>"><?php echo lang('common_banners'); ?></a></li>
                            <li><a href="<?php echo site_url('admin/pages');?>"><?php echo lang('common_pages'); ?></a></li>
                        </ul>
                    </li>
                    
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo lang('common_administrative'); ?> <b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li><a href="<?php echo site_url('admin/settings');?>"><?php echo lang('common_gocart_configuration'); ?></a></li>
                            <li><a href="<?php echo site_url('admin/shipping');?>"><?php echo lang('common_shipping_modules'); ?></a></li>
                            <li><a href="<?php echo site_url('admin/payments');?>"><?php echo lang('common_payment_modules'); ?></a></li>
                            <li><a href="<?php echo site_url('admin/settings/canned_messages');?>"><?php echo lang('common_canned_messages'); ?></a></li>
                            <li><a href="<?php echo site_url('admin/locations');?>"><?php echo lang('common_locations'); ?></a></li>
                            <li><a href="<?php echo site_url('admin/users');?>"><?php echo lang('common_administrators'); ?></a></li>
                            <li><a href="<?php echo site_url('admin/sitemap');?>"><?php echo 'Sitemap'; ?></a></li>
                        </ul>
                    </li>
                    <?php endif; ?>
                </ul>
                <ul class="nav pull-right">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo lang('common_actions');?> <b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li><a href="<?php echo site_url('admin/dashboard');?>"><?php echo lang('common_dashboard'); ?></a></li>
                            <li><a href="<?php echo site_url();?>"><?php echo lang('common_front_end'); ?></a></li>
                            <li><a href="<?php echo site_url('admin/logout');?>"><?php echo lang('common_log_out'); ?></a></li>
                        </ul>
                    </li>
                </ul>
            </div><!-- /.nav-collapse -->
        </div>
    </div><!-- /navbar-inner -->
</div>
<?php endif;?>
<div class="container">
    <?php
    //lets have the flashdata overright "$message" if it exists
    if(CI::session()->flashdata('message'))
    {
        $message    = CI::session()->flashdata('message');
    }
    
    if(CI::session()->flashdata('error'))
    {
        $error  = CI::session()->flashdata('error');
    }
    
    if(function_exists('validation_errors') && validation_errors() != '')
    {
        $error  = validation_errors();
    }
    ?>
    
    <div id="js_error_container" class="alert alert-error" style="display:none;"> 
        <p id="js_error"></p>
    </div>
    
    <div id="js_note_container" class="alert alert-note" style="display:none;">
        
    </div>
    
    <?php if (!empty($message)): ?>
        <div class="alert alert-success">
            <a class="close" data-dismiss="alert">×</a>
            <?php echo $message; ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
        <div class="alert alert-error">
            <a class="close" data-dismiss="alert">×</a>
            <?php echo $error; ?>
        </div>
    <?php endif; ?>
</div>      

<div class="container">
    <?php if(!empty($page_title)):?>
    <div class="page-header">
        <h1><?php echo  $page_title; ?></h1>
    </div>
    <?php endif;?>
    
