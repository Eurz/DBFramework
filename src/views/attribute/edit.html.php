<form action="" method="post">

    <?php
    if ($message) {
    ?>
        <div class="alert alert-success">
            <?= $message ?>
        </div>
    <?php
    }
    ?>

    <div class="mb-3">
        <label for="title" class="form-label">Title</label>
        <input class="form-control" type="text" name="title" id="title" value="<?= $attribute->title ?>" />
    </div>
    <button type="submit" class="btn btn-primary">Save</button>
</form>