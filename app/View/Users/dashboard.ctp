<div class="container-fluid mt-4">
	<h2 class="mb-4">📊 Dashboard de Administración</h2>

	<!-- ESTADÍSTICAS PRINCIPALES -->
	<div class="row mb-4">
		<!-- Tarjeta: Total Usuarios -->
		<div class="col-lg-4 col-md-6 mb-3">
			<div class="card text-white bg-primary h-100">
				<div class="card-body">
					<div class="d-flex justify-content-between align-items-center">
						<div>
							<h6 class="card-title text-white-50">Total Usuarios</h6>
							<h2 class="mb-0"><?php echo $stats['totalUsuarios']; ?></h2>
							<small>
								<i class="bi bi-check-circle"></i> <?php echo $stats['usuariosActivos']; ?> activos
								<br>
								<i class="bi bi-x-circle"></i> <?php echo $stats['usuariosInactivos']; ?> inactivos
							</small>
						</div>
						<div>
							<i class="bi bi-people" style="font-size: 3rem; opacity: 0.3;"></i>
						</div>
					</div>
				</div>
				<div class="card-footer bg-primary bg-opacity-25">
					<a href="/admin/users" class="text-white text-decoration-none">Ver usuarios →</a>
				</div>
			</div>
		</div>

		<!-- Tarjeta: Total Cursos -->
		<div class="col-lg-4 col-md-6 mb-3">
			<div class="card text-white bg-success h-100">
				<div class="card-body">
					<div class="d-flex justify-content-between align-items-center">
						<div>
							<h6 class="card-title text-white-50">Total Cursos</h6>
							<h2 class="mb-0"><?php echo $stats['totalCursos']; ?></h2>
							<small>
								<i class="bi bi-check-circle"></i> <?php echo $stats['cursosActivos']; ?> activos
							</small>
						</div>
						<div>
							<i class="bi bi-book" style="font-size: 3rem; opacity: 0.3;"></i>
						</div>
					</div>
				</div>
				<div class="card-footer bg-success bg-opacity-25">
					<a href="/admin/courses" class="text-white text-decoration-none">Ver cursos →</a>
				</div>
			</div>
		</div>


		<!-- Tarjeta: Usuarios Sin Cursos -->
		<div class="col-lg-4 col-md-6 mb-3">
			<div class="card text-white bg-warning h-100">
				<div class="card-body">
					<div class="d-flex justify-content-between align-items-center">
						<div>
							<h6 class="card-title text-white-50">Sin Asignar</h6>
							<h2 class="mb-0"><?php echo $stats['usuariosSinCursos']; ?></h2>
							<small>Usuarios sin cursos</small>
						</div>
						<div>
							<i class="bi bi-exclamation-triangle" style="font-size: 3rem; opacity: 0.3;"></i>
						</div>
					</div>
				</div>
				<div class="card-footer bg-warning bg-opacity-25">
					<small class="text-white">Requieren asignación</small>
				</div>
			</div>
		</div>
	</div>

	<!-- ESTADO DE CURSOS -->
	<div class="row mb-4">
		<div class="col-lg-4 col-md-4 mb-3">
			<div class="card border-primary">
				<div class="card-body text-center">
					<h3 class="text-primary"><?php echo $stats['cursosProximos']; ?></h3>
					<p class="mb-0">📅 Cursos Próximos</p>
					<small class="text-muted">Por iniciar</small>
				</div>
			</div>
		</div>
		<div class="col-lg-4 col-md-4 mb-3">
			<div class="card border-success">
				<div class="card-body text-center">
					<h3 class="text-success"><?php echo $stats['cursosEnCurso']; ?></h3>
					<p class="mb-0">▶️ Cursos en Curso</p>
					<small class="text-muted">Actualmente activos</small>
				</div>
			</div>
		</div>
		<div class="col-lg-4 col-md-4 mb-3">
			<div class="card border-secondary">
				<div class="card-body text-center">
					<h3 class="text-secondary"><?php echo $stats['cursosFinalizados']; ?></h3>
					<p class="mb-0">✓ Cursos Finalizados</p>
					<small class="text-muted">Completados</small>
				</div>
			</div>
		</div>
	</div>

	<!-- GRÁFICOS -->
	<div class="row">
		<!-- Gráfico de Inscripciones -->
		<div class="mb-4">
			<div class="card">
				<div class="card-header bg-white">
					<h5 class="mb-0">📊 Ocupación de Cursos</h5>
				</div>
				<div class="card-body">
					<canvas id="cursosChart" style="max-height: 400px;"></canvas>
				</div>
			</div>
		</div>

	</div>


</div>

<!-- Scripts para Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

<script>
	document.addEventListener('DOMContentLoaded', function () {
		// ===== GRÁFICO DE BARRAS: OCUPACIÓN DE CURSOS =====
		const cursosData = <?php echo json_encode($stats['cursosData']); ?>;

		const labels = cursosData.map(c => c.nombre);
		const inscritos = cursosData.map(c => c.inscritos);
		const cupoMaximo = cursosData.map(c => c.cupo_maximo);

		const ctx1 = document.getElementById('cursosChart').getContext('2d');
		new Chart(ctx1, {
			type: 'bar',
			data: {
				labels: labels,
				datasets: [
					{
						label: 'Inscritos',
						data: inscritos,
						backgroundColor: 'rgba(54, 162, 235, 0.7)',
						borderColor: 'rgba(54, 162, 235, 1)',
						borderWidth: 2
					},
					{
						label: 'Cupo Máximo',
						data: cupoMaximo,
						backgroundColor: 'rgba(255, 99, 132, 0.3)',
						borderColor: 'rgba(255, 99, 132, 1)',
						borderWidth: 2,
						borderDash: [5, 5],
						type: 'line'
					}
				]
			},
			options: {
				responsive: true,
				maintainAspectRatio: true,
				plugins: {
					legend: {
						display: true,
						position: 'top'
					}
				},
				scales: {
					y: {
						beginAtZero: true,
						ticks: {
							stepSize: 5
						}
					}
				}
			}
		});

		// ===== GRÁFICO DE PASTEL: DISTRIBUCIÓN POR ROL =====
		const ctx2 = document.getElementById('rolesChart').getContext('2d');
		new Chart(ctx2, {
			type: 'doughnut',
			data: {
				labels: ['Administradores', 'Usuarios'],
				datasets: [{
					data: [<?php echo $stats['admins']; ?>, <?php echo $stats['usuarios']; ?>],
					backgroundColor: [
						'rgba(220, 53, 69, 0.7)',
						'rgba(13, 110, 253, 0.7)'
					],
					borderColor: [
						'rgba(220, 53, 69, 1)',
						'rgba(13, 110, 253, 1)'
					],
					borderWidth: 2
				}]
			},
			options: {
				responsive: true,
				maintainAspectRatio: true,
				plugins: {
					legend: {
						position: 'bottom'
					}
				}
			}
		});
	});
</script>