<div class="d-flex justify-content-between">
    <div class="col bg-light p-3">
        <a href="/attributes/add" class="btn btn-primary btn-sm"><i class="bi bi-tag-fill"></i> Add an attribute</a>
        <!-- <a href="index.php?controller=attributes&task=add&type=country" class="btn btn-primary btn-sm">Add <?= $types['country'] ?></a>
        <a href="index.php?controller=attributes&task=add&type=nationality" class="btn btn-primary btn-sm">Add <?= $types['nationality'] ?></a>
        <a href="index.php?controller=attributes&task=add&type=status" class="btn btn-primary btn-sm">Add <?= $types['status'] ?></a>
        <a href="index.php?controller=attributes&task=add&type=hiding" class="btn btn-primary btn-sm">Add <?= $types['hiding'] ?></a>
        <a href="index.php?controller=attributes&task=add&type=speciality" class="btn btn-primary btn-sm">Add <?= $types['speciality'] ?></a>
        <a href="index.php?controller=attributes&task=add&type=type" class="btn btn-primary btn-sm">Add <?= $types['type'] ?></a>
        <a href="index.php?controller=attributes&task=add&type=userType" class="btn btn-primary btn-sm">Add <?= $types['userType'] ?></a> -->

    </div>

    <form method="post" class="">
        <!-- <select name="filter" class="form-select">
            <option value="country">Country</option>
            <option value="hiding">Hidings</option>
        </select> -->
        <?= $formFilter->render(); ?>
        <button type="submit" class="btn btn-primary">Go</button>
    </form>


</div>

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
                            <a href="attributes/edit/<?= $attribute->id ?>" class="btn btn-sm btn-primary">Edit</a>
                            <a href="attributes/delete/<?= $attribute->id ?>" class="btn btn-sm btn-danger">Delete</a>
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