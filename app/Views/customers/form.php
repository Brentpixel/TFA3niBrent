<?php
/**
 * View: customers/form.php
 * Shared by Customers::newForm() and Customers::edit().
 *
 * Variables:
 *   $pageTitle  — string  'New Customer' or 'Edit Customer'
 *   $formAction — string  POST target URL
 *   $customer   — array|null  null on create, populated row on edit
 *   $errors     — array   field => message validation errors
 *   $old        — array   previously submitted values (on redirect back)
 */

// Resolve display values: prefer old (on re-show after error), then existing record, then blank
$oldFullName = $old['full_name'] ?? ($customer['full_name'] ?? '');
$oldEmail    = $old['email']    ?? ($customer['email']    ?? '');
$oldPhone    = $old['phone']    ?? ($customer['phone']    ?? '');

$isEdit = $customer !== null;
?>
<?= view('templates/header', ['pageTitle' => $pageTitle]) ?>

<!-- Page heading -->
<div class="page-header">
  <div class="page-header-text">
    <h1><?= $isEdit ? 'Edit Customer' : 'New Customer' ?></h1>
    <p class="subtitle">
      <?= $isEdit
        ? 'Update details for ' . esc($customer['full_name'])
        : 'Fill in the details below to register a new customer.' ?>
    </p>
  </div>
  <a href="<?= base_url('/customers') ?>" class="btn btn-secondary">Back to List</a>
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
  <form id="customer-form" action="<?= esc($formAction) ?>" method="post" novalidate>
    <?= csrf_field() ?>

    <!-- Full Name -->
    <div class="form-group">
      <label for="full_name">Full Name <span style="color:var(--danger)">*</span></label>
      <input
        type="text"
        id="full_name"
        name="full_name"
        class="form-control <?= isset($errors['full_name']) ? 'is-invalid' : '' ?>"
        value="<?= esc($oldFullName) ?>"
        placeholder="e.g. Maria Santos"
        maxlength="100"
        required
        autocomplete="name"
      />
      <?php if (isset($errors['full_name'])): ?>
        <div class="field-error" id="err-full_name"><?= esc($errors['full_name']) ?></div>
      <?php endif; ?>
    </div>

    <!-- Email -->
    <div class="form-group">
      <label for="email">Email Address <span style="color:var(--danger)">*</span></label>
      <input
        type="email"
        id="email"
        name="email"
        class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>"
        value="<?= esc($oldEmail) ?>"
        placeholder="e.g. maria@example.com"
        maxlength="100"
        required
        autocomplete="email"
      />
      <?php if (isset($errors['email'])): ?>
        <div class="field-error" id="err-email"><?= esc($errors['email']) ?></div>
      <?php endif; ?>
    </div>

    <!-- Phone (optional) -->
    <div class="form-group">
      <label for="phone">Phone Number</label>
      <input
        type="text"
        id="phone"
        name="phone"
        class="form-control <?= isset($errors['phone']) ? 'is-invalid' : '' ?>"
        value="<?= esc($oldPhone) ?>"
        placeholder="e.g. 09171234567"
        maxlength="20"
        autocomplete="tel"
      />
      <div class="form-hint">Optional. Max 20 characters.</div>
      <?php if (isset($errors['phone'])): ?>
        <div class="field-error" id="err-phone"><?= esc($errors['phone']) ?></div>
      <?php endif; ?>
    </div>

    <!-- Submit -->
    <div style="display:flex;gap:.75rem;margin-top:1.75rem">
      <button type="submit" id="btn-save-customer" class="btn btn-primary">
        <?= $isEdit ? 'Save Changes' : 'Add Customer' ?>
      </button>
      <a href="<?= base_url('/customers') ?>" class="btn btn-secondary">Cancel</a>
    </div>
  </form>
</div>

<?= view('templates/footer') ?>
