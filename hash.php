<?php
$mot = "123456"; // Ton mot de passe
$hash = password_hash($mot, PASSWORD_DEFAULT);
echo "Hash pour 123456 :<br>" . $hash;
?>
