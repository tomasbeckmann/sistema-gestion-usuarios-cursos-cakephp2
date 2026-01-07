<div class="container mt-4">
	<div class="row justify-content-center">
		<div class="col-md-6">
			<div class="card">
				<div class="card-header">
					<h3>Cambiar Contraseña</h3>
				</div>
				<div class="card-body">
					<div class="alert alert-info">
						<strong>Usuario:</strong>
						<?php echo h($user['User']['nombre'] . ' ' . $user['User']['apellido']); ?><br>
						<strong>Email:</strong>
						<?php echo h($user['User']['email']); ?>
					</div>

					<?php echo $this->Form->create('User', array('class' => 'form-horizontal')); ?>

					<div class="mb-3">
						<?php echo $this->Form->input('password', array(
							'label' => 'Nueva Contraseña',
							'type' => 'password',
							'class' => 'form-control',
							'required' => true,
							'div' => false,
							'value' => ''
						)); ?>
					</div>

					<div class="mb-3">
						<?php echo $this->Form->input('password_confirm', array(
							'label' => 'Confirmar Contraseña',
							'type' => 'password',
							'class' => 'form-control',
							'required' => true,
							'div' => false
						)); ?>
						<small class="text-muted">Ingresa la misma contraseña para confirmar</small>
					</div>

					<div class="d-flex justify-content-between">
						<?php echo $this->Html->link('Cancelar', array('action' => 'admin_dashboard'), array('class' => 'btn btn-secondary')); ?>
						<?php echo $this->Form->submit('Cambiar Contraseña', array('class' => 'btn btn-primary', 'div' => false)); ?>
					</div>

					<?php echo $this->Form->end(); ?>
				</div>
			</div>
		</div>
	</div>
</div>
