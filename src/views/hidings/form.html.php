<?php
if ($hiding) :
?>

    <?php
    if (isset($message)) {
    ?>
        <div class="alert alert-success">
            <?= $message ?>
        </div>
    <?php
    }
    ?>

    <div class="p-3 mb-3 bg-light"><a href="/hidings">Back to list</a></div>

    <form method="POST">


        <div class="row mb-3">
            <label for="title" class="col-sm-2 form-label">Title</label>
            <div class="col-lg-6 col-sm-10 ">
                <input class="form-control" type="text" name="code" id="code" value="<?= $hiding->code ?? '' ?>" />
            </div>
        </div>
        <div class="row mb-3">
            <label for="address" class="col-sm-2 form-label">Address</label>
            <div class="col-lg-6 col-sm-10 ">
                <input class="form-control" type="text" name="address" id="address" value="<?= $hiding->address ?? '' ?>" />
            </div>
        </div>

        <div class="row mb-3">

            <span class="col-sm-2 form-label">
                Country
            </span>
            <div class="col-sm-6">
                <select class="form-select form-select-sm" aria-label="Hiding type" name="countryId">

                    <?php
                    $selected = null;

                    foreach ($countries as $country) : ?>
                        <?php
                        if (isset($hiding->countryId)) {
                            $selected = $country->id === $hiding->countryId ? 'selected' : null;
                        }
                        ?>


                        <option value="<?= $country->id ?>" <?= $selected ?>>
                            <?= $country->title ?? '' ?>
                        </option>
                        </label>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="row mb-3">
            <label class="col-sm-2 form-label">
                Type
            </label>
            <div class="col-sm-6">

                <select class="form-select form-select-sm" aria-label="Hiding type" name="typeId">

                    <?php
                    $selected = null;

                    foreach ($hidingTypes as $type) : ?>
                        <?php
                        if (isset($hiding->typeId)) {
                            $selected = $type->id === $hiding->typeId ? 'selected' : null;
                        }
                        ?>
                        <option value="<?= $type->id ?>" <?= $selected ?>>
                            <?= $type->title ?? null ?>
                        </option>
                    <?php endforeach
                    ?>
                </select>
            </div>
        </div>
        <div class="row mb-3">

            <span class="col-sm-2 form-label"></span>
            <div class="col-sm-6"><button type="submit" class="btn btn-primary">Save</button></div>
        </div>
    </form>

<?php else : ?>
    <div class="alert alert-success"><?= $message ?></div>

<?php endif; ?>