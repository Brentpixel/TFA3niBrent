<?= view('templates/header', ['title' => $title]) ?>

<h1>Welcome to POS System</h1>


<h2>Quick Links</h2>
<ul>
    <li><a href="<?= base_url('/customers') ?>">Customer Accounts</a></li>
    <li><a href="<?= base_url('/users') ?>">User Accounts</a></li>
    <li><a href="<?= base_url('/about') ?>">About This App</a></li>
</ul>

<h2>System Information</h2>
<table border="1" cellpadding="6" cellspacing="0">
    <tr>
        <td>Framework</td>
        <td>CodeIgniter 4</td>
    </tr>
    <tr>
        <td>Language</td>
        <td>PHP 8.2</td>
    </tr>
    <tr>
        <td>Database</td>
        <td>None (Static PHP Arrays)</td>
    </tr>
    <tr>
        <td>Architecture</td>
        <td>MVC (Model-View-Controller)</td>
    </tr>

</table>

<?= view('templates/footer') ?>
