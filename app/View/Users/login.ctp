<div class="container">
    <div class="row justify-content-center mt-5">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="text-center">Iniciar Sesión</h3>
                </div>
                <div class="card-body">
                    <?php echo $this->Flash->render(); ?>

                    <?php echo $this->Form->create('User', array(
                        'class' => 'form-horizontal'
                    )); ?>

                    <div class="form-group mb-3">
                        <?php echo $this->Form->input('email', array(
                            'label' => 'Email',
                            'class' => 'form-control',
                            'placeholder' => 'Ingrese su email',
                            'div' => false
                        )); ?>
                    </div>

                    <div class="form-group mb-3">
                        <?php echo $this->Form->input('password', array(
                            'label' => 'Contraseña',
                            'type' => 'password',
                            'class' => 'form-control',
                            'placeholder' => 'Ingrese su contraseña',
                            'div' => false
                        )); ?>
                    </div>

                    <div class="form-group">
                        <?php echo $this->Form->submit('Ingresar', array(
                            'class' => 'btn btn-primary btn-block w-100'
                        )); ?>
                    </div>

                    <?php echo $this->Form->end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
