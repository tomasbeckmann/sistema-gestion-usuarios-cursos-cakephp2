<!DOCTYPE html>
<html>

<head>
	<?php echo $this->Html->charset(); ?>
	<title>Iniciar Sesión - Sistema de Gestión</title>

	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

	<?php echo $this->Html->meta('icon'); ?>

	<style>
		body {
			background-color: #f8f9fa;
			min-height: 100vh;
			display: flex;
			align-items: center;
			justify-content: center;
		}

		.login-container {
			max-width: 450px;
			width: 100%;
		}

		.login-card {
			background: white;
			border-radius: 8px;
			box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
			overflow: hidden;
		}

		.login-header {
			background-color: #343a40;
			color: white;
			padding: 30px;
			text-align: center;
		}

		.login-header h3 {
			margin: 0 0 10px 0;
			font-size: 24px;
			font-weight: 600;
		}

		.login-header p {
			margin: 0;
			opacity: 0.9;
			font-size: 14px;
		}

		.login-body {
			padding: 30px;
		}

		.form-group {
			margin-bottom: 20px;
		}

		.form-group label {
			font-weight: 600;
			color: #495057;
			margin-bottom: 8px;
			display: block;
			font-size: 14px;
		}

		.form-control {
			border: 1px solid #ced4da;
			border-radius: 4px;
			padding: 10px 12px;
			font-size: 15px;
			transition: border-color 0.15s;
		}

		.form-control:focus {
			border-color: #80bdff;
			box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
			outline: none;
		}

		.btn-login {
			background-color: #007bff;
			border: none;
			border-radius: 4px;
			color: white;
			padding: 12px;
			font-size: 16px;
			font-weight: 500;
			width: 100%;
			transition: background-color 0.15s;
			cursor: pointer;
		}

		.btn-login:hover {
			background-color: #0056b3;
		}

		.alert {
			border-radius: 4px;
			border: 1px solid transparent;
			padding: 12px;
			margin-bottom: 20px;
		}

		.alert-danger {
			background-color: #f8d7da;
			border-color: #f5c6cb;
			color: #721c24;
		}

		.alert-success {
			background-color: #d4edda;
			border-color: #c3e6cb;
			color: #155724;
		}

		.login-footer {
			text-align: center;
			padding: 15px;
			background-color: #f8f9fa;
			color: #6c757d;
			font-size: 12px;
			border-top: 1px solid #dee2e6;
		}

		.brand-icon {
			font-size: 48px;
			margin-bottom: 15px;
		}
	</style>
</head>

<body>
	<?php echo $this->fetch('content'); ?>

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>