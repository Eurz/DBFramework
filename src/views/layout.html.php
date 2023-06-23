<?php require(VIEW_PATH . '/header.html.php'); ?>

<div class="container">

    <!-- Messages flash -->
    <?= $messages ?>

    <!-- Title -->
    <?php include_once(VIEW_PATH . '/title.html.php'); ?>

    <!-- Content -->
    <?php
    echo $pageContent;
    ?>

</div>
<?php require(VIEW_PATH . '/footer.html.php'); ?>