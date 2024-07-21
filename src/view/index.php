<div  id="main" class="<?php echo (isset($header) ? 'card': ''); ?>">
    <?php
        if(isset($header)) include __DIR__ . '/parts/header.php';
    ?>
    <div class="card-body">
        <div class="row">
        <div class="col-md-6 col-sn-12" <?php echo !isset($header) ? 'style="display: none;"': ''; ?>>
        <?php
            if(isset($methods) || isset($properties)) include __DIR__ . '/parts/column_left.php';
        ?>
        </div>
        <?php
            if(isset($toDebug)) include __DIR__ . '/parts/column_right.php';
        ?>
        </div>
    </div>
</div>