<?php
require_once 'core/init.php';
$pageTitle = 'Inscrições - UP Maputo';
include 'includes/header.php';

$user = new User();
?>

<div class="grid md:grid-cols-2 gap-8 items-center">
  <div>
    <h1 class="text-3xl md:text-4xl font-semibold text-slate-900 mb-4">Sistema de Inscrições aos Exames de Admissão</h1>
    <p class="text-slate-600 mb-6">Inscreva-se aos exames de admissão da Universidade Pedagógica de Maputo. Escolha a faculdade e o curso pretendido e consulte as disciplinas de avaliação.</p>

    <?php if ($user->isLoggedIn()): ?>
      <div class="flex gap-3">
        <a href="/apply.php" class="px-4 py-2 rounded-md bg-blue-600 text-white hover:bg-blue-700">Fazer inscrição</a>
        <a href="/my-registration.php" class="px-4 py-2 rounded-md border border-blue-200 text-blue-700 hover:bg-blue-50">Minha inscrição</a>
        <?php if ($user->hasRole('admin')): ?>
          <a href="/admin/index.php" class="px-4 py-2 rounded-md border border-slate-200 hover:bg-slate-50">Admin</a>
        <?php endif; ?>
      </div>
    <?php else: ?>
      <div class="flex gap-3">
        <a href="/login.php" class="px-4 py-2 rounded-md bg-blue-600 text-white hover:bg-blue-700">Entrar</a>
        <a href="/register.php" class="px-4 py-2 rounded-md border border-blue-200 text-blue-700 hover:bg-blue-50">Criar conta</a>
      </div>
    <?php endif; ?>
  </div>
  <div class="hidden md:block">
    <div class="rounded-xl border border-blue-100 bg-gradient-to-br from-blue-50 to-white p-6">
      <div class="h-48 rounded-lg bg-white shadow-inner border border-blue-100 flex items-center justify-center text-blue-700">UP Maputo</div>
    </div>
  </div>
</div>

<?php include 'includes/footer.php'; ?>
