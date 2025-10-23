<?php
require_once __DIR__ . '/../includes/auth.php';
require_admin();

$pageTitle = 'Mapear Disciplinas ao Curso - UP Maputo';
$db = DB::getInstance();

// Load courses and subjects
$db->query('SELECT c.id, c.name, f.name AS faculty_name FROM courses c JOIN faculties f ON f.id = c.faculty_id ORDER BY f.name, c.name');
$courses = $db->results();
$db->query('SELECT id, name FROM subjects ORDER BY name');
$subjects = $db->results();

$selectedCourseId = (int) (isset($_GET['course_id']) ? $_GET['course_id'] : 0);

if (Input::exists()) {
  if (Token::check(Input::get('token'))) {
    $selectedCourseId = (int) Input::get('course_id');
    $selectedSubjects = isset($_POST['subject_ids']) && is_array($_POST['subject_ids']) ? array_map('intval', $_POST['subject_ids']) : [];

    if ($selectedCourseId > 0) {
      // remove old mappings
      $db->query('DELETE FROM course_subjects WHERE course_id = ?', [$selectedCourseId]);
      // insert new mappings
      foreach ($selectedSubjects as $sid) {
        $db->insert('course_subjects', [
          'course_id' => $selectedCourseId,
          'subject_id' => $sid,
        ]);
      }
      Session::flash('home', 'Mapeamento atualizado.');
      Redirect::to('/admin/course-subjects.php?course_id=' . $selectedCourseId);
    } else {
      Session::flash('home', 'Selecione um curso.');
    }
  }
}

$currentMap = [];
if ($selectedCourseId > 0) {
  $db->query('SELECT subject_id FROM course_subjects WHERE course_id = ?', [$selectedCourseId]);
  foreach ($db->results() as $row) { $currentMap[(int)$row->subject_id] = true; }
}

include __DIR__ . '/../includes/header.php';
?>

<div class="space-y-6">
  <h1 class="text-2xl font-semibold text-slate-800">Mapear Disciplinas ao Curso</h1>

  <form method="get" class="flex items-end gap-3">
    <div class="flex-1">
      <label class="block text-sm text-slate-600 mb-1" for="course_id">Curso</label>
      <select id="course_id" name="course_id" class="w-full rounded-md border-slate-300 focus:border-blue-500 focus:ring-blue-500" onchange="this.form.submit()">
        <option value="">Selecione...</option>
        <?php foreach ($courses as $c): ?>
          <option value="<?php echo (int)$c->id; ?>" <?php echo $selectedCourseId===(int)$c->id?'selected':''; ?>><?php echo escape($c->faculty_name . ' — ' . $c->name); ?></option>
        <?php endforeach; ?>
      </select>
    </div>
  </form>

  <?php if ($selectedCourseId > 0): ?>
  <form method="post" class="space-y-4">
    <div class="grid md:grid-cols-2 gap-3">
      <?php foreach ($subjects as $s): $sid=(int)$s->id; $checked = isset($currentMap[$sid]); ?>
      <label class="flex items-center gap-3 rounded-md border border-slate-200 p-3 hover:bg-slate-50">
        <input type="checkbox" name="subject_ids[]" value="<?php echo $sid; ?>" <?php echo $checked?'checked':''; ?> />
        <span class="text-slate-800"><?php echo escape($s->name); ?></span>
      </label>
      <?php endforeach; ?>
    </div>

    <input type="hidden" name="course_id" value="<?php echo $selectedCourseId; ?>" />
    <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
    <button class="rounded-md bg-blue-600 text-white px-4 py-2 hover:bg-blue-700">Guardar</button>
  </form>
  <?php endif; ?>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
