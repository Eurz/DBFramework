<div class="d-flex justify-content-between">
    <div class="col bg-light p-3">
        <a href="/attributes/add/country" class="btn btn-primary btn-sm">Add <?= $types['country'] ?></a>
        <a href="/attributes/add/nationality" class="btn btn-primary btn-sm">Add <?= $types['nationality'] ?></a>
        <a href="/attributes/add/status" class="btn btn-primary btn-sm">Add <?= $types['status'] ?></a>
        <a href="/attributes/add/hiding" class="btn btn-primary btn-sm">Add <?= $types['hiding'] ?></a>
        <a href="/attributes/add/speciality" class="btn btn-primary btn-sm">Add <?= $types['speciality'] ?></a>

    </div>



</div>
<form method="post" class="py-3">
    <?= $formFilter->render(); ?>
    <button type="submit" class="btn btn-primary">Go</button>
</form>

<div>

    <table class="table table-hover">
        <thead>
            <tr>
                <th>Title</th>
                <th scope="col">Created at</th>
                <th scope="col"></th>
            </tr>
        </thead>
        <tbody>

            <?php
            if ($attributes) :
                $previousType = '';


                foreach ($attributes as $attribute) {
                    if ($previousType !== $attribute->type) {
                        echo '<tr><th colspan="4" class="table-light">' . $types[$attribute->type] . '</th></tr>';
                    }
            ?>
                    <tr>
                        <td><?= $attribute->title ?></td>
                        <td><?= $attribute->createdAt ?> </td>
                        <td class="text-end">
                            <a href="/attributes/edit/<?= $attribute->id ?>" class="btn btn-sm btn-primary"><i class="bi bi-pencil"></i></a>
                            <a href="/attributes/delete/<?= $attribute->id ?>" class="btn btn-sm btn-danger"><i class="bi bi-trash3"></i></a>
                        </td>
                    </tr>

                <?php
                    $previousType = $attribute->type;
                }
                ?>
            <?php else : ?>
                <tr>
                    <td colspan="4">There is no defined attributes</td>
                </tr>
            <?php endif; ?>

        </tbody>
    </table>
</div>