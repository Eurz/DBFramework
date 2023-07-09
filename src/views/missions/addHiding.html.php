<?php
if (isset($form)) :
?>
    <form method="POST">
        <?=
        $form->render();
        ?>


        <div class="row mb-3">
            <span class="col-sm-2 form-label"></span>
            <div class="col-sm-6">
                <button type="submit" class="btn btn-primary">Save</button>
                <?php if (isset($mission)) : ?>
                    <a href="/missions/delete/<?= $mission->id ?>" class="btn btn-danger">Delete</a>
                <?php endif ?>

            </div>
        </div>
    </form>

<?php else : ?>
    <p><?= $message ?></p>

    <a href="/hidings/add/<?= $action ?>" target="_blank" class="btn btn-primary">Add a <?= $action ?></a>
    <a href="/missions/add" class="btn btn-primary">Change country</a>
<?php endif ?>