<!DOCTYPE html>
<html>

<head>
	<?php echo $this->Html->charset(); ?>
	<title>
		<?php echo $this->fetch('title'); ?> - Sistema de Gestión
	</title>


	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

	<?php
	echo $this->Html->meta('icon');
	echo $this->fetch('meta');
	echo $this->fetch('css');
	echo $this->fetch('script');
	?>

	<style>
		.navbar-brand {
			font-weight: bold;
		}

		.main-content {
			min-height: calc(100vh - 56px);
			padding-bottom: 30px;
		}
	</style>
</head>

<body>
	<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
		<div class="container-fluid">
			<a class="navbar-brand" href="/admin/dashboard">
				📚 Sistema de Gestión
			</a>
			<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
				<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse" id="navbarNav">
				<ul class="navbar-nav me-auto">
					<li class="nav-item">
						<?php echo $this->Html->link(
							'📊 Dashboard',
							'/admin/dashboard',
							array('class' => 'nav-link', 'escape' => false)
						); ?>
					</li>
					<li class="nav-item">
						<?php echo $this->Html->link(
							'👥 Usuarios',
							'/admin/users',
							array('class' => 'nav-link', 'escape' => false)
						); ?>
					</li>
					<li class="nav-item">
						<?php echo $this->Html->link(
							'📖 Cursos',
							'/admin/courses',
							array('class' => 'nav-link', 'escape' => false)
						); ?>
					</li>
					<li class="nav-item">
						<?php echo $this->Html->link(
							'📋 Historial',
							array('controller' => 'users', 'action' => 'admin_logs'),
							array('class' => 'nav-link', 'escape' => false)
						); ?>
					</li>
				</ul>
				<ul class="navbar-nav">
					<li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
							data-bs-toggle="dropdown">
							<?php
							$userName = $this->Session->read('Auth.User.nombre');
							echo h($userName ? $userName : 'Admin');
							?>
						</a>
						<ul class="dropdown-menu dropdown-menu-end">
							<li>
								<span class="dropdown-item-text">
									<small class="text-muted">Administrador</small>
								</span>
							</li>
							<li>
								<hr class="dropdown-divider">
							</li>
							<li>
								<?php echo $this->Html->link(
									'🚪 Cerrar Sesión',
									array('controller' => 'users', 'action' => 'logout'),
									array('class' => 'dropdown-item', 'escape' => false)
								); ?>
							</li>
						</ul>
					</li>
				</ul>
			</div>
		</div>
	</nav>


	<div class="main-content">

		<div class="position-fixed top-0 end-0 p-3" style="z-index: 9999;" id="flash-container">
			<?php echo $this->Session->flash(); ?>
		</div>

		<?php echo $this->fetch('content'); ?>
	</div>

	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>


	<script>
		jQuery(function ($) {

			$(document).on('click', '.btn-toggle-user', function () {
				const btn = $(this);
				const userId = btn.data('user-id');
				const row = btn.closest('tr');

				if (!confirm('¿Estás seguro de cambiar el estado de este usuario?')) {
					return;
				}

				btn.prop('disabled', true).text('Procesando...');

				$.ajax({
					url: '/admin/users/toggle-active/' + userId,
					type: 'POST',
					dataType: 'json',
					headers: {
						'X-Requested-With': 'XMLHttpRequest'
					},
					success: function (response) {
						if (response.success) {
							const badge = row.find('.badge:contains("Activo"), .badge:contains("Inactivo")');
							badge.removeClass('bg-success bg-secondary')
								.addClass(response.badgeClass)
								.text(response.statusText);

							btn.text(response.buttonText)
								.data('current-status', response.newStatus);

							showNotification(response.message, 'success');
						} else {
							showNotification(response.message, 'error');
						}
					},
					error: function () {
						showNotification('Error al cambiar el estado del usuario', 'error');
					},
					complete: function () {
						btn.prop('disabled', false);
					}
				});
			});


			$(document).on('click', '.btn-toggle-course', function () {
				const btn = $(this);
				const courseId = btn.data('course-id');
				const row = btn.closest('tr');

				if (!confirm('¿Estás seguro de cambiar el estado de este curso?')) {
					return;
				}

				btn.prop('disabled', true).text('Procesando...');

				$.ajax({
					url: '/admin/courses/toggle-active/' + courseId,
					type: 'POST',
					dataType: 'json',
					headers: {
						'X-Requested-With': 'XMLHttpRequest'
					},
					success: function (response) {
						if (response.success) {
							const badge = row.find('td:nth-child(6) .badge');
							badge.removeClass('bg-success bg-secondary')
								.addClass(response.badgeClass)
								.text(response.statusText);

							btn.text(response.buttonText)
								.data('current-status', response.newStatus);

							showNotification(response.message, 'success');
						} else {
							showNotification(response.message, 'error');
						}
					},
					error: function () {
						showNotification('Error al cambiar el estado del curso', 'error');
					},
					complete: function () {
						btn.prop('disabled', false);
					}
				});
			});


			$(document).on('change', '#AuditLogUser, #AuditLogAction, #AuditLogModel', function () {
				performAuditLogSearch();
			});


			function performAuditLogSearch() {
				const userFilter = $('#AuditLogUser').val() || '';
				const actionFilter = $('#AuditLogAction').val() || '';
				const modelFilter = $('#AuditLogModel').val() || '';

				$.ajax({
					url: '/users/admin_logs',
					type: 'GET',
					data: {
						user_id: userFilter,
						action: actionFilter,
						model: modelFilter
					},
					beforeSend: function () {
						$('tbody').css('opacity', '0.5');
					},
					success: function (response) {
						const newTable = $(response).find('tbody').html();
						$('tbody').html(newTable);
					},
					complete: function () {
						$('tbody').css('opacity', '1');
					},
					error: function () {
						showNotification('Error al realizar la búsqueda', 'error');
					}
				});
			}


			let userSearchTimeout;
			$('#UserSearch').on('keyup', function () {
				clearTimeout(userSearchTimeout);
				const searchTerm = $(this).val();

				if (searchTerm.length >= 2 || searchTerm.length === 0) {
					userSearchTimeout = setTimeout(function () {
						performUserSearch(searchTerm);
					}, 500);
				}
			});


			let courseSearchTimeout;
			$('#CourseSearch').on('keyup', function () {
				clearTimeout(courseSearchTimeout);
				const searchTerm = $(this).val();

				if (searchTerm.length >= 2 || searchTerm.length === 0) {
					courseSearchTimeout = setTimeout(function () {
						performCourseSearch(searchTerm);
					}, 500);
				}
			});


			$(document).on('change', '#UserRole, #UserActive', function () {
				const searchTerm = $('#UserSearch').val() || '';
				performUserSearch(searchTerm);
			});


			$(document).on('change', '#CourseActive', function () {
				const searchTerm = $('#CourseSearch').val() || '';
				performCourseSearch(searchTerm);
			});


			function performUserSearch(searchTerm) {
				const roleFilter = $('#UserRole').val() || '';
				const activeFilter = $('#UserActive').val() || '';

				$.ajax({
					url: '/admin/users',
					type: 'GET',
					data: {
						search: searchTerm,
						role: roleFilter,
						active: activeFilter
					},
					beforeSend: function () {
						$('tbody').css('opacity', '0.5');
					},
					success: function (response) {
						const newTable = $(response).find('tbody').html();
						$('tbody').html(newTable);
					},
					complete: function () {
						$('tbody').css('opacity', '1');
					},
					error: function () {
						showNotification('Error al realizar la búsqueda', 'error');
					}
				});
			}

			function performCourseSearch(searchTerm) {
				const activeFilter = $('#CourseActive').val() || '';

				$.ajax({
					url: '/admin/courses',
					type: 'GET',
					data: {
						search: searchTerm,
						active: activeFilter
					},
					beforeSend: function () {
						$('tbody').css('opacity', '0.5');
					},
					success: function (response) {
						const newTable = $(response).find('tbody').html();
						$('tbody').html(newTable);
					},
					complete: function () {
						$('tbody').css('opacity', '1');
					},
					error: function () {
						showNotification('Error al realizar la búsqueda', 'error');
					}
				});
			}

			function showNotification(message, type) {
				const alertClass = type === 'success' ? 'success' : 'danger';
				const icon = type === 'success' ? '✅' : '❌';

				const toastHtml = `
		<div class="position-fixed top-0 end-0 p-3" style="z-index: 9999;">
			<div class="toast show align-items-center text-white bg-${alertClass} border-0" role="alert">
				<div class="d-flex">
					<div class="toast-body">
						<strong>${icon}</strong> ${message}
					</div>
					<button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
				</div>
			</div>
		</div>
	`;

				$('body').append(toastHtml);

				setTimeout(function () {
					$('.toast').fadeOut(function () {
						$(this).parent().remove();
					});
				}, 3000);
			}
		});
	</script>
	<script>

		jQuery(function ($) {
			$('#flash-container .message').each(function () {
				var $msg = $(this);
				var message = $msg.text().trim();
				var isError = $msg.hasClass('error');
				var isSuccess = $msg.hasClass('success');

				var alertClass = isError ? 'danger' : (isSuccess ? 'success' : 'info');
				var icon = isSuccess ? '✅' : (isError ? '❌' : 'ℹ️');

				var toast = $('<div class="toast show align-items-center text-white bg-' + alertClass + ' border-0" role="alert">' +
					'<div class="d-flex">' +
					'<div class="toast-body">' +
					'<strong>' + icon + '</strong> ' + message +
					'</div>' +
					'<button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>' +
					'</div>' +
					'</div>');

				$msg.replaceWith(toast);

				setTimeout(function () {
					var bsToast = new bootstrap.Toast(toast[0]);
					bsToast.hide();
					setTimeout(function () {
						toast.remove();
					}, 500);
				}, 3000);
			});
		});
	</script>
</body>

</html>