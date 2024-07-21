
<?php foreach ($traits as $trait) { ?>
    <div class="card mb-1">
        <div  class="card-header"" onClick="toggleContent(this)">
            <h6 class="mb-1 fw-normal">
                <span class="fst-italic" style="color: #0086b3">
                    <span class="fst-italic" style="color: #e20d0d">
                        <?php echo htmlspecialchars($trait['modifier'], ENT_QUOTES); ?>
                    </span>
                    <?php echo htmlspecialchars($trait['name'], ENT_QUOTES); ?>
                </span>
            </h6>
            <?php include __DIR__ . '/../component/arrow.php'; ?>
        </div>
        <div class="card-body py-1 method-info d-none" >
            <div class="mt-3">
                <ul class="nav nav-tabs" id="classList" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active"
                                id="traits-methods-tab"
                                data-bs-toggle="tab"
                                data-bs-target="#traits-methods"
                                type="button"
                                role="tab"
                                aria-controls="traits-methods"
                                aria-selected="true">METHODS</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link"
                                id="traits-properties-tab"
                                data-bs-toggle="tab"
                                data-bs-target="#traits-properties"
                                type="button" role="tab"
                                aria-controls="traits-properties"
                                aria-selected="false">PROPERTIES</button>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active py-3" id="traits-methods" role="tabpanel" aria-labelledby="traits-methods-tab">
                    <?php
                        $disableOnClick = true;
                        $methods = $trait['methods'];
                        if($methods) include __DIR__ . '/methods.php';
                    ?>
                    </div>
                    <div class="tab-pane py-3" id="traits-properties" role="tabpanel" aria-labelledby="traits-properties-tab">
                    <?php
                        $properties = $trait['properties'];
                        if($properties) include __DIR__ . '/properties.php';
                    ?>
                    </div>
                </div>
                <div  class="card-header mt-3 mb-2 border border-primary rounded-2"" >
                    <h6 class="mb-1 fw-normal">
                        <span class="fst-italic" style="color: #0086b3">
                            &nbsp;
                        </span>
                        <button class="btn btn-sm btn-danger float-end mr-3 position-relative" style="top: -4px;" onClick="toggleContent(this)">
                            Close
                        </button>
                    </h6>
                </div>
            </div>
        </div>
    </div>
<?php } ?>