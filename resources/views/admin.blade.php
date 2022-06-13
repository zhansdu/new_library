<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" href="favicon.ico" type="image/x-icon" />

	<!-- bootstrap --> 

	<link rel="stylesheet" href="/bootstrap/bootstrap.4.3.1.css.bootstrap.min.css">
	<script src="/bootstrap/jquery-3.3.1.slim.min.js"></script>
	<script src="/bootstrap/ajax.libs.popper.js.1.14.7.umd.popper.min.js"></script>
	<script src="/bootstrap/bootstrap.4.3.1.js.bootstrap.min.js"></script>
	
	<!-- /bootstrap -->

	<title>Library SDU - Admin Panel</title>
</head>
<body>
	<div id="app">
		<app></app>
	</div>
	<script src="{{ mix('js/admin.js') }}"></script>
</body>
</html>
