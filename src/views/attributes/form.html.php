<?php
if ($form) :
?>

    <div class="p-3 mb-3 bg-light">
        <a href="/attributes">Back to list</a>

    </div>

    <form method="POST">
        <?= $form->row('title'); ?>
        <?= $form->row('type'); ?>
        <?= $form->row('linkedAttribute'); ?>

        <div class="row mb-3">
            <span class="col-sm-2 form-label"></span>
            <div class="col-sm-6">
                <button type="submit" class="btn btn-primary">Save</button>
                <?php if (isset($attribute)) : ?>
                    <a class="btn btn-danger" href="/attributes/delete/<?= $attribute->id ?>">Delete</a>
                <?php endif ?>
            </div>
        </div>
    </form>

<?php else : ?>
    <div class="alert alert-success"><?= $message ?></div>

<?php endif; ?>