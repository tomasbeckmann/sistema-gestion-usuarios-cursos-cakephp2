<div class="container mt-4">
	<div class="row">
		<div class="col-md-12">
			<div class="d-flex justify-content-between align-items-center mb-4">
				<div>
					<h2>Mis Cursos</h2>
					<p class="text-muted mb-0">Bienvenido,
						<?php echo h($user['User']['nombre'] . ' ' . $user['User']['apellido']); ?>
					</p>
				</div>
				<div>
					<?php echo $this->Html->link('Cerrar Sesión', array('action' => 'logout'), array('class' => 'btn btn-danger btn-sm')); ?>
				</div>
			</div>

			<?php if (empty($courses)): ?>

				<div class="card">
					<div class="card-body text-center py-5">
						<h4 class="text-muted">📚 No estás inscrito en ningún curso</h4>
						<p class="text-muted">Contacta al administrador para que te agregue a un curso.</p>
					</div>
				</div>
			<?php else: ?>

				<div class="row">
					<?php foreach ($courses as $course): ?>
						<?php
						
						$totalCompaneros = 0;
						if (isset($course['User'])) {
							foreach ($course['User'] as $compañero) {
								if ($compañero['id'] != $user['User']['id']) {
									$totalCompaneros++;
								}
							}
						}
						?>
						<div class="col-md-6 mb-4">
							<div class="card h-100 shadow-sm">
								<div class="card-header bg-primary text-white">
									<h5 class="mb-0">📖 <?php echo h($course['nombre']); ?></h5>
								</div>
								<div class="card-body">
									<p class="card-text">
										<?php
										if (!empty($course['descripcion'])) {
											echo h(strlen($course['descripcion']) > 150
												? substr($course['descripcion'], 0, 150) . '...'
												: $course['descripcion']);
										} else {
											echo '<span class="text-muted">Sin descripción</span>';
										}
										?>
									</p>

									<hr>

									<div class="row text-center mb-3">
										<div class="col-6">
											<small class="text-muted">Fecha de Inicio</small>
											<p class="mb-0 fw-bold">
												<?php echo h(date('d/m/Y', strtotime($course['fecha_inicio']))); ?>
											</p>
										</div>
										<div class="col-6">
											<small class="text-muted">Fecha de Fin</small>
											<p class="mb-0 fw-bold">
												<?php echo h(date('d/m/Y', strtotime($course['fecha_fin']))); ?>
											</p>
										</div>
									</div>

									<div class="alert alert-info mb-3">
										<small>
											<strong>👥 Compañeros de curso:</strong> <?php echo $totalCompaneros; ?>
											| <strong>Total inscritos:</strong>
											<?php echo isset($course['User']) ? count($course['User']) : 1; ?>
										</small>
									</div>

									<?php echo $this->Html->link(
										'Ver Detalles y Compañeros',
										array('action' => 'view_course', $course['id']),
										array('class' => 'btn btn-primary w-100')
									); ?>
								</div>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>
