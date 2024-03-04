<?php
session_start();
// Delete certain session
unset($_SESSION);
// Delete all session variables
session_destroy();

// Jump to login page
?>
<script>
	window.location='login.php';
</script>
<?php
//header('Location: index.php'); //you can change this to the home page of website

?>
