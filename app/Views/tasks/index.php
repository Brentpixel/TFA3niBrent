<?php
/**
 * View: tasks/index.php
 * Route: GET /tasks
 *
 * Displays ALL tasks ordered by task_date ascending.
 * Variables supplied by Tasks::index():
 *   $tasks     — array   all rows from tasks table
 *   $pageTitle — string
 */
?>
<?= view('templates/header', ['pageTitle' => $pageTitle]) ?>

<!-- Page heading -->
<div class="page-header">
  <h1>All Tasks</h1>
  <p class="subtitle">Every task in the system, ordered by date ascending.</p>
</div>

<?php if (empty($tasks)): ?>
  <div class="empty-state">
    <div class="empty-icon-box">
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path></svg>
    </div>
    <h2>No tasks found</h2>
    <p>Run the seeder: <code>php spark db:seed DatabaseSeeder</code></p>
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
      <div class="chip-label">Total Tasks</div>
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

  <table class="task-table">
    <thead>
      <tr>
        <th>#</th>
        <th>Title</th>
        <th>Status</th>
        <th>Date</th>
        <th>Created At</th>
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
          <td style="white-space:nowrap"><?= esc($task['task_date']) ?></td>
          <td style="color:var(--muted);font-size:.82rem;white-space:nowrap"><?= esc($task['created_at']) ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
<?php endif; ?>

<?= view('templates/footer') ?>
