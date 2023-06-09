<?php
if ($attribute) :
?>

    <?php
    if ($message) {
    ?>
        <div class="alert alert-success">
            <?= $message ?>
        </div>
    <?php
    }
    ?>


    <div class="row mb-3">

        <div class="p-3 mb-3 bg-light"><a href="/attributes">Back to attributes</a></div>
        <span class="col-sm-2 form-label">
            Type
        </span>
        <div class="col-sm-6">

            <?php
            $first = array_key_first($types);

            foreach ($types as $type => $name) : ?>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="type" id="type" value="<?= $type ?>" <?= $attribute->type === $type ? 'checked' : null; ?> />
                    <label class="form-check-label" for="type">
                        <?= $name ?>
                    </label>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="row mb-3">
        <label for="title" class="col-sm-2 form-label">Title</label>
        <div class="col-lg-6 col-sm-10 "> <input class="form-control" type="text" name="title" id="title" value="<?= $attribute->title ?>" /></div>
    </div>
    <div class="row mb-3">

        <span class="col-sm-2 form-label"></span>
        <div class="col-sm-6"><button type="submit" class="btn btn-primary">Save</button></div>
    </div>
    </form>

<?php else : ?>
    <div class="alert alert-success"><?= $message ?></div>

<?php endif; ?>