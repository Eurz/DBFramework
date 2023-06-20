<?php
if ($user) :
?>
    <form action="" method="POST">
        <table class="table">
            <tr>
                <th>Name</th>
                <th>Type</th>
            </tr>
            <tr>
                <td><?= $user->fullName ?></td>
                <td><?= $user->userType ?></td>
            </tr>
        </table>
        <div class="p-5 text-center bg-light">
            <div class="mb-3">Are you sure you want to remove this user:</div>
            <h2 class="mb-3"><?= $user->fullName ?></h2>
            <button name="choice" type="submit" value="yes" class="btn btn-primary">Yes</button>
            <button name="choice" type="submit" value="no" class="btn btn-danger">No</button>
        </div>
    </form>

<?php else : ?>

    <div class="alert alert-success"><?= $message ?></div>

<?php endif; ?>