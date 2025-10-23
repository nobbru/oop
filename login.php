<?php
require_once 'core/init.php';

$pageTitle = 'Entrar - UP Maputo';

if (Input::exists()) {
    if (Token::check(Input::get('token'))) {
        $validate = new Validate();
        $validation = $validate->check($_POST, array(
            'username' => array('required' => true),
            'password' => array('required' => true)
        ));

        if ($validation->passed()) {
            $user = new User();
            $login = $user->login(Input::get('username'), Input::get('password'));
            if ($login) {
                Session::flash('home', 'Bem-vindo!');
                Redirect::to('index.php');
            } else {
                Session::flash('home', 'Credenciais inválidas. Tente novamente.');
            }
        } else {
            Session::flash('home', implode(' ', $validation->errors()));
        }
    }
}

include 'includes/header.php';
?>

<div class="max-w-md mx-auto">
  <h1 class="text-2xl font-semibold text-slate-800 mb-6">Entrar</h1>
  <form action="" method="post" class="space-y-4">
    <div>
      <label for="username" class="block text-sm text-slate-600 mb-1">Utilizador</label>
      <input type="text" name="username" id="username" autocomplete="off" class="w-full rounded-md border-slate-300 focus:border-blue-500 focus:ring-blue-500" />
    </div>
    <div>
      <label for="password" class="block text-sm text-slate-600 mb-1">Palavra-passe</label>
      <input type="password" name="password" id="password" autocomplete="off" class="w-full rounded-md border-slate-300 focus:border-blue-500 focus:ring-blue-500" />
    </div>

    <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
    <button class="w-full rounded-md bg-blue-600 text-white py-2 hover:bg-blue-700">Entrar</button>
  </form>

  <p class="mt-4 text-sm text-slate-600">Ainda não tem conta? <a href="/register.php" class="text-blue-700 hover:underline">Criar conta</a></p>
</div>

<?php include 'includes/footer.php'; ?>
