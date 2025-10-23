<?php
$alert = '';
if (Session::exists('home')) {
  $alert = Session::flash('home');
}
if (!empty($alert)) {
  echo '<div class="mb-4 rounded-md border border-blue-200 bg-blue-50 p-4 text-blue-800">' . escape($alert) . '</div>';
}
?>
