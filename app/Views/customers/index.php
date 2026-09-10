<?= view('templates/header', ['title' => $title]) ?>

<h1>Customer Accounts</h1>
<p>List of all registered customers.</p>
<p>Total records: <?= count($customers) ?></p>

<table border="1" cellpadding="6" cellspacing="0">
    <thead>
        <tr>
            <th>Full Name</th>
            <th>Email Address</th>
            <th>Phone Number</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($customers as $customer): ?>
        <tr>
            <!-- full_name -->
            <td><?= esc($customer['full_name']) ?></td>
            <!-- email -->
            <td><?= esc($customer['email']) ?></td>
            <!-- phone -->
            <td><?= esc($customer['phone']) ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?= view('templates/footer') ?>
