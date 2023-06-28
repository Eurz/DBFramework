    <div class="p-3 mb-3 bg-light"><a href="/missions">Back to list</a></div>

    <table class="table">
        <tr>
            <th>Type</th>
            <td><?= $mission->missionType ?></td>
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
            <th>CodeName</th>
            <td><?= $mission->codeName ?></td>
        </tr>
        <tr>
            <th>Hiding</th>
            <td><?= $mission->hiding ?></td>
        </tr>
        <tr>
            <th>Country</th>
            <td><?= $mission->country ?></td>
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
    </table>
    <div>
        <a href="/missions/edit/<?= $mission->id ?>" class="btn btn-primary">Edit mission</a>
        <a href="/missions/delete/<?= $mission->id ?>" class="btn btn-danger">Delete mission</a>
    </div>