<form class="row p-3" method="GET">
    <div class="row bg-light p-3 g-1 mb-3 d-flex justify-content-end">
        <?php if ($auth->grantedAccess('ROLE_ADMIN')) : ?>
            <div class="col">
                <a href="/missions/add" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-lg"></i>
                    Create a mission</a>
            </div>
        <?php endif; ?>
        <div class="col col-sm-12 col-lg-6">
            <?php include_once(VIEW_PATH . '/searchForm.html.php') ?>
        </div>


    </div>


    <div class="col-sm-6 col-md-3 mb-3">
        <label for="country" class="form-label">Filter by country</label>
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
    <div class="col-sm-6 col-md-3 mb-3">
        <label for="status" class="form-label">Filter by status</label>
        <select class="form-select form-select-sm" name="status" id="status" aria-label="Par champs">

            <option value="">Choose a status</option>
            <option value="">Choose a status</option>
            <?php foreach ($status as $item) : ?>
                <?php
                if ($filtersOptions['status'] === (int)$item->id) {
                    $selected = 'selected';
                } else {

                    $selected = null;
                }
                ?>
                <option value="<?= $item->id ?>" <?= $selected  ?>><?= $item->title ?></option>
            <?php endforeach; ?>
        </select>
    </div>


    <div class="col-sm-6 col-md-3 mb-3">
        <label for="sortBy" class="form-label">Sort by</label>
        <select class="form-select form-select-sm" name="sortBy" id="sortBy" aria-label="Par champs">
            <option value="">Choose a field</option>
            <option value="title" <?= $filtersOptions['sortBy'] === 'title' ? 'selected="selected"' : null; ?>>Title</option>
            <option value="status" <?= $filtersOptions['sortBy'] === 'status' ? 'selected="selected"' : null; ?>>Status</option>
            <option value="type" <?= $filtersOptions['sortBy'] === 'type' ? 'selected="selected"' : null; ?>>Mission type</option>
            <option value="country" <?= $filtersOptions['sortBy'] === 'country' ? 'selected="selected"' : null; ?>>Country</option>
            <option value="startDate" <?= $filtersOptions['sortBy'] === 'startDate' ? 'selected="selected"' : null; ?>>Start date</option>
        </select>
    </div>

    <div class="col-sm-6 col-md-3 mb-3">
        <label class="form-label" for="orderBy">Order </label>
        <select class="form-select form-select-sm" name="orderBy" aria-label="Par champs">
            <option value="ASC" <?= isset($_GET['orderBy']) && $_GET['orderBy'] === "ASC" ? 'selected' : null ?>> Crescent</option>
            <option value="DESC" <?= isset($_GET['orderBy']) && $_GET['orderBy'] === 'DESC' ? 'selected' : null ?>> Descending</option>
        </select>
    </div>
    <div class="col-12">
        <span class="form-label"><br /></span>
        <div class="">
            <button class="btn btn-primary">Go</button>
        </div>
    </div>
</form>
<?php if ($missions) : ?>
    <table class="table table-hover">
        <tr>
            <!-- <th>Title</th> -->
            <th>Title</th>
            <th>Status</th>
            <th>Country</th>
            <th>Type</th>
            <th>Start</th>
            <!-- 
            <th>End</th> -->
            <!--
            <th>Country</th>
            <th>Required</th> -->
            <th></th>
        </tr>
        <?php foreach ($missions as $mission) : ?>
            <tr>
                <!-- <td><?= $mission->title ?></td> -->
                <td><?= $mission->title ?></td>
                <td><?= $mission->status ?></td>
                <td><?= $mission->country ?></td>
                <td><?= $mission->type ?></td>
                <td><?= $mission->startDate ?></td>
                <!--
                <td><?= $mission->endDate ?></td>
                <td><?= $mission->speciality ?></td> -->
                <td class="text-end">

                    <a href="/missions/view/<?= $mission->id ?>" class="btn btn-primary btn-sm"><i class="bi bi-eye"></i></a>
                    <?php if ($auth->grantedAccess('ROLE_ADMIN')) : ?>
                        <a href="/missions/edit/<?= $mission->id ?>" class="btn btn-primary btn-sm"><i class="bi bi-pencil"></i></a>
                        <a href="/missions/delete/<?= $mission->id ?>" class="btn btn-danger btn-sm"><i class="bi bi-trash3"></i></a>
                    <?php endif ?>
                </td>
            </tr>

        <?php endforeach ?>
        </tr>
    </table>

    <?php if (isset($pagination)) : ?>
        <?= $pagination ?>
    <?php endif ?>
<?php else : ?>
    <p class="p-3">Actually, there is no missions for your selection</p>

<?php endif; ?>