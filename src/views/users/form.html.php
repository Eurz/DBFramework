<?php
if ($form) :
?>

    <?php

    if ($form->errors()) {
    ?>
        <div class="alert alert-success">
            <?= $form->errors() ?>
        </div>
    <?php
    }
    ?>


    <div class="p-3 mb-3 bg-light"><a href="/users">Back to list</a></div>

    <form method="POST">
        <?= $form->render(); ?>

        <div class="row mb-3">
            <span class="col-sm-2 form-label"></span>
            <div class="col-sm-6">
                <button type="submit" class="btn btn-primary">Save</button>
                <?php if (isset($user)) : ?>
                    <a href="/users/delete/<?= $user->id ?>" class="btn btn-danger">Delete</a>
                <?php endif ?>

            </div>
        </div>
    </form>

<?php else : ?>
    <div class="alert alert-success"><?= $message ?></div>

<?php endif; ?>