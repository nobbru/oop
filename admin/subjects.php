<?php
require_once __DIR__ . '/../includes/auth.php';
require_admin();

$pageTitle = 'Gerir Disciplinas - UP Maputo';
$db = DB::getInstance();

if (Input::exists()) {
  if (Token::check(Input::get('token'))) {
    $name = trim(Input::get('name'));
    $deleteId = (int) Input::get('delete_id');

    if ($deleteId > 0) {
      $db->delete('subjects', ['id', '=', $deleteId]);
      Session::flash('home', 'Disciplina removida.');
      Redirect::to('/admin/subjects.php');
    } else if ($name !== '') {
      $db->insert('subjects', [
        'name' => $name,
        'created_at' => date('Y-m-d H:i:s'),
      ]);
      Session::flash('home', 'Disciplina criada.');
      Redirect::to('/admin/subjects.php');
    } else {
      Session::flash('home', 'Indique o nome da disciplina.');
    }
  }
}

$db->query('SELECT id, name, created_at FROM subjects ORDER BY name');
$rows = $db->results();

include __DIR__ . '/../includes/header.php';
?>

<div class="space-y-6">
  <h1 class="text-2xl font-semibold text-slate-800">Disciplinas</h1>

  <form method="post" class="flex items-end gap-3">
    <div class="flex-1">
      <label class="block text-sm text-slate-600 mb-1" for="name">Nome</label>
      <input type="text" id="name" name="name" class="w-full rounded-md border-slate-300 focus:border-blue-500 focus:ring-blue-500" />
    </div>
    <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
    <button class="rounded-md bg-blue-600 text-white px-4 py-2 hover:bg-blue-700">Adicionar</button>
  </form>

  <div class="overflow-x-auto rounded-lg border border-slate-200">
    <table class="min-w-full divide-y divide-slate-200">
      <thead class="bg-slate-50">
        <tr>
          <th class="px-4 py-2 text-left text-xs font-medium text-slate-500 uppercase">ID</th>
          <th class="px-4 py-2 text-left text-xs font-medium text-slate-500 uppercase">Nome</th>
          <th class="px-4 py-2 text-left text-xs font-medium text-slate-500 uppercase">Criada em</th>
          <th class="px-4 py-2"></th>
        </tr>
      </thead>
      <tbody class="divide-y divide-slate-200">
        <?php foreach ($rows as $r): ?>
          <tr>
            <td class="px-4 py-2 text-slate-600"><?php echo (int)$r->id; ?></td>
            <td class="px-4 py-2 font-medium text-slate-800"><?php echo escape($r->name); ?></td>
            <td class="px-4 py-2 text-slate-600"><?php echo escape($r->created_at); ?></td>
            <td class="px-4 py-2 text-right">
              <form method="post" onsubmit="return confirm('Remover esta disciplina?');">
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
