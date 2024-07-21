<div id="header" class="card-header" onClick="toggleContent(this)" >
    <h2 class="header">
        <span class="fst-italic" style="color: #e20d0d">
            <?php echo htmlspecialchars($header['modifier'], ENT_QUOTES); ?>
        </span>
        <?php echo htmlspecialchars($header['name'], ENT_QUOTES); ?>
    </h2>
</div>
<pre class="px-3 mb-0 d-none"><?php
    echo '@@ ' . htmlspecialchars($header['file_name'], ENT_QUOTES) . '<br>';
    echo htmlspecialchars($header['class_php_doc'], ENT_QUOTES);
?>
</pre>