<?php
require_once __DIR__ . '/../includes/auth.php';
require_admin();

$pageTitle = 'Admin - UP Maputo';
$db = DB::getInstance();

$stats = [
  'users' => 0,
  'registrations' => 0,
  'faculties' => 0,
  'courses' => 0,
  'subjects' => 0,
];

$db->query('SELECT COUNT(*) AS n FROM users');
$stats['users'] = (int) $db->first()->n;
$db->query('SELECT COUNT(*) AS n FROM registrations');
$stats['registrations'] = (int) $db->first()->n;
$db->query('SELECT COUNT(*) AS n FROM faculties');
$stats['faculties'] = (int) $db->first()->n;
$db->query('SELECT COUNT(*) AS n FROM courses');
$stats['courses'] = (int) $db->first()->n;
$db->query('SELECT COUNT(*) AS n FROM subjects');
$stats['subjects'] = (int) $db->first()->n;

include __DIR__ . '/../includes/header.php';
?>

<div class="space-y-6">
  <h1 class="text-2xl font-semibold text-slate-800">Painel do Administrador</h1>

  <div class="grid md:grid-cols-5 gap-4">
    <div class="rounded-lg border border-slate-200 p-4">
      <div class="text-xs uppercase text-slate-500">Utilizadores</div>
      <div class="text-2xl font-semibold text-slate-800"><?php echo $stats['users']; ?></div>
    </div>
    <div class="rounded-lg border border-slate-200 p-4">
      <div class="text-xs uppercase text-slate-500">Inscrições</div>
      <div class="text-2xl font-semibold text-slate-800"><?php echo $stats['registrations']; ?></div>
    </div>
    <div class="rounded-lg border border-slate-200 p-4">
      <div class="text-xs uppercase text-slate-500">Faculdades</div>
      <div class="text-2xl font-semibold text-slate-800"><?php echo $stats['faculties']; ?></div>
    </div>
    <div class="rounded-lg border border-slate-200 p-4">
      <div class="text-xs uppercase text-slate-500">Cursos</div>
      <div class="text-2xl font-semibold text-slate-800"><?php echo $stats['courses']; ?></div>
    </div>
    <div class="rounded-lg border border-slate-200 p-4">
      <div class="text-xs uppercase text-slate-500">Disciplinas</div>
      <div class="text-2xl font-semibold text-slate-800"><?php echo $stats['subjects']; ?></div>
    </div>
  </div>

  <div class="flex gap-3">
    <a class="px-4 py-2 rounded-md bg-blue-600 text-white hover:bg-blue-700" href="/admin/faculties.php">Gerir Faculdades</a>
    <a class="px-4 py-2 rounded-md border border-blue-200 text-blue-700 hover:bg-blue-50" href="/admin/courses.php">Gerir Cursos</a>
    <a class="px-4 py-2 rounded-md border border-blue-200 text-blue-700 hover:bg-blue-50" href="/admin/subjects.php">Gerir Disciplinas</a>
    <a class="px-4 py-2 rounded-md border border-blue-200 text-blue-700 hover:bg-blue-50" href="/admin/course-subjects.php">Mapear Disciplinas</a>
  </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
