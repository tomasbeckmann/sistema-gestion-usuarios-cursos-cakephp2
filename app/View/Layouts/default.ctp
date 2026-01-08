<!DOCTYPE html>
<html>

<head>
	<?php echo $this->Html->charset(); ?>
	<title><?php echo $this->fetch('title'); ?> - Sistema de Gestión</title>


	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

	<?php
	echo $this->Html->meta('icon');
	echo $this->fetch('meta');
	echo $this->fetch('css');
	?>

	<style>
		body {
			background-color: #f8f9fa;
			min-height: 100vh;
			padding-top: 70px;
		}

		.navbar {
			box-shadow: 0 2px 4px rgba(0, 0, 0, .1);
		}

		.navbar-brand {
			font-weight: 600;
		}

		.main-container {
			padding: 30px 0;
		}
	</style>
</head>

<body>

	<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
		<div class="container-fluid">
			<a class="navbar-brand"
				href="<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'index')); ?>">
				📚 Mis Cursos
			</a>
			<div class="ms-auto">
				<span class="navbar-text text-white me-3">
					Bienvenido, <?php echo h($this->Session->read('Auth.User.nombre')); ?>
				</span>
				<a href="<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'logout')); ?>"
					class="btn btn-outline-light btn-sm">
					Cerrar Sesión
				</a>
			</div>
		</div>
	</nav>


	<div class="main-container">

		<?php
		$flash = $this->Session->read('Message.flash');
		if ($flash && isset($flash['params']) && isset($flash['params']['class'])):
			$alertClass = $flash['params']['class'] == 'error' ? 'danger' : ($flash['params']['class'] == 'success' ? 'success' : 'info');
			$icon = $flash['params']['class'] == 'success' ? '✅' : ($flash['params']['class'] == 'error' ? '❌' : 'ℹ️');
			$message = isset($flash['message']) ? $flash['message'] : '';
			?>
			<div class="position-fixed top-0 end-0 p-3" style="z-index: 9999; margin-top: 60px;">
				<div class="toast show align-items-center text-white bg-<?php echo $alertClass; ?> border-0" role="alert"
					id="flashToast">
					<div class="d-flex">
						<div class="toast-body">
							<strong><?php echo $icon; ?></strong> <?php echo h($message); ?>
						</div>
						<button type="button" class="btn-close btn-close-white me-2 m-auto"
							data-bs-dismiss="toast"></button>
					</div>
				</div>
			</div>
			<script>
				setTimeout(function () {
					var toastEl = document.getElementById('flashToast');
					if (toastEl) {
						var bsToast = bootstrap.Toast.getInstance(toastEl);
						if (!bsToast) {
							bsToast = new bootstrap.Toast(toastEl);
						}
						bsToast.hide();
						setTimeout(function () {
							if (toastEl.parentElement) {
								toastEl.parentElement.remove();
							}
						}, 500);
					}
				}, 3000);
			</script>
			<?php
			$this->Session->write('Message.flash', null);
		endif;
		?>

		<?php echo $this->fetch('content'); ?>
	</div>


	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
	<?php echo $this->fetch('script'); ?>
</body>

</html>