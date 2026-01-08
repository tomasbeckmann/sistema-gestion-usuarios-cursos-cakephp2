<div class="container-fluid mt-4">
	<h2 class="mb-4">📋 Historial de Cambios</h2>

	<div class="card mb-4">
		<div class="card-body">
			<?php echo $this->Form->create('AuditLog', array('type' => 'get', 'class' => 'row g-3')); ?>

			<div class="col-md-4">
				<?php echo $this->Form->input('user_id', array(
					'label' => 'Administrador',
					'class' => 'form-control',
					'empty' => 'Todos',
					'options' => $users,
					'div' => false,
					'id' => 'AuditLogUser',
					'value' => isset($this->request->query['user_id']) ? $this->request->query['user_id'] : ''
				)); ?>
			</div>

			<div class="col-md-3">
				<?php echo $this->Form->input('action', array(
					'label' => 'Acción',
					'class' => 'form-control',
					'empty' => 'Todas',
					'options' => array(
						'create' => 'Crear',
						'update' => 'Editar',
						'delete' => 'Eliminar',
						'activate' => 'Activar',
						'deactivate' => 'Desactivar',
						'assign_users' => 'Asignar usuarios',
						'remove_user' => 'Remover usuario'
					),
					'div' => false,
					'id' => 'AuditLogAction',
					'value' => isset($this->request->query['action']) ? $this->request->query['action'] : ''
				)); ?>
			</div>

			<div class="col-md-3">
				<?php echo $this->Form->input('model', array(
					'label' => 'Módulo',
					'class' => 'form-control',
					'empty' => 'Todos',
					'options' => array(
						'User' => 'Usuarios',
						'Course' => 'Cursos'
					),
					'div' => false,
					'id' => 'AuditLogModel',
					'value' => isset($this->request->query['model']) ? $this->request->query['model'] : ''
				)); ?>
			</div>

			<div class="col-md-2 d-flex align-items-end">
				<?php echo $this->Html->link(
					'🔄 Limpiar',
					array('action' => 'admin_logs'),
					array('class' => 'btn btn-secondary w-100')
				); ?>
			</div>

			<?php echo $this->Form->end(); ?>
		</div>
	</div>

	<div class="card">
		<div class="card-body">
			<table class="table table-striped table-hover">
				<thead>
					<tr>
						<th>Fecha/Hora</th>
						<th>Administrador</th>
						<th>Acción</th>
						<th>Módulo</th>
						<th>Descripción</th>
						<th>IP</th>
					</tr>
				</thead>
				<tbody>
					<?php if (empty($logs)): ?>
						<tr>
							<td colspan="6" class="text-center">No hay registros</td>
						</tr>
					<?php else: ?>
						<?php foreach ($logs as $log): ?>
							<?php

							$badgeClass = 'bg-secondary';
							$icon = '📝';

							switch ($log['AuditLog']['action']) {
								case 'create':
									$badgeClass = 'bg-success';
									$icon = '➕';
									$actionText = 'Crear';
									break;
								case 'update':
									$badgeClass = 'bg-primary';
									$icon = '✏️';
									$actionText = 'Editar';
									break;
								case 'delete':
									$badgeClass = 'bg-danger';
									$icon = '🗑️';
									$actionText = 'Eliminar';
									break;
								case 'activate':
									$badgeClass = 'bg-success';
									$icon = '✅';
									$actionText = 'Activar';
									break;
								case 'deactivate':
									$badgeClass = 'bg-warning';
									$icon = '⏸️';
									$actionText = 'Desactivar';
									break;
								case 'assign_users':
									$badgeClass = 'bg-info';
									$icon = '👥';
									$actionText = 'Asignar';
									break;
								case 'remove_user':
									$badgeClass = 'bg-warning';
									$icon = '👤';
									$actionText = 'Remover';
									break;
								default:
									$actionText = $log['AuditLog']['action'];
							}
							?>
							<tr>
								<td>
									<small>
										<?php echo date('d/m/Y', strtotime($log['AuditLog']['created'])); ?>
										<br>
										<strong><?php echo date('H:i:s', strtotime($log['AuditLog']['created'])); ?></strong>
									</small>
								</td>
								<td><?php echo h($log['User']['email']); ?></td>
								<td>
									<span class="badge <?php echo $badgeClass; ?>">
										<?php echo $icon . ' ' . $actionText; ?>
									</span>
								</td>
								<td>
									<span class="badge bg-dark">
										<?php echo $log['AuditLog']['model'] == 'User' ? '👥 Usuarios' : '📖 Cursos'; ?>
									</span>
								</td>
								<td><?php echo h($log['AuditLog']['description']); ?></td>
								<td><small class="text-muted"><?php echo h($log['AuditLog']['ip_address']); ?></small></td>
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
