<?php if(!empty($category)):?>
    <div class="page-header" style="margin-bottom:50px;">
        <h1><?php echo $category->name; ?></h1>
    </div>
<?php endif;
    
    include(__DIR__.'/index.php');?>

    <div class="text-center pagination">
        <?php echo CI::pagination()->create_links();?>
    </div>
</div>