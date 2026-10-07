<?php
/**
 * View: customers/index.php
 * Route: GET /customers
 *
 * Lists all registered customers with Edit links and an Add New button.
 * Variables supplied by Customers::index():
 *   $pageTitle  — string
 *   $customers  — array   rows from customers table
 *   $flash      — string|null  success flash message
 */
?>
<?= view('templates/header', ['pageTitle' => $pageTitle]) ?>

<!-- Page heading -->
<div class="page-header">
  <div class="page-header-text">
    <h1>Customer Accounts</h1>
    <p class="subtitle">All registered customers — <?= count($customers) ?> total</p>
  </div>
  <a href="<?= base_url('/customers/new') ?>" id="btn-add-customer" class="btn btn-primary">Add New Customer</a>
</div>

<?php if ($flash): ?>
  <div class="flash flash-success" role="alert"><?= esc($flash) ?></div>
<?php endif; ?>
<?php if (session()->getFlashdata('error')): ?>
  <div class="flash flash-error" role="alert"><?= esc(session()->getFlashdata('error')) ?></div>
<?php endif; ?>

<?php if (empty($customers)): ?>
  <div class="empty-state">
    <div class="empty-icon-box">
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
    </div>
    <h2>No customers yet</h2>
    <p>Add your first customer using the button above.</p>
  </div>

<?php else: ?>
  <table class="data-table" id="customers-table">
    <thead>
      <tr>
        <th>#</th>
        <th>Full Name</th>
        <th>Email Address</th>
        <th>Phone</th>
        <th>Added</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($customers as $i => $c): ?>
      <tr>
        <td style="color:var(--muted);width:36px"><?= $i + 1 ?></td>
        <td><?= esc($c['full_name']) ?></td>
        <td style="color:var(--muted)"><?= esc($c['email']) ?></td>
        <td style="color:var(--muted)"><?= esc($c['phone'] ?? '—') ?></td>
        <td style="color:var(--muted);white-space:nowrap"><?= esc(substr($c['created_at'], 0, 10)) ?></td>
        <td class="actions-col">
          <a href="<?= base_url('/customers/' . $c['id'] . '/edit') ?>"
             id="btn-edit-customer-<?= $c['id'] ?>"
             class="btn btn-secondary btn-sm">Edit</a>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
<?php endif; ?>

<?= view('templates/footer') ?>
