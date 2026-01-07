<?php
// Primero cargamos CakePHP
require '../Config/bootstrap.php';

// Luego importamos la clase Security
App::uses('Security', 'Utility');

// Ahora sí podemos usar Security
echo "Password 'admin123': " . Security::hash('admin123', 'blowfish', true) . "<br>";
echo "Password 'user123': " . Security::hash('user123', 'blowfish', true) . "<br>";
?>
