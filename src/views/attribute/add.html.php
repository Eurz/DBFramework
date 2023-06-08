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
    <?php
    $first = array_key_first($types);

    foreach ($types as $type => $name) : ?>
        <div class="form-check">
            <input class="form-check-input" type="radio" name="type" id="type" value="<?= $type ?>" <?= $data['type'] === $type ? 'checked' : null; ?> />
            <label class="form-check-label" for="type">
                <?= $name ?>
            </label>
        </div>
    <?php endforeach; ?>
    <div class="mb-3">
        <label for="title" class="form-label">Title</label>
        <input class="form-control" type="text" name="title" id="title" value="<?= $data['title'] ?>" />
    </div>
    <button type="submit" class="btn btn-primary">Save</button>
</form>