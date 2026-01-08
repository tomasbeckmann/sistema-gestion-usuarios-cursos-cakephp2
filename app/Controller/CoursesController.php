<?php
App::uses('AppController', 'Controller');

class CoursesController extends AppController
{
	public $paginate = array(
		'limit' => 10,
		'order' => array('Course.created' => 'desc')
	);

	public function beforeFilter()
	{
		parent::beforeFilter();
		if ($this->Auth->user('role') != 'admin') {
			$this->Flash->error('No tienes permisos para acceder');
			$this->redirect(array('controller' => 'users', 'action' => 'index'));
		}

		$this->layout = 'admin';
	}

	public function admin_index()
	{
		$conditions = array();

		if (!empty($this->request->query['search'])) {
			$search = $this->request->query['search'];
			$conditions['OR'] = array(
				'Course.nombre LIKE' => '%' . $search . '%',
				'Course.descripcion LIKE' => '%' . $search . '%'
			);
		}

		if (isset($this->request->query['active']) && $this->request->query['active'] !== '') {
			$conditions['Course.active'] = $this->request->query['active'];
		}

		$this->paginate['conditions'] = $conditions;
		$this->paginate['contain'] = array('User');

		$courses = $this->paginate('Course');
		$this->set('courses', $courses);
	}

	public function admin_add()
	{
		$this->layout = 'admin';

		if ($this->request->is('post')) {
			$this->Course->create();
			if ($this->Course->save($this->request->data)) {
				$courseId = $this->Course->id;
				$courseName = $this->request->data['Course']['nombre'];
				$this->AuditLog->logAction('create', 'Course', $courseId, "Creó el curso: {$courseName}");

				$this->Flash->success('✅ Curso "' . $this->request->data['Course']['nombre'] . '" creado correctamente');
				return $this->redirect(array('action' => 'admin_index'));
			}
			$this->Flash->error('No se pudo crear el curso');
		}
	}

	public function admin_edit($id = null)
	{
		if (!$id) {
			$this->Flash->error('Curso inválido');
			return $this->redirect(array('action' => 'admin_index'));
		}

		$course = $this->Course->findById($id);
		if (!$course) {
			$this->Flash->error('Curso no encontrado');
			return $this->redirect(array('action' => 'admin_index'));
		}

		if ($this->request->is(array('post', 'put'))) {
			$this->Course->id = $id;
			if ($this->Course->save($this->request->data)) {
				$courseName = $this->request->data['Course']['nombre'];
				$this->AuditLog->logAction('update', 'Course', $id, "Editó el curso: {$courseName}");

				$this->Flash->success('✅ Curso actualizado correctamente');
				return $this->redirect(array('action' => 'admin_index'));
			}
			$this->Flash->error('No se pudo actualizar el curso');
		}

		if (!$this->request->data) {
			$this->request->data = $course;
		}
	}

	public function admin_delete($id = null)
	{
		$this->request->allowMethod('post');

		$course = $this->Course->findById($id);
		if (!$course) {
			$this->Flash->error('Curso no encontrado');
			return $this->redirect(array('action' => 'admin_index'));
		}

		if ($this->Course->delete($id)) {
			$courseName = $course['Course']['nombre'];
			$this->AuditLog->logAction('delete', 'Course', $id, "Eliminó el curso: {$courseName}");

			$this->Flash->success('✅ Curso eliminado correctamente');
		} else {
			$this->Flash->error('No se pudo eliminar el curso');
		}

		return $this->redirect(array('action' => 'admin_index'));
	}

	public function admin_toggle_active($id = null)
	{
		$this->request->allowMethod('post');

		$isAjax = $this->request->is('ajax') ||
			!empty($this->request->header('X-Requested-With')) ||
			(isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
				strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');

		$course = $this->Course->findById($id);
		if (!$course) {
			if ($isAjax) {
				$this->autoRender = false;
				$this->response->type('json');
				$this->response->body(json_encode(array('success' => false, 'message' => 'Curso no encontrado')));
				return $this->response;
			}
			$this->Flash->error('Curso no encontrado');
			return $this->redirect(array('action' => 'admin_index'));
		}

		$newStatus = $course['Course']['active'] ? 0 : 1;
		$this->Course->id = $id;

		if ($this->Course->saveField('active', $newStatus)) {
			$message = $newStatus ? 'Curso activado' : 'Curso desactivado';
			$courseName = $course['Course']['nombre'];
			$action = $newStatus ? 'activate' : 'deactivate';
			$this->AuditLog->logAction($action, 'Course', $id, "{$message}: {$courseName}");

			if ($isAjax) {
				$this->autoRender = false;
				$this->response->type('json');
				$this->response->body(json_encode(array(
					'success' => true,
					'message' => $message,
					'newStatus' => $newStatus,
					'statusText' => $newStatus ? 'Activo' : 'Inactivo',
					'buttonText' => $newStatus ? 'Desactivar' : 'Activar',
					'badgeClass' => $newStatus ? 'bg-success' : 'bg-secondary'
				)));
				return $this->response;
			}

			$this->Flash->success($message);
		} else {
			if ($isAjax) {
				$this->autoRender = false;
				$this->response->type('json');
				$this->response->body(json_encode(array('success' => false, 'message' => 'No se pudo cambiar el estado')));
				return $this->response;
			}
			$this->Flash->error('No se pudo cambiar el estado');
		}

		return $this->redirect(array('action' => 'admin_index'));
	}

	public function admin_view($id = null)
	{
		if (!$id) {
			$this->Flash->error('Curso inválido');
			return $this->redirect(array('action' => 'admin_index'));
		}

		$course = $this->Course->find('first', array(
			'conditions' => array('Course.id' => $id),
			'contain' => array('User')
		));

		if (!$course) {
			$this->Flash->error('Curso no encontrado');
			return $this->redirect(array('action' => 'admin_index'));
		}

		$inscritosIds = array();
		foreach ($course['User'] as $user) {
			$inscritosIds[] = $user['id'];
		}

		$conditions = array('User.role' => 'user');
		if (!empty($inscritosIds)) {
			$conditions['User.id NOT'] = $inscritosIds;
		}

		$usuariosDisponibles = $this->Course->User->find('all', array(
			'conditions' => $conditions,
			'order' => array('User.nombre' => 'asc')
		));

		$cuposInfo = $this->Course->getCuposDisponibles($id);

		$this->set('course', $course);
		$this->set('usuariosDisponibles', $usuariosDisponibles);
		$this->set('cuposInfo', $cuposInfo);
	}

	public function admin_add_user($courseId = null)
	{
		$this->request->allowMethod('post');

		if (!$courseId || empty($this->request->data['user_ids'])) {
			$this->Flash->error('Debes seleccionar al menos un usuario');
			return $this->redirect(array('action' => 'admin_view', $courseId));
		}

		$userIds = $this->request->data['user_ids'];

		$cuposInfo = $this->Course->getCuposDisponibles($courseId);
		$usuariosAgregar = count($userIds);

		if ($cuposInfo['disponibles'] < $usuariosAgregar) {
			$this->Flash->error('⚠️ No hay suficientes cupos. Disponibles: ' . $cuposInfo['disponibles'] . ', Intentando agregar: ' . $usuariosAgregar);
			return $this->redirect(array('action' => 'admin_view', $courseId));
		}

		App::uses('AppModel', 'Model');
		$CoursesUser = new AppModel(false, 'courses_users');

		$agregados = 0;
		$errores = 0;

		foreach ($userIds as $userId) {
			$CoursesUser->create();

			$data = array(
				'course_id' => $courseId,
				'user_id' => $userId,
				'created' => date('Y-m-d H:i:s')
			);

			if ($CoursesUser->save($data)) {
				$agregados++;
			} else {
				$errores++;
			}
		}

		if ($agregados > 0) {
			$course = $this->Course->findById($courseId);
			$courseName = $course['Course']['nombre'];
			$this->AuditLog->logAction('assign_users', 'Course', $courseId, "Agregó {$agregados} usuario(s) al curso: {$courseName}");
			$this->Flash->success('✅ ' . $agregados . ' usuario(s) agregado(s) al curso correctamente');
		}
		if ($errores > 0) {
			$this->Flash->error($errores . ' usuario(s) no pudieron ser agregados (pueden estar ya inscritos)');
		}

		return $this->redirect(array('action' => 'admin_view', $courseId));
	}

	public function admin_remove_user($courseId = null, $userId = null)
	{
		$this->request->allowMethod('post');

		if (!$courseId || !$userId) {
			$this->Flash->error('Datos inválidos');
			return $this->redirect(array('action' => 'admin_index'));
		}

		$this->Course->User->CoursesUser = ClassRegistry::init('CoursesUser');

		$deleted = $this->Course->User->CoursesUser->deleteAll(array(
			'CoursesUser.course_id' => $courseId,
			'CoursesUser.user_id' => $userId
		));

		if ($deleted) {
			$course = $this->Course->findById($courseId);
			$user = $this->Course->User->findById($userId);
			$courseName = $course['Course']['nombre'];
			$userName = $user['User']['nombre'] . ' ' . $user['User']['apellido'];
			$this->AuditLog->logAction('remove_user', 'Course', $courseId, "Removió a {$userName} del curso: {$courseName}");

			$this->Flash->success('✅ Usuario removido del curso');
		} else {
			$this->Flash->error('No se pudo remover el usuario');
		}

		return $this->redirect(array('action' => 'admin_view', $courseId));
	}

	public function export_courses()
	{
		if ($this->Auth->user('role') != 'admin') {
			$this->Flash->error('No tienes permisos para acceder');
			return $this->redirect(array('controller' => 'users', 'action' => 'index'));
		}

		$courses = $this->Course->find('all', array(
			'order' => array('Course.id' => 'ASC'),
			'contain' => array('User')
		));

		$this->autoRender = false;
		$this->response->type('csv');
		$this->response->download('cursos_' . date('Y-m-d_His') . '.csv');

		$output = fopen('php://output', 'w');

		fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));

		fputcsv($output, array(
			'ID',
			'Nombre',
			'Descripción',
			'Fecha Inicio',
			'Fecha Fin',
			'Cupo Máximo',
			'Inscritos',
			'Disponibles',
			'Estado',
			'Fecha Creación'
		), ';');

		foreach ($courses as $course) {
			$inscritos = count($course['User']);
			$disponibles = $course['Course']['cupo_maximo'] - $inscritos;

			fputcsv($output, array(
				$course['Course']['id'],
				$course['Course']['nombre'],
				$course['Course']['descripcion'],
				date('d/m/Y', strtotime($course['Course']['fecha_inicio'])),
				date('d/m/Y', strtotime($course['Course']['fecha_fin'])),
				$course['Course']['cupo_maximo'],
				$inscritos,
				$disponibles,
				$course['Course']['active'] ? 'Activo' : 'Inactivo',
				date('d/m/Y H:i', strtotime($course['Course']['created']))
			), ';');
		}

		fclose($output);
	}

	public function export_course_students($courseId = null)
	{
		if ($this->Auth->user('role') != 'admin') {
			$this->Flash->error('No tienes permisos para acceder');
			return $this->redirect(array('controller' => 'users', 'action' => 'index'));
		}

		if (!$courseId) {
			$this->Flash->error('Curso inválido');
			return $this->redirect(array('action' => 'admin_index'));
		}

		$course = $this->Course->find('first', array(
			'conditions' => array('Course.id' => $courseId),
			'contain' => array('User')
		));

		if (!$course) {
			$this->Flash->error('Curso no encontrado');
			return $this->redirect(array('action' => 'admin_index'));
		}

		$this->autoRender = false;
		$this->response->type('csv');
		$filename = 'estudiantes_' . preg_replace('/[^a-z0-9]/i', '_', $course['Course']['nombre']) . '_' . date('Y-m-d_His') . '.csv';
		$this->response->download($filename);

		$output = fopen('php://output', 'w');

		fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));

		fputcsv($output, array(
			'Curso: ' . $course['Course']['nombre']
		), ';');
		fputcsv($output, array(''), ';');
		fputcsv($output, array(
			'ID',
			'Nombre',
			'Apellido',
			'Email',
			'Estado'
		), ';');

		foreach ($course['User'] as $user) {
			if ($user['role'] == 'user') {
				fputcsv($output, array(
					$user['id'],
					$user['nombre'],
					$user['apellido'],
					$user['email'],
					$user['active'] ? 'Activo' : 'Inactivo'
				), ';');
			}
		}

		fclose($output);
	}
}
?>