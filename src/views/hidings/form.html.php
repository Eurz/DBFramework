<?php
if (isset($form)) :
?>

    <div class="p-3 mb-3 bg-light"><a href="/hidings">Back to list</a></div>

    <form method="POST">
        <?= $form->row('code'); ?>
        <?= $form->row('address'); ?>
        <?= $form->row('typeId'); ?>
        <?= $form->row('countryId'); ?>

        <div class="row mb-3">
            <span class="col-sm-2 form-label"></span>
            <div class="col-sm-6">
                <button type="submit" class="btn btn-primary">Save</button>

                <?php if (isset($hiding)) : ?>
                    <a class="btn btn-danger" href="/hidings/delete/<?= $hiding->id ?>">Delete</a>
                <?php endif ?>

            </div>
        </div>
    </form>

<?php else : ?>
    <div><?= $message ?></div>

<?php endif; ?>