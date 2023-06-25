<div class="d-flex justify-content-between">
    <div class="col bg-light p-3">
        <a href="/hidings/add" class="btn btn-primary btn-sm"><i class="bi bi-house-door-fill"></i> Add an hiding</a>
    </div>

</div>

<form class="row p-3" method="GET" action="">
    <!-- <select name="country">
        <option value="france">France</option>
        <option value="japon">Japon</option>
    </select>
    <label>Type de planque </label>
    <select name="hidingType">
        <option value="maison">Maison</option>
        <option value="Villa">Villa</option>
    </select> -->

    <div class="col">
        <label for="filterByCountry" class="form-label">Filter by</label>
        <select class="form-select form-select-sm" name="filterByCountry" id="filterByCountry" aria-label="Par champs">

            <option value="">Choose a country</option>
            <?php foreach ($countries as $country) : ?>
                <?php
                if (!empty($_GET['filterByCountry']) && $_GET['filterByCountry'] === (string)$country->id) {
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
        <label for="field" class="form-label">Sort by</label>
        <select class="form-select form-select-sm" name="field" id="field" aria-label="Par champs">
            <option value="">Choose a field</option>
            <option value="code" <?= !empty($_GET['field']) && $_GET['field'] === 'code' ? 'selected="selected"' : null; ?>>Code name</option>
            <option value="country" <?= !empty($_GET['field']) && $_GET['field'] === 'country' ? 'selected="selected"' : null; ?>>Country</option>
            <option value="type" <?= !empty($_GET['field']) && $_GET['field'] === 'type' ? 'selected="selected"' : null; ?>>Hiding type</option>
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
            <a href="/hidings" class="btn btn-primary">Reset</a>
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
                        <td><?= $hiding->country ?> </td>
                        <td><?= $hiding->address ?> </td>
                        <td><?= $hiding->type ?> </td>
                        <td class="text-end">
                            <a href="hidings/edit/<?= $hiding->id ?>" class="btn btn-sm btn-primary">Edit</a>
                            <a href="hidings/delete/<?= $hiding->id ?>" class="btn btn-sm btn-danger">Delete</a>
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