<?php
require_once 'includes/auth.php';
require_login();

$pageTitle = 'Inscrição - UP Maputo';
$user = new User();
$db = DB::getInstance();

// Load faculties
$db->query('SELECT id, name FROM faculties ORDER BY name');
$faculties = $db->results();

// Handle submission
if (Input::exists()) {
    if (Token::check(Input::get('token'))) {
        $facultyId = (int) Input::get('faculty_id');
        $courseId = (int) Input::get('course_id');

        // basic validation
        if ($facultyId <= 0 || $courseId <= 0) {
            Session::flash('home', 'Selecione a faculdade e o curso.');
        } else {
            // ensure course belongs to faculty
            $db->query('SELECT id FROM courses WHERE id = ? AND faculty_id = ?', [$courseId, $facultyId]);
            if (!$db->count()) {
                Session::flash('home', 'Curso inválido para a faculdade selecionada.');
            } else {
                // check if user already registered
                $db->query('SELECT id FROM registrations WHERE user_id = ?', [$user->data()->id]);
                if ($db->count()) {
                    // update existing registration
                    $regId = $db->first()->id;
                    $db->update('registrations', $regId, [
                        'faculty_id' => $facultyId,
                        'course_id' => $courseId,
                    ]);
                } else {
                    $db->insert('registrations', [
                        'user_id' => $user->data()->id,
                        'faculty_id' => $facultyId,
                        'course_id' => $courseId,
                        'created_at' => date('Y-m-d H:i:s'),
                    ]);
                }
                Session::flash('home', 'Inscrição efetuada com sucesso.');
                Redirect::to('my-registration.php');
            }
        }
    }
}

include 'includes/header.php';
?>

<div class="max-w-2xl mx-auto">
  <h1 class="text-2xl font-semibold text-slate-800 mb-6">Inscrição</h1>

  <form action="" method="post" class="space-y-4" id="apply-form">
    <div>
      <label class="block text-sm text-slate-600 mb-1" for="faculty_id">Faculdade</label>
      <select name="faculty_id" id="faculty_id" class="w-full rounded-md border-slate-300 focus:border-blue-500 focus:ring-blue-500">
        <option value="">Selecione...</option>
        <?php foreach ($faculties as $f): ?>
          <option value="<?php echo (int)$f->id; ?>"><?php echo escape($f->name); ?></option>
        <?php endforeach; ?>
      </select>
    </div>

    <div>
      <label class="block text-sm text-slate-600 mb-1" for="course_id">Curso</label>
      <select name="course_id" id="course_id" class="w-full rounded-md border-slate-300 focus:border-blue-500 focus:ring-blue-500">
        <option value="">Selecione a faculdade primeiro...</option>
      </select>
    </div>

    <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
    <button class="rounded-md bg-blue-600 text-white px-4 py-2 hover:bg-blue-700">Submeter inscrição</button>
  </form>
</div>

<script>
  const facultySelect = document.getElementById('faculty_id');
  const courseSelect = document.getElementById('course_id');
  facultySelect.addEventListener('change', async () => {
    const id = facultySelect.value;
    courseSelect.innerHTML = '<option>Carregando...</option>';
    if (!id) { courseSelect.innerHTML = '<option>Selecione a faculdade primeiro...</option>'; return; }
    const res = await fetch('/api/courses.php?faculty_id=' + encodeURIComponent(id));
    const data = await res.json();
    courseSelect.innerHTML = '<option value="">Selecione...</option>';
    data.forEach(c => {
      const opt = document.createElement('option');
      opt.value = c.id;
      opt.textContent = c.name;
      courseSelect.appendChild(opt);
    });
  });
</script>

<?php include 'includes/footer.php'; ?>
