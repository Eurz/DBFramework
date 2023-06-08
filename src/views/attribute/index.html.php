<div class="d-flex justify-content-between">
    <div class="col bg-light p-3">
        <a href="attributes/add" class="btn btn-primary btn-sm">Add an attribute</a>
        <!-- <a href="index.php?controller=attributes&task=add&type=country" class="btn btn-primary btn-sm">Add <?= $types['country'] ?></a>
        <a href="index.php?controller=attributes&task=add&type=nationality" class="btn btn-primary btn-sm">Add <?= $types['nationality'] ?></a>
        <a href="index.php?controller=attributes&task=add&type=status" class="btn btn-primary btn-sm">Add <?= $types['status'] ?></a>
        <a href="index.php?controller=attributes&task=add&type=hiding" class="btn btn-primary btn-sm">Add <?= $types['hiding'] ?></a>
        <a href="index.php?controller=attributes&task=add&type=speciality" class="btn btn-primary btn-sm">Add <?= $types['speciality'] ?></a>
        <a href="index.php?controller=attributes&task=add&type=type" class="btn btn-primary btn-sm">Add <?= $types['type'] ?></a>
        <a href="index.php?controller=attributes&task=add&type=userType" class="btn btn-primary btn-sm">Add <?= $types['userType'] ?></a> -->
    </div>

</div>

<div>

    <table class="table table-hover">
        <thead>
            <tr>
                <th>Title</th>
                <th scope="col">Created at</th>
                <th scope="col"></th>
                <th scope="col"></th>
            </tr>
        </thead>
        <tbody>

            <?php
            if ($attributes) {
                $previousType = '';
                foreach ($attributes as $attribute) {
                    if ($previousType !== $attribute->type) {
                        echo '<tr><th colspan="4" class="table-light">' . $types[$attribute->type] . '</th></tr>';
                    }
                    echo '<tr>';
                    echo '<td>' . $attribute->title . '</td>';
                    echo '<td>' . $attribute->createdAt . '</td>';
                    echo '<td><a href="attributes/edit/' . $attribute->id . '" class="btn btn-sm btn-primary">Edit</a></td>';
                    echo '<td><a href="attributes/delete/' . $attribute->id . '" class="btn btn-sm btn-danger">Delete</a></td>';
                    echo '</tr>';
                    $previousType = $attribute->type;
                }
            } else {
                echo '<tr><td colspan="4">There is no defined attributes</td></tr>';
            }
            ?>
        </tbody>
    </table>
</div>