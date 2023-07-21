    <div class="p-3 mb-3 bg-light"><a href="/missions">Back to list</a></div>

    <table class="table">
        <tr>
            <th>Type</th>
            <td><?= $mission->missionType ?></td>
        </tr>
        <tr>
            <th>CodeName</th>
            <td><?= $mission->codeName ?></td>
        </tr>

        <tr>
            <th>Description</th>
            <td><?= $mission->description ?></td>
        </tr>
        <tr>
            <th>Status</th>
            <td><?= $mission->status ?></td>
        </tr>
        <tr>
            <th>Country</th>
            <td><?= $mission->country ?></td>
        </tr>

        <tr>
            <th>Hiding</th>
            <td><?= $mission->hiding->code ?> ( <?= $mission->hiding->country ?> )</td>
        </tr>
        <tr>
            <th>Required speciality</th>
            <td><?= $mission->speciality ?></td>
        </tr>
        <tr>
            <th>Start</th>
            <td><?= $mission->startDate ?></td>
        </tr>
        <tr>
            <th>End</th>
            <td><?= $mission->endDate ?></td>
        </tr>

        <tr>
            <th>Contacts</th>
            <td>
                <?php if ($mission->getContacts()) : ?>

                    <?php
                    foreach ($mission->getContacts() as $contact) : ?>
                        <div><?= $contact->fullName ?> ( <?= $contact->nationality ?> )</div>

                    <?php endforeach ?>
                <?php else : ?>

                    ---
                <?php endif ?>

            </td>
        </tr>
        <tr>
            <th>Agents</th>
            <td>
                <?php if ($mission->getAgents()) : ?>
                    <?php
                    foreach ($mission->getAgents() as $agent) : ?>
                        <div><?= $agent->fullName ?> ( <?= $agent->nationality ?> )</div>

                    <?php endforeach ?>

                <?php else : ?>

                    ---
                <?php endif ?>
            </td>
        </tr>
        <tr>
            <th>Targets</th>
            <td> <?php if ($mission->getTargets()) : ?>

                    <?php
                        foreach ($mission->getTargets() as $target) : ?>
                        <div><?= $target->fullName ?> ( <?= $target->nationality ?> )</div>

                    <?php endforeach ?>
                <?php else : ?>

                    ---
                <?php endif ?>
            </td>
        </tr>

    </table>
    <div>
        <?php if ($auth->grantedAccess('ROLE_ADMIN')) : ?>
            <a href="/missions/edit/<?= $mission->id ?>" class="btn btn-primary">Edit</a>
            <a href="/missions/delete/<?= $mission->id ?>" class="btn btn-danger">Delete</a>
        <?php endif ?>
    </div>