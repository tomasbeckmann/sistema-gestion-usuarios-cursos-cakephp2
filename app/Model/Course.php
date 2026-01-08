<?php
App::uses('AppModel', 'Model');

class Course extends AppModel
{

	public $displayField = 'nombre';

	public $validate = array(
		'nombre' => array(
			'required' => array(
				'rule' => 'notBlank',
				'message' => 'El nombre del curso es requerido'
			)
		),
		'fecha_inicio' => array(
			'required' => array(
				'rule' => 'notBlank',
				'message' => 'La fecha de inicio es requerida'
			),
			'date' => array(
				'rule' => 'date',
				'message' => 'Ingrese una fecha válida'
			)
		),
		'fecha_fin' => array(
			'required' => array(
				'rule' => 'notBlank',
				'message' => 'La fecha de fin es requerida'
			),
			'date' => array(
				'rule' => 'date',
				'message' => 'Ingrese una fecha válida'
			)
		),
		'cupo_maximo' => array(
			'numeric' => array(
				'rule' => 'numeric',
				'message' => 'El cupo debe ser un número'
			),
			'positive' => array(
				'rule' => array('comparison', '>', 0),
				'message' => 'El cupo debe ser mayor a 0'
			)
		)
	);


	public $hasAndBelongsToMany = array(
		'User' => array(
			'className' => 'User',
			'joinTable' => 'courses_users',
			'foreignKey' => 'course_id',
			'associationForeignKey' => 'user_id',
			'unique' => true
		)
	);


	public function getCuposDisponibles($courseId)
	{
		$course = $this->findById($courseId);
		if (!$course) {
			return 0;
		}

		$inscritos = count($course['User']);
		$disponibles = $course['Course']['cupo_maximo'] - $inscritos;

		return array(
			'inscritos' => $inscritos,
			'disponibles' => $disponibles,
			'maximo' => $course['Course']['cupo_maximo']
		);
	}


	public function hayCuposDisponibles($courseId)
	{
		$cupos = $this->getCuposDisponibles($courseId);
		return $cupos['disponibles'] > 0;
	}
}
?>