<div class="d-flex justify-content-between">
    <div class="col bg-light p-3">
        <a href="/hidings/add" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg"></i> Add an hiding</a>
    </div>

</div>

<form class="row p-3" method="GET" action="">

    <div class="col">
        <label for="country" class="form-label">Filter by</label>
        <select class="form-select form-select-sm" name="country" id="country" aria-label="Par champs">

            <option value="">Choose a country</option>
            <?php foreach ($countries as $country) : ?>
                <?php
                if (!empty($_GET['country']) && $_GET['country'] === (string)$country->id) {
                    $selected = 'selected';
                } else {

                    $selected = null;
                }
                ?>
                <option value="<?= $country->id ?>" <?= $selected  ?>><?= $country->title ?></option>
            <?php endforeach; ?>
        </select>
    </div>


    <div class="col">
        <label for="sortBy" class="form-label">Sort by</label>
        <select class="form-select form-select-sm" name="sortBy" id="sortBy" aria-label="Par champs">
            <option value="">Choose a field</option>
            <option value="code" <?= $filtersOptions['sortBy'] === 'code' ? 'selected="selected"' : null; ?>>Code name</option>
            <option value="country" <?= $filtersOptions['sortBy'] === 'country' ? 'selected="selected"' : null; ?>>Country</option>
            <option value="type" <?= $filtersOptions['sortBy'] === 'type' ? 'selected="selected"' : null; ?>>Hiding type</option>
        </select>
    </div>

    <div class="col">
        <label class="form-label" for="orderBy">Order </label>
        <select class="form-select form-select-sm" name="orderBy" aria-label="Par champs">
            <option value="ASC" <?= isset($_GET['orderBy']) && $_GET['orderBy'] === "ASC" ? 'selected' : null ?>> Growing</option>
            <option value="DESC" <?= isset($_GET['orderBy']) && $_GET['orderBy'] === 'DESC' ? 'selected' : null ?>> Decrease</option>
        </select>
    </div>
    <div class="col-12">
        <span class="form-label"><br /></span>
        <div class="">
            <button class="btn btn-primary">Go</button>
        </div>
    </div>
</form>

<div>

    <table class="table table-hover">
        <thead>
            <tr>
                <th>Code name</th>
                <th scope="col">Country</th>
                <th scope="col">Address</th>
                <th scope="col">Type</th>
                <th scope="col">Actions</th>
            </tr>
        </thead>
        <tbody>

            <?php
            if ($hidings) :


                foreach ($hidings as $hiding) {
            ?>
                    <tr>
                        <td><?= $hiding->code ?></td>
                        <td><?= $hiding->country ?? '---' ?> </td>
                        <td><?= $hiding->address ?> </td>
                        <td><?= $hiding->type ?? '---' ?> </td>
                        <td class="text-end">
                            <a href="hidings/edit/<?= $hiding->id ?>" class="btn btn-sm btn-primary"><i class="bi bi-pencil"></i></a>
                            <a href="hidings/delete/<?= $hiding->id ?>" class="btn btn-sm btn-danger"><i class="bi bi-trash3"></i></a>
                        </td>
                    </tr>

                <?php
                }
                ?>
            <?php else : ?>
                <tr>
                    <td colspan="5">There is no defined hidings</td>
                </tr>
            <?php endif; ?>

        </tbody>
    </table>
</div>

<?php if (isset($pagination)) : ?>
    <?= $pagination ?>
<?php endif ?>