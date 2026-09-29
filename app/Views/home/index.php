<?php
/**
 * View: home/index.php
 * Route: GET /
 *
 * Displays tasks whose task_date = today (Asia/Manila).
 * Variables supplied by Home::index():
 *   $today      — string  'Y-m-d'
 *   $todayLabel — string  'F j, Y'
 *   $tasks      — array   rows from tasks table (may be empty)
 *   $pageTitle  — string
 */
?>
<?= view('templates/header', ['pageTitle' => $pageTitle]) ?>

<!-- Page heading -->
<div class="page-header">
  <h1>Tasks for Today</h1>
  <p class="subtitle">Showing tasks scheduled for <strong><?= esc($todayLabel) ?></strong> (Asia/Manila)</p>
</div>

<?php if (empty($tasks)): ?>
  <!-- Empty state — shown when no tasks match today's date -->
  <div class="empty-state">
    <div class="empty-icon-box">
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
    </div>
    <h2>No tasks scheduled for today</h2>
    <p>There are no tasks found for this specific date in the database.</p>
  </div>

<?php else: ?>
  <!-- Summary chips -->
  <?php
    $pending    = count(array_filter($tasks, fn($t) => $t['status'] === 'pending'));
    $inProgress = count(array_filter($tasks, fn($t) => $t['status'] === 'in-progress'));
    $done       = count(array_filter($tasks, fn($t) => $t['status'] === 'done'));
  ?>
  <div class="summary-row">
    <div class="chip">
      <div class="chip-label">Total</div>
      <div class="chip-value" style="color:var(--accent2)"><?= count($tasks) ?></div>
    </div>
    <div class="chip">
      <div class="chip-label">Pending</div>
      <div class="chip-value" style="color:var(--warning)"><?= $pending ?></div>
    </div>
    <div class="chip">
      <div class="chip-label">In Progress</div>
      <div class="chip-value" style="color:var(--info)"><?= $inProgress ?></div>
    </div>
    <div class="chip">
      <div class="chip-label">Done</div>
      <div class="chip-value" style="color:var(--success)"><?= $done ?></div>
    </div>
  </div>

  <!-- Task table -->
  <table class="task-table">
    <thead>
      <tr>
        <th>#</th>
        <th>Title</th>
        <th>Status</th>
        <th>Date</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($tasks as $i => $task): ?>
        <?php
          $badgeClass = match($task['status']) {
            'done'        => 'badge-done',
            'in-progress' => 'badge-in-progress',
            default       => 'badge-pending',
          };
        ?>
        <tr>
          <td style="color:var(--muted);width:40px"><?= $i + 1 ?></td>
          <td><?= esc($task['title']) ?></td>
          <td><span class="badge <?= $badgeClass ?>"><?= esc($task['status']) ?></span></td>
          <td style="color:var(--muted);white-space:nowrap"><?= esc($task['task_date']) ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
<?php endif; ?>

<?= view('templates/footer') ?>
