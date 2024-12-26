<?php if (session()->getFlashdata('success')): ?>
    <div id="flash-message" class="alert alert-success mx-3">
        <?= session()->getFlashdata('success'); ?>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <div id="flash-message" class="alert alert-danger mx-3">
        <?= session()->getFlashdata('error'); ?>
    </div>
<?php endif; ?>