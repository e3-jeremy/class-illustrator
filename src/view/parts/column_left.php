
<ul class="nav nav-tabs" id="classList" role="tablist">
    <?php include __DIR__ . '/tabs.php'; ?>
</ul>
<!-- Tab panes -->
<div class="tab-content">
    <div class="tab-pane active py-3" id="methods" role="tabpanel" aria-labelledby="methods-tab">
    <?php
        if(isset($methods)) include __DIR__ . '/tab_pane/methods.php';
    ?>
    </div>
    <div class="tab-pane py-3" id="properties" role="tabpanel" aria-labelledby="properties-tab">
    <?php
        if(isset($properties)) include __DIR__ . '/tab_pane/properties.php';
    ?>
    </div>
    <div class="tab-pane py-3" id="constants" role="tabpanel" aria-labelledby="constants-tab">
        <?php
        if(isset($constants)) include __DIR__ . '/tab_pane/constants.php';
        ?>
    </div>
    <div class="tab-pane py-3" id="traits" role="tabpanel" aria-labelledby="traits-tab">
        <?php
        if(isset($traits)) include __DIR__ . '/tab_pane/traits.php';
        ?>
    </div>
    <div class="tab-pane py-3" id="parents" role="tabpanel" aria-labelledby="parents-tab">
        <?php
        if(isset($traits)) include __DIR__ . '/tab_pane/parents.php';
        ?>
    </div>
</div>
