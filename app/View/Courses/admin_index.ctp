<div class="container mt-4">
	<div class="row">
		<div class="col-md-12">
			<h2 class="mb-4">Gestión de Cursos</h2>
			<!-- Búsqueda y Filtros -->
			<div class="card mb-4">
				<div class="card-body">
					<?php echo $this->Form->create('Course', array('type' => 'get', 'class' => 'row g-3')); ?>

					<div class="col-md-5">
						<?php echo $this->Form->input('search', array(
							'label' => 'Buscar curso',
							'class' => 'form-control',
							'placeholder' => 'Buscar por nombre o descripción...',
							'div' => false,
							'id' => 'CourseSearch',
							'value' => isset($this->request->query['search']) ? $this->request->query['search'] : ''
						)); ?>
					</div>

					<div class="col-md-3">
						<?php echo $this->Form->input('active', array(
							'label' => 'Estado',
							'class' => 'form-control',
							'empty' => 'Todos',
							'options' => array('1' => 'Activos', '0' => 'Inactivos'),
							'div' => false,
							'id' => 'CourseActive',
							'value' => isset($this->request->query['active']) ? $this->request->query['active'] : ''
						)); ?>
					</div>
					<div class="col-md-2 d-flex align-items-end">
						<?php echo $this->Html->link(
							'🔄 Limpiar',
							array('action' => 'admin_index'),
							array('class' => 'btn btn-secondary w-100')
						); ?>
					</div>
					<?php echo $this->Form->end(); ?>
				</div>
			</div>

			<!-- Botón Agregar Curso y Exportar -->
			<div class="mb-3 d-flex justify-content-between">
				<div>
					<?php echo $this->Html->link('+ Agregar Curso', array('action' => 'admin_add'), array('class' => 'btn btn-success')); ?>
				</div>
				<div>
					<?php echo $this->Html->link(
						'📥 Exportar Cursos a CSV',
						array('action' => 'export_courses'),
						array('class' => 'btn btn-outline-primary', 'escape' => false)
					); ?>
				</div>
			</div>
			<!-- Tabla de Cursos -->
			<div class="card">
				<div class="card-body">
					<table class="table table-striped table-hover">
						<thead>
							<tr>
								<th><?php echo $this->Paginator->sort('Course.id', 'ID'); ?></th>
								<th><?php echo $this->Paginator->sort('Course.nombre', 'Nombre'); ?></th>
								<th><?php echo $this->Paginator->sort('Course.fecha_inicio', 'Fecha Inicio'); ?></th>
								<th><?php echo $this->Paginator->sort('Course.fecha_fin', 'Fecha Fin'); ?></th>
								<th>Cupos</th>
								<th><?php echo $this->Paginator->sort('Course.active', 'Estado'); ?></th>
								<th>Acciones</th>
							</tr>
						</thead>
						<tbody>
							<?php if (empty($courses)): ?>
								<tr>
									<td colspan="7" class="text-center">No se encontraron cursos</td>
								</tr>
							<?php else: ?>
								<?php foreach ($courses as $course): ?>
									<?php
									$inscritos = count($course['User']);
									$cupoMaximo = $course['Course']['cupo_maximo'];
									$disponibles = $cupoMaximo - $inscritos;
									?>
									<tr>
										<td><?php echo h($course['Course']['id']); ?></td>
										<td>
											<strong><?php echo h($course['Course']['nombre']); ?></strong>
											<?php if (!empty($course['Course']['descripcion'])): ?>
												<br><small
													class="text-muted"><?php echo h(substr($course['Course']['descripcion'], 0, 60)) . '...'; ?></small>
											<?php endif; ?>
										</td>
										<td><?php echo h($course['Course']['fecha_inicio']); ?></td>
										<td><?php echo h($course['Course']['fecha_fin']); ?></td>
										<td>
											<span class="badge <?php echo $disponibles > 0 ? 'bg-success' : 'bg-danger'; ?>">
												<?php echo $inscritos; ?> / <?php echo $cupoMaximo; ?>
											</span>
											<br><small class="text-muted"><?php echo $disponibles; ?> disponibles</small>
										</td>
										<td>
											<?php if ($course['Course']['active']): ?>
												<span class="badge bg-success">Activo</span>
											<?php else: ?>
												<span class="badge bg-secondary">Inactivo</span>
											<?php endif; ?>
										</td>
										<td>
											<div class="btn-group btn-group-sm" role="group">
												<?php echo $this->Html->link('Ver', array('action' => 'admin_view', $course['Course']['id']), array('class' => 'btn btn-info btn-sm')); ?>

												<?php echo $this->Html->link('Editar', array('action' => 'admin_edit', $course['Course']['id']), array('class' => 'btn btn-warning btn-sm')); ?>

												<button class="btn btn-secondary btn-sm btn-toggle-course"
													data-course-id="<?php echo $course['Course']['id']; ?>"
													data-current-status="<?php echo $course['Course']['active']; ?>">
													<?php echo $course['Course']['active'] ? 'Desactivar' : 'Activar'; ?>
												</button>
												<?php echo $this->Form->postLink(
													'Eliminar',
													array('action' => 'admin_delete', $course['Course']['id']),
													array('class' => 'btn btn-danger btn-sm', 'confirm' => '¿Eliminar este curso? Se removerán todos los usuarios inscritos.')
												); ?>
											</div>
										</td>
									</tr>
								<?php endforeach; ?>
							<?php endif; ?>
						</tbody>
					</table>

					<!-- Paginación -->
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