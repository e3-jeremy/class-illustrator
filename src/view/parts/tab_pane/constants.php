
<?php foreach ($constants as $constant) { ?>
    <div class="card mb-1">
        <div  class="card-header" <?php echo !isset($disableOnClick) ? 'onClick="toggleContent(this)"' : ''; ?>>
            <h6 class="mb-1 fw-normal">
            <span class="fst-italic" style="color: #0086b3">
                <span class="fst-italic" style="color: #e20d0d">
                <?php echo htmlspecialchars($constant['modifier'], ENT_QUOTES); ?>
                </span>
                <?php echo htmlspecialchars($constant['name'], ENT_QUOTES); ?>
                </span>
                    <span class="px-3 smaller-font-size" style="">
                    <?php echo htmlspecialchars($constant['type'], ENT_QUOTES); ?>
                </span>
            </h6>
            <?php include __DIR__ . '/../component/arrow.php'; ?>
        </div>
        <div class="card-body py-1 method-info d-none" >
            <div class="mt-3 mb-2 border rounded-2 px-2" style="<?php echo (!$constant['value']['count']) ? 'display: none': ''; ?>">
            <pre class="mb-0 smaller-font-size p-4"><?php
                echo htmlspecialchars($constant['value']['data'], ENT_QUOTES);
                ?>
            </pre>
            </div>
            <div  class="card-header mt-3 mb-2 border border-primary rounded-2"" >
            <h6 class="mb-1 fw-normal">
                <span class="fst-italic" style="color: #0086b3">
                    <span class="fst-italic" style="color: #e20d0d">
                        <?php echo htmlspecialchars($constant['modifier'], ENT_QUOTES); ?>
                    </span>
                    <?php echo htmlspecialchars($constant['name'], ENT_QUOTES); ?>
                </span>
                <button class="btn btn-sm btn-danger float-end mr-3 position-relative"style="top: -4px;" onClick="toggleContent(this)">
                    Close
                </button>
            </h6>
        </div>
    </div>
</div>
<?php } ?>