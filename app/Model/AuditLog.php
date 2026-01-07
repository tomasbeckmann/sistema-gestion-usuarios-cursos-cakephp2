<?php
App::uses('AppModel', 'Model');

class AuditLog extends AppModel {

    public $displayField = 'description';

    // Relación con User
    public $belongsTo = array(
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id'
        )
    );

    /**
     * Registrar una acción en el log
     *
     * @param int $userId ID del usuario que realiza la acción
     * @param string $action Tipo de acción (create, update, delete, activate, deactivate, etc.)
     * @param string $model Modelo afectado (User, Course)
     * @param int $modelId ID del registro afectado
     * @param string $description Descripción detallada
     * @param string $ipAddress IP del usuario
     * @return bool
     */
    public function logAction($userId, $action, $model, $modelId, $description, $ipAddress = null) {
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

    /**
     * Obtener los últimos logs
     *
     * @param int $limit Número de registros a obtener
     * @param array $conditions Condiciones adicionales
     * @return array
     */
    public function getRecentLogs($limit = 50, $conditions = array()) {
        return $this->find('all', array(
            'conditions' => $conditions,
            'contain' => array('User'),
            'order' => array('AuditLog.created' => 'DESC'),
            'limit' => $limit
        ));
    }
}
?>
