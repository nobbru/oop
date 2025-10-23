<?php
require_once 'core/init.php';

$pageTitle = 'Criar Conta - UP Maputo';

if (Input::exists()) {
    if (Token::check(Input::get('token'))) {
        $validate = new Validate();
        $validation = $validate->check($_POST, array(
            'username' => array(
                'required' => true,
                'min' => 2,
                'max' => 20,
                'unique' => 'users'
            ),
            'password' => array(
                'required' => true,
                'min' => 6
            ),
            'password_again' => array(
                'required' => true,
                'matches' => 'password'
            ),
            'name' => array(
                'required' => true,
                'min' => 2,
                'max' => 50
            ),
        ));

        if ($validation->passed()) {
            $user = new User();
            $salt = Hash::salt(32);

            try {
                $user->create(array(
                    'username' => Input::get('username'),
                    'password' => Hash::make(Input::get('password'), $salt),
                    'salt' => $salt,
                    'name' => Input::get('name'),
                    'joined' => date('Y-m-d H:i:s'),
                    'grupo' => 1
                ));

                Session::flash('home', 'Registo efectuado com sucesso. Pode agora entrar.');
                Redirect::to('login.php');
            } catch (Exception $e) {
                Session::flash('home', 'Erro ao registar: ' . $e->getMessage());
            }
        } else {
            Session::flash('home', implode(' ', $validation->errors()));
        }
    }
}

include 'includes/header.php';
?>

<div class="max-w-md mx-auto">
  <h1 class="text-2xl font-semibold text-slate-800 mb-6">Criar conta</h1>
  <form action="" method="post" class="space-y-4">
    <div>
      <label for="name" class="block text-sm text-slate-600 mb-1">Nome completo</label>
      <input type="text" name="name" id="name" value="<?php echo escape(Input::get('name')); ?>" autocomplete="off" class="w-full rounded-md border-slate-300 focus:border-blue-500 focus:ring-blue-500" />
    </div>
    <div>
      <label for="username" class="block text-sm text-slate-600 mb-1">Utilizador</label>
      <input type="text" name="username" id="username" value="<?php echo escape(Input::get('username')); ?>" autocomplete="off" class="w-full rounded-md border-slate-300 focus:border-blue-500 focus:ring-blue-500" />
    </div>
    <div>
      <label for="password" class="block text-sm text-slate-600 mb-1">Palavra-passe</label>
      <input type="password" name="password" id="password" autocomplete="off" class="w-full rounded-md border-slate-300 focus:border-blue-500 focus:ring-blue-500" />
    </div>
    <div>
      <label for="password_again" class="block text-sm text-slate-600 mb-1">Confirmar palavra-passe</label>
      <input type="password" name="password_again" id="password_again" autocomplete="off" class="w-full rounded-md border-slate-300 focus:border-blue-500 focus:ring-blue-500" />
    </div>

    <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
    <button class="w-full rounded-md bg-blue-600 text-white py-2 hover:bg-blue-700">Criar conta</button>
  </form>

  <p class="mt-4 text-sm text-slate-600">Já tem conta? <a href="/login.php" class="text-blue-700 hover:underline">Entrar</a></p>
</div>

<?php include 'includes/footer.php'; ?>
