<?php
if (isset($form)) :
?>




    <div class="p-3 mb-3 bg-light"><a href="/missions">Back to list</a></div>

    <form method="POST">
        <?= $form->render(); ?>

        <div class="row mb-3">
            <span class="col-sm-2 form-label"></span>
            <div class="col-sm-6">
                <button type="submit" class="btn btn-primary">Save</button>
                <?php if (isset($mission)) : ?>
                    <a href="/missions/delete/<?= $mission->id ?>" class="btn btn-danger">Delete</a>
                <?php endif ?>
                <button name="cancelMission" type="submit" value="Cancel" class="btn btn-danger">Cancel</button>



            </div>
        </div>
    </form>

<?php else : ?>

    <?php if (isset($message)) : ?>
        <div class="mb-3"><?= $message ?></div>
    <?php endif; ?>

<?php endif; ?>