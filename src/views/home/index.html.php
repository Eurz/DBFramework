<?php if (isset($form)) : ?>
    <p>
        Your application is not installed.
    </p>
    <p>
        We are going to install it when you'll click on the button.
    </p>

    <form action="" method="POST">
        <?= $form->render(); ?>
        <button class="btn btn-primary">Install</button>
    </form>

<?php endif ?>