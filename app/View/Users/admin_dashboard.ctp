<div class="container mt-4">
	<div class="row">
		<div class="col-md-12">

			<h2 class="mb-4">Gestión de Usuarios</h2>

			<div class="card mb-4">
				<div class="card-body">
					<?php echo $this->Form->create('User', array('type' => 'get', 'class' => 'row g-3')); ?>

					<div class="col-md-4">
						<?php echo $this->Form->input('search', array(
							'label' => 'Buscar (nombre, apellido, email)',
							'class' => 'form-control',
							'placeholder' => 'Buscar...',
							'div' => false,
							'id' => 'UserSearch',
							'value' => isset($this->request->query['search']) ? $this->request->query['search'] : ''
						)); ?>
					</div>

					<div class="col-md-3">
						<?php echo $this->Form->input('role', array(
							'label' => 'Filtrar por Rol',
							'class' => 'form-control',
							'empty' => 'Todos los roles',
							'options' => array('admin' => 'Administrador', 'user' => 'Usuario'),
							'div' => false,
							'id' => 'UserRole',
							'value' => isset($this->request->query['role']) ? $this->request->query['role'] : ''
						)); ?>
					</div>

					<div class="col-md-3">
						<?php echo $this->Form->input('active', array(
							'label' => 'Filtrar por Estado',
							'class' => 'form-control',
							'empty' => 'Todos los estados',
							'options' => array('1' => 'Activos', '0' => 'Inactivos'),
							'div' => false,
							'id' => 'UserActive',
							'value' => isset($this->request->query['active']) ? $this->request->query['active'] : ''
						)); ?>
					</div>

					<div class="col-md-2 d-flex align-items-end">
						<?php echo $this->Html->link(
							'🔄 Limpiar',
							array('action' => 'admin_dashboard'),
							array('class' => 'btn btn-secondary w-100')
						); ?>
					</div>

				</div>
			</div>


			<div class="mb-3 d-flex justify-content-between">
				<div>
					<?php echo $this->Html->link('+ Agregar Usuario', array('action' => 'admin_add'), array('class' => 'btn btn-success')); ?>
				</div>
				<div>
					<?php echo $this->Html->link(
						'📥 Exportar Usuarios a CSV',
						array('action' => 'export_users'),
						array('class' => 'btn btn-outline-primary', 'escape' => false)
					); ?>
				</div>
			</div>


			<div class="card">
				<div class="card-body">
					<table class="table table-striped table-hover">
						<thead>
							<tr>
								<th>ID</th>
								<th>Nombre</th>
								<th>Apellido</th>
								<th>Email</th>
								<th>Rol</th>
								<th>Estado</th>
								<th>Acciones</th>
							</tr>
						</thead>
						<tbody>
							<?php if (empty($users)): ?>
								<tr>
									<td colspan="7" class="text-center">No se encontraron usuarios</td>
								</tr>
							<?php else: ?>
								<?php foreach ($users as $user): ?>
									<tr>
										<td><?php echo h($user['User']['id']); ?></td>
										<td><?php echo h($user['User']['nombre']); ?></td>
										<td><?php echo h($user['User']['apellido']); ?></td>
										<td><?php echo h($user['User']['email']); ?></td>
										<td>
											<?php if ($user['User']['role'] == 'admin'): ?>
												<span class="badge bg-danger">Administrador</span>
											<?php else: ?>
												<span class="badge bg-primary">Usuario</span>
											<?php endif; ?>
										</td>
										<td>
											<?php if ($user['User']['active']): ?>
												<span class="badge bg-success">Activo</span>
											<?php else: ?>
												<span class="badge bg-secondary">Inactivo</span>
											<?php endif; ?>
										</td>
										<td>
											<div class="btn-group btn-group-sm" role="group">
												<?php echo $this->Html->link('Editar', array('action' => 'admin_edit', $user['User']['id']), array('class' => 'btn btn-warning btn-sm')); ?>

												<?php echo $this->Html->link('Cambiar contraseña', array('action' => 'admin_change_password', $user['User']['id']), array('class' => 'btn btn-info btn-sm')); ?>

												<?php if ($user['User']['id'] != $currentUser['id']): ?>
													<button class="btn btn-secondary btn-sm btn-toggle-user"
														data-user-id="<?php echo $user['User']['id']; ?>"
														data-current-status="<?php echo $user['User']['active']; ?>">
														<?php echo $user['User']['active'] ? 'Desactivar' : 'Activar'; ?>
													</button>

													<?php echo $this->Form->postLink(
														'Eliminar',
														array('action' => 'admin_delete', $user['User']['id']),
														array('class' => 'btn btn-danger btn-sm', 'confirm' => '¿Estás seguro de eliminar este usuario?')
													); ?>
												<?php endif; ?>
											</div>
										</td>
									</tr>
								<?php endforeach; ?>
							<?php endif; ?>
						</tbody>
					</table>


					<?php if ($this->Paginator->hasPage(2)): ?>
						<nav>
							<ul class="pagination justify-content-center">
								<?php echo $this->Paginator->prev('« Anterior', array('tag' => 'li', 'class' => 'page-item'), null, array('tag' => 'li', 'class' => 'page-item disabled', 'disabledTag' => 'span')); ?>
								<?php echo $this->Paginator->numbers(array('separator' => '', 'tag' => 'li', 'currentClass' => 'page-item active', 'currentTag' => 'span')); ?>
								<?php echo $this->Paginator->next('Siguiente »', array('tag' => 'li', 'class' => 'page-item'), null, array('tag' => 'li', 'class' => 'page-item disabled', 'disabledTag' => 'span')); ?>
							</ul>
						</nav>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>

</div>
