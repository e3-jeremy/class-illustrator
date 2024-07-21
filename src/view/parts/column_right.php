<div class=" <?php echo isset($header) ? 'col-md-6 col-sm-12': 'col-12'; ?>"  <?php echo !isset($toDebug) ? 'style="display: none;"': ''; ?>>
    <div class="card print_data py-2 px-5 mt-5" style="overflow: auto; background-color: #cbcbc9;">
        <pre class="mb-0 smaller-font-size py-5 <?php echo ($toDebug['print']) ? '' : 'notify'; ?>"><?php
            print_r($toDebug['toDebug']);
            ?>
        </pre>
    </div>
</div>