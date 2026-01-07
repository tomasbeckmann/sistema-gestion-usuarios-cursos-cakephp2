<div class="container mt-4">
	<div class="row justify-content-center">
		<div class="col-md-8">
			<div class="card">
				<div class="card-header">
					<h3>Agregar Usuario</h3>
				</div>
				<div class="card-body">
					<?php echo $this->Form->create('User', array('class' => 'form-horizontal')); ?>

					<div class="mb-3">
						<?php echo $this->Form->input('nombre', array(
							'label' => 'Nombre',
							'class' => 'form-control',
							'required' => true,
							'div' => false
						)); ?>
					</div>

					<div class="mb-3">
						<?php echo $this->Form->input('apellido', array(
							'label' => 'Apellido',
							'class' => 'form-control',
							'required' => true,
							'div' => false
						)); ?>
					</div>

					<div class="mb-3">
						<?php echo $this->Form->input('email', array(
							'label' => 'Email',
							'type' => 'email',
							'class' => 'form-control',
							'required' => true,
							'div' => false
						)); ?>
					</div>

					<div class="mb-3">
						<?php echo $this->Form->input('password', array(
							'label' => 'Contraseña',
							'type' => 'password',
							'class' => 'form-control',
							'required' => true,
							'div' => false
						)); ?>
					</div>

					<div class="mb-3">
						<?php echo $this->Form->input('role', array(
							'label' => 'Rol',
							'class' => 'form-control',
							'options' => array('user' => 'Usuario', 'admin' => 'Administrador'),
							'required' => true,
							'div' => false
						)); ?>
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
						<?php echo $this->Html->link('Cancelar', array('action' => 'admin_dashboard'), array('class' => 'btn btn-secondary')); ?>
						<?php echo $this->Form->submit('Guardar Usuario', array('class' => 'btn btn-success', 'div' => false)); ?>
					</div>

					<?php echo $this->Form->end(); ?>
				</div>
			</div>
		</div>
	</div>
</div>
