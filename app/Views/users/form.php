<?php
/**
 * View: users/form.php
 * Shared by Users::newForm() and Users::edit().
 *
 * Variables:
 *   $pageTitle  — string  'New User' or 'Edit User'
 *   $formAction — string  POST target URL
 *   $user       — array|null  null on create, populated row on edit
 *   $errors     — array   field => message validation errors
 *   $old        — array   previously submitted values (on redirect back)
 */

$oldUsername = $old['username']  ?? ($user['username']  ?? '');
$oldFullName = $old['full_name'] ?? ($user['full_name'] ?? '');
$oldEmail    = $old['email']     ?? ($user['email']     ?? '');

$isEdit     = $user !== null;
$avatarFile = ($isEdit && ! empty($user['avatar']))
  ? base_url('uploads/avatars/' . $user['avatar'])
  : null;
?>
<?= view('templates/header', ['pageTitle' => $pageTitle]) ?>

<!-- Page heading -->
<div class="page-header">
  <div class="page-header-text">
    <h1><?= $isEdit ? 'Edit User' : 'New User' ?></h1>
    <p class="subtitle">
      <?= $isEdit
        ? 'Update details for @' . esc($user['username'])
        : 'Fill in the details below to create a new user.' ?>
    </p>
  </div>
  <a href="<?= base_url('/users') ?>" class="btn btn-secondary">Back to List</a>
</div>

<?php if (! empty($errors)): ?>
  <div class="validation-summary" role="alert" id="validation-summary">
    <strong>Please fix the following errors:</strong>
    <ul>
      <?php foreach ($errors as $field => $message): ?>
        <li><?= esc($message) ?></li>
      <?php endforeach; ?>
    </ul>
  </div>
<?php endif; ?>

<div class="form-card">
  <form
    id="user-form"
    action="<?= esc($formAction) ?>"
    method="post"
    enctype="multipart/form-data"
    novalidate
  >
    <?= csrf_field() ?>

    <!-- Username -->
    <div class="form-group">
      <label for="username">Username <span style="color:var(--danger)">*</span></label>
      <input
        type="text"
        id="username"
        name="username"
        class="form-control <?= isset($errors['username']) ? 'is-invalid' : '' ?>"
        value="<?= esc($oldUsername) ?>"
        placeholder="e.g. jsmith"
        maxlength="50"
        required
        autocomplete="username"
      />
      <div class="form-hint">Letters, numbers, underscores, and dashes only. Must be unique.</div>
      <?php if (isset($errors['username'])): ?>
        <div class="field-error" id="err-username"><?= esc($errors['username']) ?></div>
      <?php endif; ?>
    </div>

    <!-- Full Name -->
    <div class="form-group">
      <label for="full_name">Full Name <span style="color:var(--danger)">*</span></label>
      <input
        type="text"
        id="full_name"
        name="full_name"
        class="form-control <?= isset($errors['full_name']) ? 'is-invalid' : '' ?>"
        value="<?= esc($oldFullName) ?>"
        placeholder="e.g. Juan Smith"
        maxlength="100"
        required
        autocomplete="name"
      />
      <?php if (isset($errors['full_name'])): ?>
        <div class="field-error" id="err-full_name"><?= esc($errors['full_name']) ?></div>
      <?php endif; ?>
    </div>

    <!-- Email (optional) -->
    <div class="form-group">
      <label for="email">Email Address</label>
      <input
        type="email"
        id="email"
        name="email"
        class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>"
        value="<?= esc($oldEmail) ?>"
        placeholder="e.g. jsmith@example.com"
        maxlength="100"
        autocomplete="email"
      />
      <div class="form-hint">Optional.</div>
      <?php if (isset($errors['email'])): ?>
        <div class="field-error" id="err-email"><?= esc($errors['email']) ?></div>
      <?php endif; ?>
    </div>

    <?php if ($isEdit): ?>
    <!-- Avatar Upload (edit only) -->
    <div class="form-group">
      <label for="avatar">Profile Picture</label>

      <?php if ($avatarFile): ?>
        <div class="avatar-preview-wrap" id="current-avatar-wrap">
          <img src="<?= esc($avatarFile) ?>" alt="Current avatar" id="current-avatar-img" />
          <p class="avatar-preview-label">Current avatar — upload a new image to replace it.</p>
        </div>
      <?php else: ?>
        <p class="form-hint" style="margin-bottom:.5rem">No avatar uploaded yet.</p>
      <?php endif; ?>

      <input
        type="file"
        id="avatar"
        name="avatar"
        class="form-control <?= isset($errors['avatar']) ? 'is-invalid' : '' ?>"
        accept="image/jpeg,image/png"
      />
      <div class="form-hint">JPG or PNG only. Maximum 2 MB. Leave blank to keep the current image.</div>
      <?php if (isset($errors['avatar'])): ?>
        <div class="field-error" id="err-avatar"><?= esc($errors['avatar']) ?></div>
      <?php endif; ?>
    </div>
    <?php endif; ?>

    <!-- Submit -->
    <div style="display:flex;gap:.75rem;margin-top:1.75rem">
      <button type="submit" id="btn-save-user" class="btn btn-primary">
        <?= $isEdit ? 'Save Changes' : 'Add User' ?>
      </button>
      <a href="<?= base_url('/users') ?>" class="btn btn-secondary">Cancel</a>
    </div>
  </form>
</div>

<?= view('templates/footer') ?>
