<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
</button>
<div class="collapse navbar-collapse" id="navbarNav">
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" aria-current="page" href="/missions">Missions</a>
        </li>

        <?php if ($auth->grantedAccess('ROLE_ADMIN')) : ?>
            <li class="nav-item">
                <a class="nav-link" href="/users">Users</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/hidings">Hidings</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/attributes">Attributes</a>
            </li>
        <?php endif; ?>

        <li class="nav-item">
            <a class="nav-link" href="/logout">Logout</a>
        </li>

    </ul>
</div>