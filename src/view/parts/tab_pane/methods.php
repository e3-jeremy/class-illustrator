
<?php foreach ($methods as $method) { ?>
    <div class="card mb-1">
        <div class="card-header" <?php echo !isset($disableOnClick) ? 'onClick="toggleContent(this)"' : ''; ?>>
            <h6 class="mb-1 fw-normal">
            <span class="fst-italic" style="color: #0086b3">
                <span class="fst-italic" style="color: #8c6e92">
                    <?php echo htmlspecialchars($method['modifier'], ENT_QUOTES); ?>
                </span>
                <?php echo htmlspecialchars($method['name'], ENT_QUOTES); ?>
            </span>
                <span class="" style="">
                    <span class="px-3" style="">
                        (
                        <?php if($method['arguments']) { ?>
                            <span class="smaller-font-size text-danger" style="">
                            <span class="smaller-font-size text-danger" style="">
                            <?php
                            echo '$' . implode(
                                    '</span>, <span class="smaller-font-size text-danger" style="">  $',
                                    $method['arguments']
                                );
                            ?>
                            </span>
                        </span>
                        <?php } ?>
                        )
                    </span>
                    <span class="smaller-font-size" style="">
                        : <?php echo htmlspecialchars($method['return_type'], ENT_QUOTES); ?>
                    </span>
                </span>
                <span class=" smaller-font-size fst-italic float-end" style=" margin-top: 5px;">
                    [&nbsp;<?php echo htmlspecialchars($method['arguments_count'], ENT_QUOTES); ?>&nbsp;Argument&nbsp;]
                </span>
                </h6>
                <?php include __DIR__ . '/../component/arrow.php'; ?>
            </div>
            <div class="card-body py-1 method-info d-none" >
                <pre class="mb-0 default-font-size" style="color: #3d3d3d;"><?php
                    echo '@@ ' . htmlspecialchars($method['file_location'], ENT_QUOTES) . '<br>';
                    ?>
                    </pre>
                <div class="p-3 rounded" style="background-color: #3b3c3eff;">
                    <pre class="mb-0 default-font-size" style="color: #fff;"><?php
                        echo $method['phpdoc'];
                    ?></pre>
                    <code class="mb-0 default-font-size" style="color: #fff; white-space: pre;"><?php
                        echo '<div>' . print_r($method['code'], true) . '</div>';
                    ?></code>
                </div>
                <div class="mt-3 mb-2 rounded-2" style="<?php echo (!$method['arguments_count']) ? 'display: none': ''; ?>">
                    <table class="table table-responsive default-font-size" width="100%" cellspacing="15">
                        <?php foreach ($method['parameters_info'] as $parameter) { ?>
                            <tr class="card-header border <?php echo ($method['arguments_count'] > $parameter['index']) ? 'border-bottom' : ''; ?>">
                                <td class="p-2" style="<?php echo ($parameter['has_type']) ? 'min-width: 225px;' : ''; ?>width: 225px;">
                            <span class="" style="">
                                Argument 1 :
                            </span>
                                    <span class="fst-italic" style="color: #0086b3;">
                                <?php echo htmlspecialchars($parameter['name'], ENT_QUOTES); ?>
                            </span>
                                </td>
                                <td class="px-1" <?php echo (!$parameter['has_type']) ? 'colspan="2"' : ''; ?> style="<?php echo ($parameter['has_type']) ? 'width: 80px;' : ''; ?>">
                            <span class="badge bg-danger bg-gradient text-white px-3 py-2" style=" height: 28px">
                                <?php echo htmlspecialchars(strtoupper($parameter['required']), ENT_QUOTES); ?>
                            </span>
                                </td>
                                <?php if($parameter['has_type']) { ?>
                                    <td>
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <div class="fst-italic px-1" style="color: #888; height: 28px">
                                                    |&nbsp;&nbsp;Allows&nbsp;Null&nbsp;:&nbsp;
                                                </div>
                                                <div class="fst-italic px-1" style="color: #888; height: 28px">
                                                    |&nbsp;&nbsp;Is&nbsp;Builtin&nbsp;:&nbsp;
                                                </div>
                                                <div class="fst-italic px-1" style="color: #888; height: 28px">
                                                    |&nbsp;&nbsp;Type&nbsp;:&nbsp;
                                                </div>
                                            </div>
                                            <div class="col-sm-9">
                                                <div style="height: 28px;">
                                                    <div class="badge bg-info bg-gradient text-white px-3 py-2" style="min-width: 75px;">
                                                        <?php echo htmlspecialchars(strtoupper($parameter['allows_null']), ENT_QUOTES); ?>
                                                    </div>
                                                </div>
                                                <div style="height: 28px;">
                                                    <div class="badge bg-info bg-gradient text-white px-3 py-2" style="min-width: 75px;">
                                                        <?php echo htmlspecialchars(strtoupper($parameter['is_builtin']), ENT_QUOTES); ?>
                                                    </div>
                                                </div>
                                                <div style="height: 28px;">
                                                    <div class="badge bg-info bg-gradient text-white px-3 py-2" style="min-width: 75px;">
                                                        <?php echo htmlspecialchars(strtoupper(str_replace('\\', ' \ ', $parameter['type'])), ENT_QUOTES); ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                <?php } ?>
                            </tr>
                        <?php } ?>
                    </table>
                </div>
             <div  class="card-header mt-3 mb-2 border border-primary rounded-2"" >
                <h6 class="mb-1 fw-normal">
                    <span class="fst-italic" style="color: #0086b3">
                        <span class="fst-italic" style="color: #e20d0d">
                            <?php echo htmlspecialchars($method['modifier'], ENT_QUOTES); ?>
                        </span>
                        <?php echo htmlspecialchars($method['name'], ENT_QUOTES); ?>
                    </span>
                    <button class="btn btn-sm btn-danger float-end mr-3 position-relative" style="top: -4px;" onClick="toggleContent(this)">
                        Close
                    </button>
                </h6>
            </div>
        </div>
    </div>
<?php } ?>