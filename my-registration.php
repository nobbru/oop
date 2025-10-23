<?php
require_once 'includes/auth.php';
require_login();

$pageTitle = 'Minha Inscrição - UP Maputo';
$user = new User();
$db = DB::getInstance();

// fetch registration
$db->query('SELECT r.*, f.name AS faculty_name, c.name AS course_name FROM registrations r JOIN faculties f ON f.id = r.faculty_id JOIN courses c ON c.id = r.course_id WHERE r.user_id = ? LIMIT 1', [$user->data()->id]);
$registration = $db->count() ? $db->first() : null;

// fetch subjects for course
$subjects = [];
if ($registration) {
    $db->query('SELECT s.id, s.name FROM course_subjects cs JOIN subjects s ON s.id = cs.subject_id WHERE cs.course_id = ? ORDER BY s.name', [$registration->course_id]);
    $subjects = $db->results();
}

include 'includes/header.php';
?>

<div class="max-w-2xl mx-auto">
  <h1 class="text-2xl font-semibold text-slate-800 mb-6">Minha Inscrição</h1>
  <?php if (!$registration): ?>
    <div class="rounded-md border border-slate-200 p-4 text-slate-600">Ainda não possui inscrição. <a class="text-blue-700 hover:underline" href="/apply.php">Fazer inscrição</a></div>
  <?php else: ?>
    <div class="rounded-md border border-slate-200 p-4">
      <div class="grid md:grid-cols-2 gap-4">
        <div>
          <div class="text-xs uppercase text-slate-500">Faculdade</div>
          <div class="font-medium text-slate-800"><?php echo escape($registration->faculty_name); ?></div>
        </div>
        <div>
          <div class="text-xs uppercase text-slate-500">Curso</div>
          <div class="font-medium text-slate-800"><?php echo escape($registration->course_name); ?></div>
        </div>
      </div>
      <div class="mt-6">
        <div class="text-xs uppercase text-slate-500 mb-2">Disciplinas de avaliação</div>
        <?php if (!$subjects): ?>
          <div class="text-slate-600">Ainda não foram associadas disciplinas a este curso.</div>
        <?php else: ?>
          <ul class="list-disc pl-5 space-y-1 text-slate-800">
            <?php foreach ($subjects as $s): ?>
              <li><?php echo escape($s->name); ?></li>
            <?php endforeach; ?>
          </ul>
        <?php endif; ?>
      </div>
    </div>
  <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
