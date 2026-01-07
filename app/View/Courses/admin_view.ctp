<div class="container mt-4">
	<div class="row">
		<div class="col-md-12">
			<div class="d-flex justify-content-between align-items-center mb-4">
				<h2><?php echo h($course['Course']['nombre']); ?></h2>
				<div>
					<?php echo $this->Html->link('Volver a Cursos', array('action' => 'admin_index'), array('class' => 'btn btn-secondary')); ?>
				</div>
			</div>

			<!-- Información del Curso -->
			<div class="card mb-4">
				<div class="card-header">
					<h4>Información del Curso</h4>
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-md-6">
							<p><strong>Descripción:</strong><br><?php echo h($course['Course']['descripcion']); ?></p>
							<p><strong>Fecha de Inicio:</strong> <?php echo h($course['Course']['fecha_inicio']); ?></p>
							<p><strong>Fecha de Fin:</strong> <?php echo h($course['Course']['fecha_fin']); ?></p>
						</div>
						<div class="col-md-6">
							<p><strong>Estado:</strong>
								<?php if ($course['Course']['active']): ?>
									<span class="badge bg-success">Activo</span>
								<?php else: ?>
									<span class="badge bg-secondary">Inactivo</span>
								<?php endif; ?>
							</p>
							<p><strong>Cupos:</strong></p>
							<div class="progress" style="height: 30px;">
								<?php
								$porcentaje = ($cuposInfo['inscritos'] / $cuposInfo['maximo']) * 100;
								$colorClass = $porcentaje < 70 ? 'bg-success' : ($porcentaje < 90 ? 'bg-warning' : 'bg-danger');
								?>
								<div class="progress-bar <?php echo $colorClass; ?>" role="progressbar"
									style="width: <?php echo $porcentaje; ?>%;"
									aria-valuenow="<?php echo $cuposInfo['inscritos']; ?>" aria-valuemin="0"
									aria-valuemax="<?php echo $cuposInfo['maximo']; ?>">
									<?php echo $cuposInfo['inscritos']; ?> / <?php echo $cuposInfo['maximo']; ?>
								</div>
							</div>
							<small class="text-muted">
								<?php echo $cuposInfo['disponibles']; ?> cupos disponibles
							</small>
						</div>
					</div>
				</div>
			</div>

			<div class="row">
				<!-- Usuarios Inscritos -->
				<div class="col-md-7">
					<div class="card">
						<div class="card-header">
							<div class="d-flex justify-content-between align-items-center">
								<h5 class="mb-0">Usuarios Inscritos (<?php echo count($course['User']); ?>)</h5>
								<?php if (!empty($course['User'])): ?>
									<?php echo $this->Html->link(
										'📥 Exportar Lista',
										array('action' => 'export_course_students', $course['Course']['id']),
										array('class' => 'btn btn-sm btn-outline-success', 'escape' => false)
									); ?>
								<?php endif; ?>
							</div>
						</div>
						<div class="card-body">
							<?php if (empty($course['User'])): ?>
								<p class="text-muted text-center">No hay usuarios inscritos en este curso</p>
							<?php else: ?>
								<table class="table table-sm table-hover">
									<thead>
										<tr>
											<th>ID</th>
											<th>Nombre</th>
											<th>Email</th>
											<th>Acción</th>
										</tr>
									</thead>
									<tbody>
										<?php foreach ($course['User'] as $user): ?>
											<tr>
												<td><?php echo h($user['id']); ?></td>
												<td><?php echo h($user['nombre'] . ' ' . $user['apellido']); ?></td>
												<td><?php echo h($user['email']); ?></td>
												<td>
													<?php echo $this->Form->postLink(
														'Remover',
														array('action' => 'admin_remove_user', $course['Course']['id'], $user['id']),
														array('class' => 'btn btn-danger btn-sm', 'confirm' => '¿Remover a ' . $user['nombre'] . ' de este curso?')
													); ?>
												</td>
											</tr>
										<?php endforeach; ?>
									</tbody>
								</table>
							<?php endif; ?>
						</div>
					</div>
				</div>

				<!-- Agregar Usuario -->
				<div class="col-md-5">
					<div class="card">
						<div class="card-header">
							<h5>Agregar Usuarios al Curso</h5>
						</div>
						<div class="card-body">
							<?php if ($cuposInfo['disponibles'] <= 0): ?>
								<div class="alert alert-warning">
									<strong>¡Cupos llenos!</strong><br>
									No se pueden agregar más usuarios a este curso.
								</div>
							<?php elseif (empty($usuariosDisponibles)): ?>
								<div class="alert alert-info">
									No hay usuarios disponibles para agregar.
									<br><small>Todos los usuarios ya están inscritos en este curso.</small>
								</div>
							<?php else: ?>
								<?php echo $this->Form->create('Course', array(
									'url' => array('action' => 'admin_add_user', $course['Course']['id']),
									'id' => 'formAgregarUsuarios'
								)); ?>

								<div class="mb-3">
									<label class="form-label">Seleccionar Usuarios</label>

									<!-- Dropdown con checkboxes -->
									<div class="dropdown w-100">
										<button
											class="btn btn-outline-secondary dropdown-toggle w-100 d-flex justify-content-between align-items-center"
											type="button" id="dropdownUsuarios" data-bs-toggle="dropdown"
											data-bs-auto-close="outside" aria-expanded="false">
											<span id="dropdownText">Seleccionar usuarios...</span>
											<span class="ms-auto"></span>
										</button>

										<div class="dropdown-menu w-100 p-3" aria-labelledby="dropdownUsuarios"
											style="max-height: 400px; overflow-y: auto;">
											<!-- Búsqueda dentro del dropdown -->
											<div class="mb-2">
												<input type="text" class="form-control form-control-sm"
													id="searchInDropdown" placeholder="Buscar usuario..."
													onclick="event.stopPropagation();">
											</div>

											<hr class="dropdown-divider">

											<!-- Checkbox para seleccionar todos -->
											<div class="form-check mb-2 pb-2 border-bottom">
												<input class="form-check-input" type="checkbox" id="selectAll"
													onclick="event.stopPropagation();">
												<label class="form-check-label fw-bold" for="selectAll">
													Seleccionar Todos
												</label>
											</div>

											<!-- Lista de usuarios -->
											<div id="userList">
												<?php foreach ($usuariosDisponibles as $usuario): ?>
													<div class="form-check user-item"
														data-name="<?php echo strtolower($usuario['User']['nombre'] . ' ' . $usuario['User']['apellido'] . ' ' . $usuario['User']['email']); ?>">
														<input class="form-check-input user-checkbox" type="checkbox"
															name="data[user_ids][]"
															value="<?php echo $usuario['User']['id']; ?>"
															id="user_<?php echo $usuario['User']['id']; ?>"
															onclick="event.stopPropagation();">
														<label class="form-check-label"
															for="user_<?php echo $usuario['User']['id']; ?>">
															<?php echo h($usuario['User']['nombre'] . ' ' . $usuario['User']['apellido']); ?>
															<br><small
																class="text-muted"><?php echo h($usuario['User']['email']); ?></small>
														</label>
													</div>
												<?php endforeach; ?>
											</div>
										</div>
									</div>

									<small class="text-muted d-block mt-2">
										<span id="selectedCount">0</span> usuario(s) seleccionado(s) de
										<?php echo count($usuariosDisponibles); ?> disponibles
									</small>
								</div>

								<div class="alert alert-info">
									<small>
										<strong>Cupos disponibles:</strong> <?php echo $cuposInfo['disponibles']; ?> de
										<?php echo $cuposInfo['maximo']; ?>
									</small>
								</div>

								<?php echo $this->Form->submit('Agregar Usuarios Seleccionados', array(
									'class' => 'btn btn-success w-100',
									'div' => false,
									'id' => 'btnAgregar'
								)); ?>

								<?php echo $this->Form->end(); ?>
							<?php endif; ?>
						</div>
					</div>

					<!-- Búsqueda en el curso (próximamente) -->
					<div class="card mt-3">
						<div class="card-header">
							<h5>Buscar en este curso</h5>
						</div>
						<div class="card-body">
							<input type="text" id="searchUser" class="form-control"
								placeholder="Buscar usuario inscrito...">
							<small class="text-muted">Busca por nombre o email</small>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
	// Búsqueda en tiempo real de usuarios inscritos en la tabla
	document.getElementById('searchUser')?.addEventListener('keyup', function () {
		const searchTerm = this.value.toLowerCase();
		const rows = document.querySelectorAll('table tbody tr');

		rows.forEach(row => {
			const text = row.textContent.toLowerCase();
			row.style.display = text.includes(searchTerm) ? '' : 'none';
		});
	});

	// Funcionalidad del dropdown con checkboxes
	document.addEventListener('DOMContentLoaded', function () {
		const selectAllCheckbox = document.getElementById('selectAll');
		const userCheckboxes = document.querySelectorAll('.user-checkbox');
		const selectedCount = document.getElementById('selectedCount');
		const btnAgregar = document.getElementById('btnAgregar');
		const dropdownText = document.getElementById('dropdownText');
		const searchInDropdown = document.getElementById('searchInDropdown');
		const cuposDisponibles = <?php echo $cuposInfo['disponibles']; ?>;

		// Función para actualizar el texto del dropdown y contador
		// Función para actualizar el contador (sin cambiar el texto del dropdown)
		function updateDropdownText() {
			const checkedBoxes = document.querySelectorAll('.user-checkbox:checked');
			const count = checkedBoxes.length;

			selectedCount.textContent = count;

			// Mantener siempre el mismo texto en el dropdown
			dropdownText.textContent = 'Seleccionar usuarios...';

			if (count === 0) {
				selectedCount.classList.remove('text-danger', 'text-success');
				btnAgregar.disabled = false;
				btnAgregar.textContent = 'Agregar Usuarios Seleccionados';
				btnAgregar.classList.add('btn-success');
				btnAgregar.classList.remove('btn-secondary');
			} else {
				// Validar cupos
				if (count > cuposDisponibles) {
					selectedCount.classList.add('text-danger');
					selectedCount.classList.remove('text-success');
					btnAgregar.disabled = true;
					btnAgregar.textContent = 'Excede cupos disponibles (' + cuposDisponibles + ')';
					btnAgregar.classList.add('btn-secondary');
					btnAgregar.classList.remove('btn-success');
				} else {
					selectedCount.classList.add('text-success');
					selectedCount.classList.remove('text-danger');
					btnAgregar.disabled = false;
					btnAgregar.textContent = 'Agregar ' + count + ' Usuario(s)';
					btnAgregar.classList.add('btn-success');
					btnAgregar.classList.remove('btn-secondary');
				}
			}
		}

		// Seleccionar/deseleccionar todos
		selectAllCheckbox?.addEventListener('change', function (e) {
			e.stopPropagation();
			const visibleCheckboxes = document.querySelectorAll('.user-item:not([style*="display: none"]) .user-checkbox');
			visibleCheckboxes.forEach(checkbox => {
				checkbox.checked = this.checked;
			});
			updateDropdownText();
		});

		// Actualizar cuando se marca un checkbox individual
		userCheckboxes.forEach(checkbox => {
			checkbox.addEventListener('change', function (e) {
				e.stopPropagation();
				updateDropdownText();

				// Si se desmarca uno, desmarcar "Seleccionar Todos"
				if (!this.checked) {
					selectAllCheckbox.checked = false;
				}

				// Si todos están marcados, marcar "Seleccionar Todos"
				const visibleCheckboxes = document.querySelectorAll('.user-item:not([style*="display: none"]) .user-checkbox');
				const allChecked = Array.from(visibleCheckboxes).every(cb => cb.checked);
				if (allChecked && visibleCheckboxes.length > 0) {
					selectAllCheckbox.checked = true;
				}
			});
		});

		// Búsqueda dentro del dropdown
		searchInDropdown?.addEventListener('input', function (e) {
			e.stopPropagation();
			const searchTerm = this.value.toLowerCase();
			const userItems = document.querySelectorAll('.user-item');

			userItems.forEach(item => {
				const userName = item.getAttribute('data-name');
				if (userName.includes(searchTerm)) {
					item.style.display = '';
				} else {
					item.style.display = 'none';
				}
			});
		});

		// Prevenir que el dropdown se cierre al hacer click dentro
		document.querySelector('.dropdown-menu')?.addEventListener('click', function (e) {
			e.stopPropagation();
		});

		// Validar antes de enviar
		document.getElementById('formAgregarUsuarios')?.addEventListener('submit', function (e) {
			const checked = document.querySelectorAll('.user-checkbox:checked').length;

			if (checked === 0) {
				e.preventDefault();
				alert('Debes seleccionar al menos un usuario');
				return false;
			}

			if (checked > cuposDisponibles) {
				e.preventDefault();
				alert('Seleccionaste ' + checked + ' usuarios pero solo hay ' + cuposDisponibles + ' cupos disponibles');
				return false;
			}
		});

		// Inicializar texto
		updateDropdownText();
	});
</script>