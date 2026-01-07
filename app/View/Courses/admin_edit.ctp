<div class="container mt-4">
	<div class="row justify-content-center">
		<div class="col-md-8">
			<div class="card">
				<div class="card-header">
					<h3>Editar Curso</h3>
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
							<?php echo $this->Form->input('fecha_inicio', array(
								'label' => 'Fecha de Inicio',
								'type' => 'date',
								'class' => 'form-control',
								'required' => true,
								'div' => false
							)); ?>
						</div>

						<div class="col-md-6 mb-3">
							<?php echo $this->Form->input('fecha_fin', array(
								'label' => 'Fecha de Fin',
								'type' => 'date',
								'class' => 'form-control',
								'required' => true,
								'div' => false
							)); ?>
						</div>
					</div>

					<div class="mb-3">
						<?php echo $this->Form->input('cupo_maximo', array(
							'label' => 'Cupo Máximo',
							'type' => 'number',
							'class' => 'form-control',
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
							'div' => false
						)); ?>
					</div>

					<div class="d-flex justify-content-between">
						<?php echo $this->Html->link('Cancelar', array('action' => 'admin_index'), array('class' => 'btn btn-secondary')); ?>
						<?php echo $this->Form->submit('Actualizar Curso', array('class' => 'btn btn-primary', 'div' => false)); ?>
					</div>

					<?php echo $this->Form->end(); ?>
				</div>
			</div>
		</div>
	</div>
</div>