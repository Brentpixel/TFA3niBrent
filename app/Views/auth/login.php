<?php
/**
 * View: auth/login.php
 * Route: GET /login
 *
 * Public login form for TFA4 Sessions & Authentication.
 *
 * Variables supplied by Auth::login():
 *   $pageTitle — string
 *   $errors    — array   validation errors
 *   $old       — array   old input (username only)
 *   $flash     — string|null  success flash message (e.g., after logout)
 *   $error     — string|null  general auth error (e.g., invalid credentials)
 */

$oldUsername = $old['username'] ?? '';
?>
<?= view('templates/header', ['pageTitle' => $pageTitle]) ?>

<div style="max-width: 440px; margin: 2rem auto 4rem;">
  <!-- Header -->
  <div class="page-header" style="justify-content: center; text-align: center; border-bottom: none; margin-bottom: 1.5rem;">
    <div class="page-header-text">
      <h1>Sign In</h1>
      <p class="subtitle">Access POS management and user administration</p>
    </div>
  </div>

  <?php if ($flash): ?>
    <div class="flash flash-success" role="alert" id="flash-success">
      <?= esc($flash) ?>
    </div>
  <?php endif; ?>

  <?php if ($error): ?>
    <div class="flash flash-error" role="alert" id="flash-error">
      <?= esc($error) ?>
    </div>
  <?php endif; ?>

  <?php if (! empty($errors)): ?>
    <div class="validation-summary" role="alert" id="validation-summary">
      <strong>Please fix the following:</strong>
      <ul>
        <?php foreach ($errors as $msg): ?>
          <li><?= esc($msg) ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>

  <div class="form-card" style="max-width: 100%;">
    <form action="<?= base_url('/login') ?>" method="post" id="login-form" novalidate>
      <?= csrf_field() ?>

      <!-- Username / Email -->
      <div class="form-group">
        <label for="username">Username or Email <span style="color:var(--danger)">*</span></label>
        <input
          type="text"
          id="username"
          name="username"
          class="form-control <?= isset($errors['username']) ? 'is-invalid' : '' ?>"
          value="<?= esc($oldUsername) ?>"
          placeholder="e.g. bverdera or brentverdera02@gmail.com"
          maxlength="50"
          required
          autofocus
          autocomplete="username"
        />
        <?php if (isset($errors['username'])): ?>
          <div class="field-error" id="err-username"><?= esc($errors['username']) ?></div>
        <?php endif; ?>
      </div>

      <!-- Password -->
      <div class="form-group">
        <label for="password">Password <span style="color:var(--danger)">*</span></label>
        <input
          type="password"
          id="password"
          name="password"
          class="form-control <?= isset($errors['password']) ? 'is-invalid' : '' ?>"
          placeholder="Enter your password"
          required
          autocomplete="current-password"
        />
        <?php if (isset($errors['password'])): ?>
          <div class="field-error" id="err-password"><?= esc($errors['password']) ?></div>
        <?php endif; ?>
      </div>

      <!-- Submit button -->
      <div style="margin-top: 1.75rem;">
        <button type="submit" id="btn-login" class="btn btn-primary" style="width: 100%; justify-content: center;">
          Sign In
        </button>
      </div>
    </form>
  </div>
</div>

<?= view('templates/footer') ?>
