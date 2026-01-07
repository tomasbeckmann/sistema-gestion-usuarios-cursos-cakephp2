<?php
App::uses('Component', 'Controller');

class AuditLogComponent extends Component
{

	public $components = array();
	public $controller;

	public function initialize(Controller $controller)
	{
		$this->controller = $controller;
	}

	/**
	 * Registrar una acción en el log
	 */
	public function logAction($action, $model, $modelId, $description)  // ← CAMBIAR AQUÍ
	{
		// Cargar el modelo AuditLog
		$AuditLog = ClassRegistry::init('AuditLog');

		// Obtener el usuario actual
		$userId = $this->controller->Auth->user('id');

		// Obtener la IP
		$ipAddress = $this->controller->request->clientIp();

		// Registrar el log
		return $AuditLog->logAction($userId, $action, $model, $modelId, $description, $ipAddress);
	}
}
?>