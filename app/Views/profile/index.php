<?php
/**
 * View: profile/index.php
 * Route: GET /profile
 *
 * Displays the single demo user record.
 * Variables supplied by Profile::index():
 *   $user      — array|null  single user row
 *   $pageTitle — string
 */
?>
<?= view('templates/header', ['pageTitle' => $pageTitle]) ?>

<!-- Page heading -->
<div class="page-header">
  <h1>Developer Profile</h1>
  <p class="subtitle">Viewing the demo user account stored in the database.</p>
</div>

<?php if (empty($user)): ?>
  <div class="empty-state">
    <div class="empty-icon-box">
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
    </div>
    <h2>No user found</h2>
    <p>Run the seeder to create the demo user:</p>
    <code>php spark db:seed UserSeeder</code>
  </div>

<?php else: ?>
  <?php
    // Build avatar initials from full_name
    $parts    = explode(' ', $user['full_name']);
    $initials = strtoupper(substr($parts[0], 0, 1) . (isset($parts[1]) ? substr($parts[1], 0, 1) : ''));
  ?>

  <div class="profile-card">
    <div class="profile-avatar"><?= esc($initials) ?></div>
    <div class="profile-info">
      <h2><?= esc($user['full_name']) ?></h2>
      <ul class="profile-meta">
        <li><strong>Username:</strong> @<?= esc($user['username']) ?></li>
        <li><strong>Email:</strong> <?= esc($user['email']) ?></li>
        <li><strong>Member since:</strong> <?= esc(date('F j, Y', strtotime($user['created_at']))) ?></li>
        <li><strong>User ID:</strong> #<?= esc($user['id']) ?></li>
      </ul>
    </div>
  </div>
<?php endif; ?>

<?= view('templates/footer') ?>
