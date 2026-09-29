<?php
/**
 * View: about/index.php
 * Route: GET /about
 *
 * Static page — no model or controller data required.
 * Identifies Brent Verdera as the developer.
 */
?>
<?= view('templates/header', ['pageTitle' => $pageTitle]) ?>

<!-- Page heading -->
<div class="page-header">
  <h1>About the Developer</h1>
  <p class="subtitle">IT0049 Technical Summative Assessment 1 — Tasks for Today Management System</p>
</div>

<div class="about-card">
  <h2>Developer Information</h2>
  <p><strong style="color:var(--text)">Name:</strong> &nbsp; Brent Verdera</p>
  <p><strong style="color:var(--text)">Course &amp; Subject:</strong> &nbsp; IT0049 — Information Technology</p>
  <p><strong style="color:var(--text)">Activity:</strong> &nbsp; Technical Summative Assessment 1</p>
  <p><strong style="color:var(--text)">Framework:</strong> &nbsp; CodeIgniter 4 (MVC)</p>
</div>

<div class="about-card">
  <h2>Project Overview</h2>
  <p>
    <strong style="color:var(--text)">Tasks for Today</strong> is a task management system that
    displays daily tasks filtered by the current date in the Asia/Manila timezone.
    Built following the CodeIgniter 4 MVC pattern:
  </p>
  <ul style="margin-top:.75rem">
    <li><strong style="color:var(--text)">Models</strong> — all database queries (TaskModel, UserModel)</li>
    <li><strong style="color:var(--text)">Controllers</strong> — page logic and data passing (Home, Tasks, Profile, About)</li>
    <li><strong style="color:var(--text)">Views</strong> — HTML display with shared header/footer templates</li>
  </ul>
</div>

<div class="about-card">
  <h2>Available Routes</h2>
  <ul>
    <li><strong style="color:var(--text)">/ &nbsp;</strong> — Welcome page: tasks whose <code style="color:var(--accent2)">task_date</code> = today</li>
    <li><strong style="color:var(--text)">/tasks &nbsp;</strong> — Full task list ordered by date ascending</li>
    <li><strong style="color:var(--text)">/profile &nbsp;</strong> — Demo user profile from the database</li>
    <li><strong style="color:var(--text)">/about &nbsp;</strong> — This page</li>
  </ul>
</div>

<div class="about-card">
  <h2>Tech Stack</h2>
  <ul>
    <li>PHP 8.1+ / CodeIgniter 4</li>
    <li>MySQL 5.7+ via XAMPP</li>
    <li>Vanilla HTML5 &amp; CSS3 (dark mode, glassmorphism)</li>
    <li>Google Fonts — Inter</li>
  </ul>
</div>

<?= view('templates/footer') ?>
