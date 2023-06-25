<div class="col bg-light p-3">
    <a href="/users/add/agent" class="btn btn-primary btn-sm"><i class="bi bi-person-fill"></i> Add agent</a>
    <a href="/users/add/contact" class="btn btn-primary btn-sm"><i class="bi bi-person-lines-fill"></i> Add contact</a>
    <a href="/users/add/target" class="btn btn-primary btn-sm"><i class="bi bi-bullseye"></i> Add target</a>
    <a href="/users/add/manager" class="btn btn-primary btn-sm">Add manager</a>
    <a href="#" class="btn btn-primary btn-sm">Filter</a>
    <!-- <a href="/users/add" class="btn btn-primary btn-sm">Add user</a> -->
</div>

<table class="table">
    <thead>
        <tr>
            <th scope="col">Firstname</th>
            <th scope="col">Lastname</th>
            <th scope="col">Date of birth</th>
            <th scope="col">Type</th>
            <th scope="col">Nationality</th>
            <th scope="col">Created at</th>
            <th scope="col">Missions</th>
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
                    <td><?= $user->createdAt ?></td>
                    <td><a href="/users/<?= $user->id ?>" class="btn btn-primary btn-sm">View</a></td>
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