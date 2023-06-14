<?php
if ($attribute) :
?>
    <table class="table">
        <tr>
            <th>Title</th>
            <th>Type</th>
            <th>Created at</th>
        </tr>
        <tr>
            <td><?= $attribute->title ?></td>
            <td><?= $attribute->type ?></td>
            <td><?= $attribute->createdAt ?></td>
        </tr>
    </table>
    <form action="" method="POST">
        <div class="p-5 text-center bg-light">
            <div class="mb-3">Are you sure you want to remove this attribute?</div>
            <button name="choice" type="submit" value="yes" class="btn btn-primary">Yes</button>
            <button name="choice" type="submit" value="no" class="btn btn-danger">No</button>
        </div>
    </form>

<?php else : ?>

    <div class="alert alert-success"><?= $message ?></div>

<?php endif; ?>