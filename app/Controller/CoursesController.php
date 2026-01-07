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
		// Solo admins pueden gestionar cursos
		if ($this->Auth->user('role') != 'admin') {
			$this->Flash->error('No tienes permisos para acceder');
			$this->redirect(array('controller' => 'users', 'action' => 'index'));
		}

		// Usar layout de admin para todas las vistas
		$this->layout = 'admin';
	}

	// Listar todos los cursos (Admin)
	public function admin_index()
	{
		// Búsqueda
		$conditions = array();

		if (!empty($this->request->query['search'])) {
			$search = $this->request->query['search'];
			$conditions['OR'] = array(
				'Course.nombre LIKE' => '%' . $search . '%',
				'Course.descripcion LIKE' => '%' . $search . '%'
			);
		}

		// Filtro por estado
		if (isset($this->request->query['active']) && $this->request->query['active'] !== '') {
			$conditions['Course.active'] = $this->request->query['active'];
		}

		$this->paginate['conditions'] = $conditions;
		$this->paginate['contain'] = array('User'); // Cargar usuarios relacionados

		$courses = $this->paginate('Course');
		$this->set('courses', $courses);
	}

	// Crear curso
	public function admin_add()
	{
		$this->layout = 'admin';

		if ($this->request->is('post')) {
			$this->Course->create();
			if ($this->Course->save($this->request->data)) {
				// AGREGAR LOG
				$courseId = $this->Course->id;
				$courseName = $this->request->data['Course']['nombre'];
				$this->AuditLog->logAction('create', 'Course', $courseId, "Creó el curso: {$courseName}");

				$this->Flash->success('✅ Curso "' . $this->request->data['Course']['nombre'] . '" creado correctamente');
				return $this->redirect(array('action' => 'admin_index'));
			}
			$this->Flash->error('No se pudo crear el curso');
		}
	}

	// Editar curso
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

		// Obtener el curso antes de eliminarlo
		$course = $this->Course->findById($id);
		if (!$course) {
			$this->Flash->error('Curso no encontrado');
			return $this->redirect(array('action' => 'admin_index'));
		}

		if ($this->Course->delete($id)) {
			// AGREGAR LOG
			$courseName = $course['Course']['nombre'];
			$this->AuditLog->logAction('delete', 'Course', $id, "Eliminó el curso: {$courseName}");

			$this->Flash->success('Curso eliminado correctamente');
		} else {
			$this->Flash->error('No se pudo eliminar el curso');
		}

		return $this->redirect(array('action' => 'admin_index'));
	}

	// Activar/Desactivar curso
	public function admin_toggle_active($id = null)
	{
		$this->request->allowMethod('post');

		// Detectar si es AJAX
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
			// Si es petición AJAX, responder JSON
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
	// Ver detalle del curso con usuarios inscritos
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

		// Obtener usuarios NO inscritos en este curso
		$inscritosIds = array();
		foreach ($course['User'] as $user) {
			$inscritosIds[] = $user['id'];
		}

		$conditions = array('User.role' => 'user'); // Solo usuarios normales
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

	// Agregar usuario al curso
	// Agregar usuarios al curso (múltiples)
	public function admin_add_user($courseId = null)
	{
		$this->request->allowMethod('post');

		if (!$courseId || empty($this->request->data['user_ids'])) {
			$this->Flash->error('Debes seleccionar al menos un usuario');
			return $this->redirect(array('action' => 'admin_view', $courseId));
		}

		$userIds = $this->request->data['user_ids'];

		// Verificar cupos disponibles
		$cuposInfo = $this->Course->getCuposDisponibles($courseId);
		$usuariosAgregar = count($userIds);

		if ($cuposInfo['disponibles'] < $usuariosAgregar) {
			$this->Flash->error('No hay suficientes cupos. Disponibles: ' . $cuposInfo['disponibles'] . ', Intentando agregar: ' . $usuariosAgregar);
			return $this->redirect(array('action' => 'admin_view', $courseId));
		}

		// Usar el modelo CoursesUser para insertar directamente en la tabla intermedia
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

	// Quitar usuario del curso
	public function admin_remove_user($courseId = null, $userId = null)
	{
		$this->request->allowMethod('post');

		if (!$courseId || !$userId) {
			$this->Flash->error('Datos inválidos');
			return $this->redirect(array('action' => 'admin_index'));
		}

		// Eliminar la relación de la tabla intermedia
		$this->Course->User->CoursesUser = ClassRegistry::init('CoursesUser');

		$deleted = $this->Course->User->CoursesUser->deleteAll(array(
			'CoursesUser.course_id' => $courseId,
			'CoursesUser.user_id' => $userId
		));

		if ($deleted) {
			// AGREGAR LOG
			$course = $this->Course->findById($courseId);
			$user = $this->Course->User->findById($userId);
			$courseName = $course['Course']['nombre'];
			$userName = $user['User']['nombre'] . ' ' . $user['User']['apellido'];
			$this->AuditLog->logAction('remove_user', 'Course', $courseId, "Removió a {$userName} del curso: {$courseName}");

			$this->Flash->success('Usuario removido del curso');
		} else {
			$this->Flash->error('No se pudo remover el usuario');
		}

		return $this->redirect(array('action' => 'admin_view', $courseId));
	}

	public function export_courses()
	{
		// Verificar que sea admin
		if ($this->Auth->user('role') != 'admin') {
			$this->Flash->error('No tienes permisos para acceder');
			return $this->redirect(array('controller' => 'users', 'action' => 'index'));
		}

		// Obtener todos los cursos con usuarios
		$courses = $this->Course->find('all', array(
			'order' => array('Course.id' => 'ASC'),
			'contain' => array('User')
		));

		// Configurar headers para descarga CSV
		$this->autoRender = false;
		$this->response->type('csv');
		$this->response->download('cursos_' . date('Y-m-d_His') . '.csv');

		// Abrir output
		$output = fopen('php://output', 'w');

		// BOM para UTF-8
		fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));

		// Encabezados
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

		// Escribir datos
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
		// Verificar que sea admin
		if ($this->Auth->user('role') != 'admin') {
			$this->Flash->error('No tienes permisos para acceder');
			return $this->redirect(array('controller' => 'users', 'action' => 'index'));
		}

		if (!$courseId) {
			$this->Flash->error('Curso inválido');
			return $this->redirect(array('action' => 'admin_index'));
		}

		// Obtener curso con sus estudiantes
		$course = $this->Course->find('first', array(
			'conditions' => array('Course.id' => $courseId),
			'contain' => array('User')
		));

		if (!$course) {
			$this->Flash->error('Curso no encontrado');
			return $this->redirect(array('action' => 'admin_index'));
		}

		// Configurar headers
		$this->autoRender = false;
		$this->response->type('csv');
		$filename = 'estudiantes_' . preg_replace('/[^a-z0-9]/i', '_', $course['Course']['nombre']) . '_' . date('Y-m-d_His') . '.csv';
		$this->response->download($filename);

		// Abrir output
		$output = fopen('php://output', 'w');

		// BOM para UTF-8
		fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));

		// Encabezados
		fputcsv($output, array(
			'Curso: ' . $course['Course']['nombre']
		), ';');
		fputcsv($output, array(''), ';'); // Línea en blanco
		fputcsv($output, array(
			'ID',
			'Nombre',
			'Apellido',
			'Email',
			'Estado'
		), ';');

		// Escribir estudiantes
		foreach ($course['User'] as $user) {
			if ($user['role'] == 'user') { // Solo usuarios normales
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