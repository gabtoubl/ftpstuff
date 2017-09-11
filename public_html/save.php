<?php
print_r($_POST);
if (isset($_POST['db'])) {
  $content = $_POST['db'];
  $file = 'db.js';
  echo $content;
  file_put_contents($file, 'var jsonTree = '.$content);
}
?>
