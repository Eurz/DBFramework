<?php
if ($mission) :
?>
    <form action="" method="POST">
        <div class="p-5 text-center bg-light">
            <div class="mb-3">Are you sure you want to remove this mission?</div>
            <h2 class="mb-3"><?= $mission->title ?></h2>
            <button name="choice" type="submit" value="yes" class="btn btn-primary">Yes</button>
            <button name="choice" type="submit" value="no" class="btn btn-danger">No</button>
        </div>


        <?php include('view.html.php'); ?>

    </form>

<?php else : ?>

    <div class="alert alert-success"><?= $message ?></div>

<?php endif; ?>