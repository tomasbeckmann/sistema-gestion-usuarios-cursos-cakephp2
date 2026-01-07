<div class="container mt-4">
	<div class="row justify-content-center">
		<div class="col-md-8">
			<div class="card">
				<div class="card-header">
					<h3>Agregar Curso</h3>
				</div>
				<div class="card-body">
					<?php echo $this->Form->create('Course', array('class' => 'form-horizontal')); ?>

					<div class="mb-3">
						<?php echo $this->Form->input('nombre', array(
							'label' => 'Nombre del Curso',
							'class' => 'form-control',
							'required' => true,
							'div' => false
						)); ?>
					</div>

					<div class="mb-3">
						<?php echo $this->Form->input('descripcion', array(
							'label' => 'Descripción',
							'type' => 'textarea',
							'class' => 'form-control',
							'rows' => 4,
							'div' => false
						)); ?>
					</div>

					<div class="row">
						<div class="col-md-6 mb-3">
							<label>Fecha de Inicio <span class="text-danger">*</span></label>
							<div class="d-flex gap-2">
								<?php
								$fechaInicioInputs = $this->Form->input('fecha_inicio', array(
									'label' => false,
									'type' => 'date',
									'dateFormat' => 'DMY',
									'minYear' => date('Y'),
									'maxYear' => date('Y') + 5,
									'monthNames' => array(
										'01' => 'Enero',
										'02' => 'Febrero',
										'03' => 'Marzo',
										'04' => 'Abril',
										'05' => 'Mayo',
										'06' => 'Junio',
										'07' => 'Julio',
										'08' => 'Agosto',
										'09' => 'Septiembre',
										'10' => 'Octubre',
										'11' => 'Noviembre',
										'12' => 'Diciembre'
									),
									'separator' => '</div><div style="flex: 1;">',
									'div' => false,
									'before' => '<div style="flex: 1;">',
									'after' => '</div>',
									'class' => 'form-select'
								));
								echo $fechaInicioInputs;
								?>
							</div>
						</div>

						<div class="col-md-6 mb-3">
							<label>Fecha de Fin <span class="text-danger">*</span></label>
							<div class="d-flex gap-2">
								<?php
								$fechaFinInputs = $this->Form->input('fecha_fin', array(
									'label' => false,
									'type' => 'date',
									'dateFormat' => 'DMY',
									'minYear' => date('Y'),
									'maxYear' => date('Y') + 5,
									'monthNames' => array(
										'01' => 'Enero',
										'02' => 'Febrero',
										'03' => 'Marzo',
										'04' => 'Abril',
										'05' => 'Mayo',
										'06' => 'Junio',
										'07' => 'Julio',
										'08' => 'Agosto',
										'09' => 'Septiembre',
										'10' => 'Octubre',
										'11' => 'Noviembre',
										'12' => 'Diciembre'
									),
									'separator' => '</div><div style="flex: 1;">',
									'div' => false,
									'before' => '<div style="flex: 1;">',
									'after' => '</div>',
									'class' => 'form-select'
								));
								echo $fechaFinInputs;
								?>
							</div>
						</div>
					</div>

					<div class="mb-3">
						<?php echo $this->Form->input('cupo_maximo', array(
							'label' => 'Cupo Máximo',
							'type' => 'number',
							'class' => 'form-control',
							'default' => 50,
							'min' => 1,
							'required' => true,
							'div' => false
						)); ?>
						<small class="text-muted">Número máximo de estudiantes que pueden inscribirse</small>
					</div>

					<div class="mb-3">
						<?php echo $this->Form->input('active', array(
							'label' => 'Estado',
							'class' => 'form-control',
							'options' => array('1' => 'Activo', '0' => 'Inactivo'),
							'default' => '1',
							'div' => false
						)); ?>
					</div>

					<div class="d-flex justify-content-between">
						<?php echo $this->Html->link('Cancelar', array('action' => 'admin_index'), array('class' => 'btn btn-secondary')); ?>
						<?php echo $this->Form->submit('Guardar Curso', array('class' => 'btn btn-success', 'div' => false)); ?>
					</div>

					<?php echo $this->Form->end(); ?>
				</div>
			</div>
		</div>
	</div>
</div>