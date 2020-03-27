<?php 
use Phppot\Member;

session_start();
?>
<HTML>
<HEAD>
<TITLE>user-registration</TITLE>
<link href="./phppot-style.css" type="text/css"
	rel="stylesheet" />
<link href="./user-registration.css" type="text/css"
	rel="stylesheet" />
<script src="./jquery-3.3.1.js" type="text/javascript"></script>
</HEAD>
<BODY>
	<div class="phppot-container">
	<?php require_once "login-form.php";?>
	</div>
</BODY>
</HTML>