<?php
App::uses('AppModel', 'Model');

class AuditLog extends AppModel
{
	public $displayField = 'description';

	public $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id'
		)
	);

	public function logAction($userId, $action, $model, $modelId, $description, $ipAddress = null)
	{
		$this->create();

		$data = array(
			'user_id' => $userId,
			'action' => $action,
			'model' => $model,
			'model_id' => $modelId,
			'description' => $description,
			'ip_address' => $ipAddress,
			'created' => date('Y-m-d H:i:s')
		);

		return $this->save($data);
	}

	public function getRecentLogs($limit = 50, $conditions = array())
	{
		return $this->find('all', array(
			'conditions' => $conditions,
			'contain' => array('User'),
			'order' => array('AuditLog.created' => 'DESC'),
			'limit' => $limit
		));
	}
}
?>