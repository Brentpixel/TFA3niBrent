<?= view('templates/header', ['title' => $title]) ?>

<h1>About This Application</h1>


<h2>Laboratory Activity</h2>
<p>
  

You will build the first version of a basic Point-of-Sale (POS) system: a four-page CodeIgniter website. The Customer Accounts and User Accounts pages will use a static PHP array as a temporary data source — no database is involved yet. 

Required pages: a landing page (/), an about page (/about), a Customer Accounts page (/customers) listing customer records (full name, email, phone) from a static array, and a User Accounts page (/users) listing user/staff records (username, full name, role) from a static array. 
</p>


<h2>How MVC Works in This Assignment</h2>
<ul>
    <li><strong>Routes</strong> — Defined in <code>app/Config/Routes.php</code>. They decide which controller runs when you visit a URL.</li>
    <li><strong>Controllers</strong> — In <code>app/Controllers/</code>. They handle what happens and prepare data, call views.</li>
    <li><strong>Views</strong> — In <code>app/Views/</code>. They display the HTML page using the data sent from the controller.</li>
</ul>

<h2>Available Routes</h2>
<table border="1" cellpadding="6" cellspacing="0">
    <thead>
        <tr>
            <th>URL</th>
            <th>Controller</th>
            <th>Method</th>
            <th>Description</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>/</td>
            <td>Pages</td>
            <td>index()</td>
            <td>Landing / Home Page</td>
        </tr>
        <tr>
            <td>/about</td>
            <td>Pages</td>
            <td>about()</td>
            <td>About This Application</td>
        </tr>
        <tr>
            <td>/customers</td>
            <td>Customers</td>
            <td>index()</td>
            <td>Customer Accounts List</td>
        </tr>
        <tr>
            <td>/users</td>
            <td>Users</td>
            <td>index()</td>
            <td>User / Staff Accounts List</td>
        </tr>
    </tbody>
</table>

<?= view('templates/footer') ?>
