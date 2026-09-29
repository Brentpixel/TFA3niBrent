<?php
/**
 * View: users/index.php
 * Route: GET /users
 *
 * Lists all users. Shows avatar thumbnail or initials placeholder.
 * Variables supplied by Users::index():
 *   $pageTitle  — string
 *   $users      — array   rows from users table (includes avatar column)
 *   $flash      — string|null  success flash message
 */
?>
<?= view('templates/header', ['pageTitle' => $pageTitle]) ?>

<!-- Page heading -->
<div class="page-header">
  <div class="page-header-text">
    <h1>User Accounts</h1>
    <p class="subtitle">All system users and staff — <?= count($users) ?> total</p>
  </div>
  <a href="<?= base_url('/users/new') ?>" id="btn-add-user" class="btn btn-primary">Add New User</a>
</div>

<?php if ($flash): ?>
  <div class="flash flash-success" role="alert"><?= esc($flash) ?></div>
<?php endif; ?>

<?php if (empty($users)): ?>
  <div class="empty-state">
    <div class="empty-icon-box">
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
    </div>
    <h2>No users yet</h2>
    <p>Add your first user using the button above.</p>
  </div>

<?php else: ?>
  <table class="data-table" id="users-table">
    <thead>
      <tr>
        <th>#</th>
        <th>Avatar</th>
        <th>Username</th>
        <th>Full Name</th>
        <th>Email</th>
        <th>Added</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($users as $i => $u): ?>
      <?php
        // Derive initials for placeholder
        $nameParts = explode(' ', trim($u['full_name']));
        $initials  = strtoupper(substr($nameParts[0], 0, 1) . (isset($nameParts[1]) ? substr($nameParts[1], 0, 1) : ''));
        $avatarFile = ! empty($u['avatar'])
          ? base_url('uploads/avatars/' . $u['avatar'])
          : null;
      ?>
      <tr>
        <td style="color:var(--muted);width:36px"><?= $i + 1 ?></td>
        <td>
          <?php if ($avatarFile): ?>
            <img
              src="<?= esc($avatarFile) ?>"
              alt="Avatar of <?= esc($u['full_name']) ?>"
              class="avatar-thumb"
              id="avatar-user-<?= $u['id'] ?>"
            />
          <?php else: ?>
            <div class="avatar-placeholder" id="avatar-placeholder-<?= $u['id'] ?>" title="No avatar uploaded">
              <?= esc($initials) ?>
            </div>
          <?php endif; ?>
        </td>
        <td><code style="font-size:.83rem;color:var(--accent2)"><?= esc($u['username']) ?></code></td>
        <td><?= esc($u['full_name']) ?></td>
        <td style="color:var(--muted)"><?= esc($u['email'] ?? '—') ?></td>
        <td style="color:var(--muted);white-space:nowrap"><?= esc(substr($u['created_at'], 0, 10)) ?></td>
        <td class="actions-col">
          <a href="<?= base_url('/users/' . $u['id'] . '/edit') ?>"
             id="btn-edit-user-<?= $u['id'] ?>"
             class="btn btn-secondary btn-sm">Edit</a>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
<?php endif; ?>

<?= view('templates/footer') ?>
