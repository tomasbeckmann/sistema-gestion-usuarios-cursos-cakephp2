<?php
App::uses('AppModel', 'Model');
App::uses('BlowfishPasswordHasher', 'Controller/Component/Auth');

class User extends AppModel
{

	public $validate = array(
		'email' => array(
			'required' => array(
				'rule' => 'notBlank',
				'message' => 'El email es requerido'
			),
			'email' => array(
				'rule' => 'email',
				'message' => 'Ingrese un email válido'
			),
			'unique' => array(
				'rule' => 'isUnique',
				'message' => 'Este email ya está registrado'
			)
		),
		'password' => array(
			'required' => array(
				'rule' => 'notBlank',
				'message' => 'La contraseña es requerida'
			)
		)
	);


	public $hasAndBelongsToMany = array(
		'Course' => array(
			'className' => 'Course',
			'joinTable' => 'courses_users',
			'foreignKey' => 'user_id',
			'associationForeignKey' => 'course_id',
			'unique' => true
		)
	);

	public function beforeSave($options = array())
	{
		if (isset($this->data[$this->alias]['password'])) {
			$passwordHasher = new BlowfishPasswordHasher();
			$this->data[$this->alias]['password'] = $passwordHasher->hash(
				$this->data[$this->alias]['password']
			);
		}
		return true;
	}
}
?>