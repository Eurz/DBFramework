<div class="col bg-light p-3">
    <a href="/users/add/agent" class="btn btn-primary btn-sm"><i class="bi bi-person-fill"></i> Add agent</a>
    <a href="/users/add/contact" class="btn btn-primary btn-sm"><i class="bi bi-person-lines-fill"></i> Add contact</a>
    <a href="/users/add/target" class="btn btn-primary btn-sm"><i class="bi bi-bullseye"></i> Add target</a>
    <a href="/users/add/manager" class="btn btn-primary btn-sm">Add manager</a>
    <a href="#" class="btn btn-primary btn-sm">Filter</a>
    <!-- <a href="/users/add" class="btn btn-primary btn-sm">Add user</a> -->
</div>
<form class="row g-3 p-3" method="GET" action="">
    <!-- <select name="country">
        <option value="france">France</option>
        <option value="japon">Japon</option>
    </select>
    <label>Type de planque </label>
    <select name="hidingType">
        <option value="maison">Maison</option>
        <option value="Villa">Villa</option>
    </select> -->

    <div class="col-sm-6 col-md-3 mb-2">
        <label for="userType" class="form-label">Filter by type</label>
        <select class="form-select form-select-sm" name="userType" id="userType" aria-label="Par champs">

            <option value="">Choose a field</option>
            <option value="agent" <?= $filtersOptions['userType'] === 'agent' ? 'selected="selected"' : null; ?>>Agent</option>
            <option value="contact" <?= $filtersOptions['userType'] === 'contact' ? 'selected="selected"' : null; ?>>Contact</option>
            <option value="target" <?= $filtersOptions['userType'] === 'target' ? 'selected="selected"' : null; ?>>Target</option>
            <option value="manager" <?= $filtersOptions['userType'] === 'manager' ? 'selected="selected"' : null; ?>>Manager</option>
        </select>
    </div>


    <div class="col-sm-6 col-md-3 mb-2">
        <label for="sortBy" class="form-label">Sort by</label>
        <select class="form-select form-select-sm" name="sortBy" id="sortBy" aria-label="Par champs">
            <option value="">Choose a field</option>
            <option value="type" <?= $filtersOptions['sortBy'] === 'type' ? 'selected="selected"' : null; ?>>User type</option>
            <option value="nationality" <?= $filtersOptions['sortBy'] === 'nationality' ? 'selected="selected"' : null; ?>>Nationality</option>
            <option value="createdAt" <?= $filtersOptions['sortBy'] === 'createdAt' ? 'selected="selected"' : null; ?>>Created at</option>
            <option value="firstName" <?= $filtersOptions['sortBy'] === 'firstName' ? 'selected="selected"' : null; ?>>First Name</option>
        </select>
    </div>

    <div class="col-sm-12 col-md-3 mb-2">
        <label class="form-label" for="orderBy">Order </label>
        <select class="form-select form-select-sm" name="orderBy" aria-label="Par champs">
            <option value="ASC" <?= $filtersOptions['orderBy'] === "ASC" ? 'selected' : null ?>> Growing</option>
            <option value="DESC" <?= $filtersOptions['orderBy'] === 'DESC' ? 'selected' : null ?>> Decrease</option>
        </select>
    </div>
    <div class="col-auto">
        <span class="form-label"><br /></span>
        <div class="">
            <button class="btn btn-sm btn-primary">Go</button>
        </div>
    </div>
</form>
<table class="table">
    <thead>
        <tr>
            <th scope="col">Firstname</th>
            <th scope="col">Lastname</th>
            <th scope="col">Date of birth</th>
            <th scope="col">Type</th>
            <th scope="col">Nationality</th>
            <th scope="col">Speciality</th>
            <th scope="col">Created at</th>
            <th scope="col"></th>

        </tr>
    </thead>
    <tbody>
        <?php
        if ($users) {
            foreach ($users as $user) : ?>
                <tr>
                    <td><?= $user->firstName ?></td>
                    <td><?= $user->lastName ?></td>
                    <td><?= $user->dateOfBirth ?></td>
                    <td><?= $user->type ?></td>
                    <td><?= $user->nationality ? $user->nationality : 'Unknown' ?></td>
                    <td>
                        <?php if ($user->type === 'agent') : ?>

                            <?php foreach ($user->specialities as $key => $speciality) : ?>
                                <?= $speciality->title ?>

                                <?php
                                if (count($user->specialities) !== $key + 1) {
                                    echo ',';
                                }
                                ?>


                            <?php endforeach ?>
                        <?php else : ?>
                            ---
                        <?php endif ?>


                    </td>

                    <td><?= $user->createdAt ?></td>
                    <td class="text-end">

                        <a href="/users/edit/<?= $user->id ?>" class="btn btn-primary btn-sm">Edit</a>
                        <a href="/users/delete/<?= $user->id ?>" class="btn btn-danger btn-sm">Delete</a>
                    </td>
                </tr>

            <?php endforeach;
        } else { ?>

            <tr>
                <td colspan="8">No users availables!!</td>
            </tr>
        <?php }; ?>

    </tbody>
</table>

<?php if (isset($pagination)) : ?>
    <?= $pagination ?>
<?php endif ?>