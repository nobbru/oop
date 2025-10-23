<?php
require_once __DIR__ . '/../core/init.php';
$currentUser = new User();
$isLoggedIn = $currentUser->isLoggedIn();
$isAdmin = $currentUser->hasRole('admin');
$title = isset($pageTitle) ? $pageTitle : 'UP Maputo - Inscrições';
?>
<!DOCTYPE html>
<html lang="pt">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo escape($title); ?></title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white text-slate-800">
  <header class="border-b border-slate-200 bg-white sticky top-0 z-30">
    <div class="max-w-6xl mx-auto px-4">
      <div class="flex items-center justify-between h-16">
        <a href="/index.php" class="flex items-center gap-2 font-semibold text-blue-700">
          <span class="inline-block w-3 h-6 bg-blue-600 rounded-sm"></span>
          <span>Universidade Pedagógica de Maputo</span>
        </a>
        <nav class="flex items-center gap-3">
          <?php if ($isLoggedIn): ?>
            <a class="px-3 py-2 rounded-md hover:bg-blue-50 text-slate-700" href="/apply.php">Inscrição</a>
            <a class="px-3 py-2 rounded-md hover:bg-blue-50 text-slate-700" href="/my-registration.php">Minha Inscrição</a>
            <?php if ($isAdmin): ?>
              <a class="px-3 py-2 rounded-md hover:bg-blue-50 text-blue-700 font-medium" href="/admin/index.php">Admin</a>
            <?php endif; ?>
            <a class="px-3 py-2 rounded-md bg-blue-600 text-white hover:bg-blue-700" href="/logout.php">Sair</a>
          <?php else: ?>
            <a class="px-3 py-2 rounded-md hover:bg-blue-50 text-slate-700" href="/login.php">Entrar</a>
            <a class="px-3 py-2 rounded-md bg-blue-600 text-white hover:bg-blue-700" href="/register.php">Criar Conta</a>
          <?php endif; ?>
        </nav>
      </div>
    </div>
  </header>
  <main class="max-w-6xl mx-auto px-4 py-6">
    <?php include __DIR__ . '/alerts.php'; ?>
