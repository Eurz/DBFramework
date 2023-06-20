<div class="d-flex justify-content-between">
    <div class="col bg-light p-3">
        <a href="/missions/add" class="btn btn-primary btn-sm">Create a mission</a>
    </div>

</div>
<?php
// var_dump($missions);
?>
<?php if ($missions) : ?>
    <table class="table">
        <tr>
            <!-- <th>Title</th> -->
            <th>Code name</th>
            <th>Status</th>
            <th>Start</th>
            <th>End</th>
            <!--
            <th>Country</th>
            <th>Type</th>
            <th>Required</th> -->
            <th>Actions</th>
        </tr>
        <?php foreach ($missions as $mission) : ?>
            <tr>
                <!-- <td><?= $mission->title ?></td> -->
                <td><?= $mission->codeName ?></td>
                <td><?= $mission->status ?></td>
                <td><?= $mission->startDate ?></td>
                <td><?= $mission->endDate ?></td>
                <!--
                <td><?= $mission->country ?></td>
                <td><?= $mission->type ?></td>
                <td><?= $mission->speciality ?></td> -->
                <td class="text-end">
                    <a href="/missions/edit/<?= $mission->id ?>" class="btn btn-primary btn-sm">Edit</a>
                    <a href="/missions/delete/<?= $mission->id ?>" class="btn btn-danger btn-sm">Delete</a>
                </td>
            </tr>

        <?php endforeach ?>
        </tr>
    </table>

<?php else : ?>
    <div class="alert alert-success">No missions</div>
<?php endif ?>