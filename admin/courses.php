<?php
require_once __DIR__ . '/../includes/auth.php';
require_admin();

$pageTitle = 'Gerir Cursos - UP Maputo';
$db = DB::getInstance();

// Load faculties for select
$db->query('SELECT id, name FROM faculties ORDER BY name');
$faculties = $db->results();

if (Input::exists()) {
  if (Token::check(Input::get('token'))) {
    $name = trim(Input::get('name'));
    $facultyId = (int) Input::get('faculty_id');
    $deleteId = (int) Input::get('delete_id');

    if ($deleteId > 0) {
      $db->delete('courses', ['id', '=', $deleteId]);
      Session::flash('home', 'Curso removido.');
      Redirect::to('/admin/courses.php');
    } else if ($name !== '' && $facultyId > 0) {
      $db->insert('courses', [
        'name' => $name,
        'faculty_id' => $facultyId,
        'created_at' => date('Y-m-d H:i:s'),
      ]);
      Session::flash('home', 'Curso criado.');
      Redirect::to('/admin/courses.php');
    } else {
      Session::flash('home', 'Indique a faculdade e o nome do curso.');
    }
  }
}

$db->query('SELECT c.id, c.name, c.created_at, f.name AS faculty_name FROM courses c JOIN faculties f ON f.id = c.faculty_id ORDER BY f.name, c.name');
$rows = $db->results();

include __DIR__ . '/../includes/header.php';
?>

<div class="space-y-6">
  <h1 class="text-2xl font-semibold text-slate-800">Cursos</h1>

  <form method="post" class="grid md:grid-cols-3 gap-3 items-end">
    <div>
      <label class="block text-sm text-slate-600 mb-1" for="faculty_id">Faculdade</label>
      <select id="faculty_id" name="faculty_id" class="w-full rounded-md border-slate-300 focus:border-blue-500 focus:ring-blue-500">
        <option value="">Selecione...</option>
        <?php foreach ($faculties as $f): ?>
        <option value="<?php echo (int)$f->id; ?>"><?php echo escape($f->name); ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div>
      <label class="block text-sm text-slate-600 mb-1" for="name">Nome</label>
      <input type="text" id="name" name="name" class="w-full rounded-md border-slate-300 focus:border-blue-500 focus:ring-blue-500" />
    </div>
    <div>
      <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
      <button class="w-full md:w-auto rounded-md bg-blue-600 text-white px-4 py-2 hover:bg-blue-700">Adicionar</button>
    </div>
  </form>

  <div class="overflow-x-auto rounded-lg border border-slate-200">
    <table class="min-w-full divide-y divide-slate-200">
      <thead class="bg-slate-50">
        <tr>
          <th class="px-4 py-2 text-left text-xs font-medium text-slate-500 uppercase">ID</th>
          <th class="px-4 py-2 text-left text-xs font-medium text-slate-500 uppercase">Faculdade</th>
          <th class="px-4 py-2 text-left text-xs font-medium text-slate-500 uppercase">Nome</th>
          <th class="px-4 py-2 text-left text-xs font-medium text-slate-500 uppercase">Criado em</th>
          <th class="px-4 py-2"></th>
        </tr>
      </thead>
      <tbody class="divide-y divide-slate-200">
        <?php foreach ($rows as $r): ?>
        <tr>
          <td class="px-4 py-2 text-slate-600"><?php echo (int)$r->id; ?></td>
          <td class="px-4 py-2 text-slate-700"><?php echo escape($r->faculty_name); ?></td>
          <td class="px-4 py-2 font-medium text-slate-800"><?php echo escape($r->name); ?></td>
          <td class="px-4 py-2 text-slate-600"><?php echo escape($r->created_at); ?></td>
          <td class="px-4 py-2 text-right">
            <form method="post" onsubmit="return confirm('Remover este curso?');">
              <input type="hidden" name="delete_id" value="<?php echo (int)$r->id; ?>">
              <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
              <button class="rounded-md px-3 py-1.5 border border-slate-300 text-slate-700 hover:bg-slate-50">Remover</button>
            </form>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
