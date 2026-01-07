<?php
App::uses('Controller', 'Controller');

class AppController extends Controller
{

	public $components = array(
		'Flash',
		'Auth' => array(
			'loginRedirect' => '/admin/dashboard',  // ← CAMBIAR
			'logoutRedirect' => array('controller' => 'users', 'action' => 'login'),
			'authenticate' => array(
				'Form' => array(
					'passwordHasher' => 'Blowfish',
					'fields' => array('username' => 'email')
				)
			),
			'authorize' => array('Controller'),
			'authError' => 'No tienes permisos para acceder a esta sección',
			'loginAction' => array('controller' => 'users', 'action' => 'login')
		),
		'AuditLog'
	);

	public function beforeFilter()
	{
		// Configuración general del Auth
		$this->Auth->allow(); // Temporalmente permitir todo para evitar loops
	}

	public function isAuthorized($user)
	{
		// Por defecto, todos los usuarios autenticados tienen acceso
		return true;
	}
}
?>