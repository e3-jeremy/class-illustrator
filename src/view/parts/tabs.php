<!-- Nav tabs -->

<?php if (!empty($methods)) { ?>
<li class="nav-item" role="presentation">
    <button class="nav-link active"
            id="methods-tab"
            data-bs-toggle="tab"
            data-bs-target="#methods"
            type="button"
            role="tab"
            aria-controls="methods"
            aria-selected="true">METHODS</button>
</li>
<?php } ?>
<?php if (!empty($properties)) { ?>
<li class="nav-item" role="presentation">
    <button class="nav-link"
            id="properties-tab"
            data-bs-toggle="tab"
            data-bs-target="#properties"
            type="button" role="tab"
            aria-controls="properties"
            aria-selected="false">PROPERTIES</button>
</li>
<?php } ?>
<?php if (!empty($constants)) { ?>
<li class="nav-item" role="presentation">
    <button class="nav-link"
            id="constants-tab"
            data-bs-toggle="tab"
            data-bs-target="#constants"
            type="button" role="tab"
            aria-controls="constants"
            aria-selected="false">CONSTANTS</button>
</li>
<?php } ?>
<?php if (!empty($traits)) { ?>
<li class="nav-item" role="presentation">
    <button class="nav-link"
            id="traits-tab"
            data-bs-toggle="tab"
            data-bs-target="#traits"
            type="button" role="tab"
            aria-controls="traits"
            aria-selected="false">TRAITS</button>
</li>
<?php } ?>
<?php if (!empty($parents)) { ?>
<li class="nav-item" role="presentation">
    <button class="nav-link"
            id="parents-tab"
            data-bs-toggle="tab"
            data-bs-target="#parents"
            type="button" role="tab"
            aria-controls="parents"
            aria-selected="false">PARENTS</button>
</li>
<?php } ?>