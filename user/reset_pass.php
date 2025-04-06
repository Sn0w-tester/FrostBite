<?php
$input_password = "1";
$hashed_password = password_hash($input_password, PASSWORD_DEFAULT);
echo "New hash for 'a': " . $hashed_password . "<br>";

$verify_result = password_verify($input_password, $hashed_password);
echo "password_verify result: " . ($verify_result ? "true" : "false") . "<br>";

$stored_password = '$2y$10$.0fcDYgD8wRL5oMVGUrjduz4jEhqCCsUpJjKA0KbqTV';
$verify_old = password_verify($input_password, $stored_password);
echo "Verify with stored password: " . ($verify_old ? "true" : "false") . "<br>";
?>