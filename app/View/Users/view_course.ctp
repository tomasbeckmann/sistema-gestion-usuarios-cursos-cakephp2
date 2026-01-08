<div class="container mt-4">
	<div class="row">
		<div class="col-md-12">
			<div class="mb-4">
				<?php echo $this->Html->link('← Volver a Mis Cursos', array('action' => 'index'), array('class' => 'btn btn-secondary mb-3')); ?>
			</div>


			<div class="card mb-4">
				<div class="card-header bg-primary text-white">
					<h3 class="mb-0">📖 <?php echo h($course['Course']['nombre']); ?></h3>
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-md-8">
							<h5>Descripción del Curso</h5>
							<p><?php echo h($course['Course']['descripcion']); ?></p>
						</div>
						<div class="col-md-4">
							<h5>Información</h5>
							<p>
								<strong>📅 Fecha de Inicio:</strong><br>
								<?php echo h(date('d/m/Y', strtotime($course['Course']['fecha_inicio']))); ?>
							</p>
							<p>
								<strong>📅 Fecha de Fin:</strong><br>
								<?php echo h(date('d/m/Y', strtotime($course['Course']['fecha_fin']))); ?>
							</p>
							<p>
								<strong>👥 Total de Estudiantes:</strong><br>
								<?php echo count($course['User']); ?> /
								<?php echo h($course['Course']['cupo_maximo']); ?>
							</p>
						</div>
					</div>
				</div>
			</div>


			<div class="card">
				<div class="card-header">
					<div class="d-flex justify-content-between align-items-center">
						<h5 class="mb-0">👥 Compañeros de Curso (<?php echo count($course['User']) - 1; ?>)</h5>
						<input type="text" id="searchCompanero" class="form-control form-control-sm"
							style="max-width: 300px;" placeholder="Buscar compañero...">
					</div>
				</div>
				<div class="card-body">
					<?php if (count($course['User']) <= 1): ?>
						<p class="text-muted text-center">Aún no hay otros estudiantes inscritos en este curso.</p>
					<?php else: ?>
						<div class="row" id="companerosList">
							<?php foreach ($course['User'] as $companero): ?>
								<?php if ($companero['id'] != $currentUserId): ?>
									<div class="col-md-6 col-lg-4 mb-3 companero-item"
										data-name="<?php echo strtolower($companero['nombre'] . ' ' . $companero['apellido'] . ' ' . $companero['email']); ?>">
										<div class="card">
											<div class="card-body">
												<h6 class="card-title mb-1">
													<?php echo h($companero['nombre'] . ' ' . $companero['apellido']); ?>
												</h6>
												<p class="card-text mb-0">
													<small class="text-muted">
														✉️ <?php echo h($companero['email']); ?>
													</small>
												</p>
											</div>
										</div>
									</div>
								<?php endif; ?>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</div>

<script>

	document.getElementById('searchCompanero')?.addEventListener('input', function () {
		const searchTerm = this.value.toLowerCase();
		const companeros = document.querySelectorAll('.companero-item');

		companeros.forEach(companero => {
			const name = companero.getAttribute('data-name');
			if (name.includes(searchTerm)) {
				companero.style.display = '';
			} else {
				companero.style.display = 'none';
			}
		});
	});
</script>