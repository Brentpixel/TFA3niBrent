<?= view('templates/header', ['title' => $title]) ?>

<h1>User Accounts</h1>
<p>List of all system users and staff.</p>
<p>Total records: <?= count($users) ?></p>

<table border="1" cellpadding="6" cellspacing="0">
    <thead>
        <tr>
            <th>Username</th>
            <th>Full Name</th>
            <th>Role</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $user): ?>
        <tr>
            <!-- username -->
            <td><?= esc($user['username']) ?></td>
            <!-- full_name -->
            <td><?= esc($user['full_name']) ?></td>
            <!-- role -->
            <td><?= esc($user['role']) ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?= view('templates/footer') ?>
