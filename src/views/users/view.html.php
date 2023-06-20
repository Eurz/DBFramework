<div class="p-3 mb-3 bg-light"><a href="/users">Back to list</a></div>

<table class="table">
    <tbody>

        <?php
        if ($user) : ?>
            <tr>
                <th scope="col">Date of birth</th>
                <td><?= $user->dateOfBirth ?></td>
            </tr>
            <tr>
                <th scope="col">Type</th>
                <td><?= $user->userType ?></td>
            </tr>
            <tr>
                <th scope="col">Nationality</th>
                <td><?= $user->nationality ?></td>
            </tr>
            <tr>
                <th scope="col">Created at</th>
                <td><?= $user->createdAt ?></td>
            </tr>
            <tr>
                <th>Specialities</th>
                <td>
                    <?php
                    if ($user->specialities) {
                        foreach ($user->specialities as $speciality) : ?>
                            <?= $speciality->title; ?> <br>
                        <?php endforeach;
                    } else { ?>
                        ---
                    <?php } ?>

                </td>
            </tr>
            <tr>

                <td class="text-end" colspan="2">

                    <a href="/users/edit/<?= $user->id ?>" class="btn btn-primary btn-sm">Edit</a>
                    <a href="/users/delete/<?= $user->id ?>" class="btn btn-danger btn-sm">Delete</a>
                </td>
            </tr>
        <?php else : ?>

            <tr>
                <td colspan="2">Who is it?</td>
            </tr>
        <?php endif ?>

    </tbody>
</table>