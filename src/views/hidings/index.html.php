<div class="d-flex justify-content-between">
    <div class="col bg-light p-3">
        <a href="/hidings/add" class="btn btn-primary btn-sm">Add an hiding</a>
    </div>

</div>

<div>

    <table class="table table-hover">
        <thead>
            <tr>
                <th>Code name</th>
                <th scope="col">Country</th>
                <th scope="col">Address</th>
                <th scope="col">Type</th>
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