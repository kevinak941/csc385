<div class="container">
    <div class="bs-callout bs-callout-danger">
        <h4>Error <?php echo (isset($title) ? ' - '.$title : ''); ?></h4>
        <p><?php echo $message; ?></p>
        <?php if(isset($link)) { ?>
            <a href="<?php echo base_url($link); ?>"><?php echo (isset($linkMessage) ? $linkMessage : 'Back'); ?></a>
        <?php } ?>
    </div>
</div>