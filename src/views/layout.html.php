<?php require(VIEW_PATH . '/header.html.php'); ?>

<div class="container">
    <?php include_once(VIEW_PATH . '/title.html.php'); ?>
    <?php
    echo $pageContent;
    ?>

</div>
<?php require(VIEW_PATH . '/footer.html.php'); ?>