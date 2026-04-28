<?php
session_start();
session_destroy();
header("Location: login_form.php?success=You have been logged out.");
exit();
?>