<?php
App::uses('AppController', 'Controller');

class UsersController extends AppController
{
	public $paginate = array(
		'limit' => 10,
		'order' => array('User.id' => 'asc')
	);

	public function beforeFilter()
	{
		parent::beforeFilter();
		$this->Auth->allow('login');
	}

	public function login()
	{
		if ($this->request->is('post')) {
			if ($this->Auth->login()) {
				$user = $this->Auth->user();
				if ($user['role'] == 'admin') {
					return $this->redirect(array('action' => 'dashboard')); // ← CAMBIAR AQUÍ
				} else {
					return $this->redirect(array('action' => 'index'));
				}
			}
			$this->Flash->error('Email o contraseña incorrectos');
		}
	}

	public function logout()
	{
		/* $this->Flash->success('Has cerrado sesión correctamente'); */
		return $this->redirect($this->Auth->logout());
	}

	public function index()
	{
		// Dashboard para usuarios normales
		$userId = $this->Auth->user('id');

		// Cargar el modelo Course
		$this->loadModel('Course');

		// Obtener información del usuario con sus cursos
		$user = $this->User->find('first', array(
			'conditions' => array('User.id' => $userId),
			'contain' => array('Course')
		));

		// Para cada curso, obtener sus usuarios (compañeros)
		$coursesWithUsers = array();
		if (isset($user['Course']) && !empty($user['Course'])) {
			foreach ($user['Course'] as $course) {
				// Obtener el curso completo con todos sus usuarios
				$courseComplete = $this->Course->find('first', array(
					'conditions' => array('Course.id' => $course['id']),
					'contain' => array('User')
				));

				if ($courseComplete) {
					// Merge la info del curso con los usuarios
					$course['User'] = $courseComplete['User'];
					$coursesWithUsers[] = $course;
				}
			}
		}

		$this->set('user', $user);
		$this->set('courses', $coursesWithUsers);
	}
	public function view_course($courseId = null)
	{
		if (!$courseId) {
			$this->Flash->error('Curso inválido');
			return $this->redirect(array('action' => 'index'));
		}

		$userId = $this->Auth->user('id');

		// Verificar que el usuario esté inscrito en este curso
		$this->loadModel('Course');
		$course = $this->Course->find('first', array(
			'conditions' => array('Course.id' => $courseId),
			'contain' => array('User')
		));

		if (!$course) {
			$this->Flash->error('Curso no encontrado');
			return $this->redirect(array('action' => 'index'));
		}

		// Verificar que el usuario esté inscrito
		$inscrito = false;
		foreach ($course['User'] as $user) {
			if ($user['id'] == $userId) {
				$inscrito = true;
				break;
			}
		}

		if (!$inscrito) {
			$this->Flash->error('No tienes acceso a este curso');
			return $this->redirect(array('action' => 'index'));
		}

		$this->set('course', $course);
		$this->set('currentUserId', $userId);
	}

	public function dashboard()
	{
		$this->layout = 'admin';

		// Verificar que sea admin
		if ($this->Auth->user('role') != 'admin') {
			$this->Flash->error('No tienes permisos para acceder');
			return $this->redirect(array('action' => 'index'));
		}

		// ===== ESTADÍSTICAS PARA EL DASHBOARD =====
		$this->loadModel('Course');

		// Total de usuarios
		$totalUsuarios = $this->User->find('count');
		$usuariosActivos = $this->User->find('count', array('conditions' => array('User.active' => 1)));
		$usuariosInactivos = $totalUsuarios - $usuariosActivos;

		// Total de cursos
		$totalCursos = $this->Course->find('count');
		$cursosActivos = $this->Course->find('count', array('conditions' => array('Course.active' => 1)));

		// Usuarios por rol
		$admins = $this->User->find('count', array('conditions' => array('User.role' => 'admin')));
		$usuarios = $this->User->find('count', array('conditions' => array('User.role' => 'user')));

		// Cursos con más inscritos
		$cursosPopulares = $this->Course->find('all', array(
			'contain' => array('User'),
			'order' => array('Course.nombre' => 'ASC'),
			'limit' => 10
		));

		// Preparar datos para gráfico
		$cursosData = array();
		foreach ($cursosPopulares as $curso) {
			$cursosData[] = array(
				'nombre' => $curso['Course']['nombre'],
				'inscritos' => count($curso['User']),
				'cupo_maximo' => $curso['Course']['cupo_maximo']
			);
		}

		// Usuarios sin cursos
		$usuariosSinCursos = $this->User->find('all', array(
			'conditions' => array('User.role' => 'user'),
			'contain' => array('Course')
		));

		$countSinCursos = 0;
		foreach ($usuariosSinCursos as $user) {
			if (empty($user['Course'])) {
				$countSinCursos++;
			}
		}

		// Cursos por estado (próximos, en curso, finalizados)
		$hoy = date('Y-m-d');
		$cursosProximos = $this->Course->find('count', array(
			'conditions' => array(
				'Course.fecha_inicio >' => $hoy,
				'Course.active' => 1
			)
		));

		$cursosEnCurso = $this->Course->find('count', array(
			'conditions' => array(
				'Course.fecha_inicio <=' => $hoy,
				'Course.fecha_fin >=' => $hoy,
				'Course.active' => 1
			)
		));

		$cursosFinalizados = $this->Course->find('count', array(
			'conditions' => array(
				'Course.fecha_fin <' => $hoy
			)
		));

		// Enviar estadísticas a la vista
		$this->set('stats', array(
			'totalUsuarios' => $totalUsuarios,
			'usuariosActivos' => $usuariosActivos,
			'usuariosInactivos' => $usuariosInactivos,
			'totalCursos' => $totalCursos,
			'cursosActivos' => $cursosActivos,
			'admins' => $admins,
			'usuarios' => $usuarios,
			'usuariosSinCursos' => $countSinCursos,
			'cursosData' => $cursosData,
			'cursosProximos' => $cursosProximos,
			'cursosEnCurso' => $cursosEnCurso,
			'cursosFinalizados' => $cursosFinalizados
		));
	}

	public function admin_dashboard()
	{
		$this->layout = 'admin';
		// Verificar que sea admin
		if ($this->Auth->user('role') != 'admin') {
			$this->Flash->error('No tienes permisos para acceder');
			return $this->redirect(array('action' => 'index'));
		}

		// Búsqueda y filtros
		$conditions = array();

		// Búsqueda general
		if (!empty($this->request->query['search'])) {
			$search = $this->request->query['search'];
			$conditions['OR'] = array(
				'User.nombre LIKE' => '%' . $search . '%',
				'User.apellido LIKE' => '%' . $search . '%',
				'User.email LIKE' => '%' . $search . '%'
			);
		}

		// Filtro por role
		if (!empty($this->request->query['role'])) {
			$conditions['User.role'] = $this->request->query['role'];
		}

		// Filtro por estado
		if (isset($this->request->query['active']) && $this->request->query['active'] !== '') {
			$conditions['User.active'] = $this->request->query['active'];
		}

		$this->paginate['conditions'] = $conditions;
		$users = $this->paginate('User');

		$this->set('users', $users);
		$this->set('currentUser', $this->Auth->user());
	}

	public function admin_add()
	{
		$this->layout = 'admin';

		if ($this->Auth->user('role') != 'admin') {
			$this->Flash->error('No tienes permisos para acceder');
			return $this->redirect(array('action' => 'index'));
		}

		if ($this->request->is('post')) {
			$this->User->create();
			if ($this->User->save($this->request->data)) {
				// AGREGAR LOG
				$userId = $this->User->id;
				$userName = $this->request->data['User']['nombre'] . ' ' . $this->request->data['User']['apellido'];
				$this->AuditLog->logAction('create', 'User', $userId, "Creó el usuario: {$userName} ({$this->request->data['User']['email']})");
				return $this->redirect(array('action' => 'admin_dashboard'));
			}
			$this->Flash->error('No se pudo crear el usuario. Por favor, verifica los datos.');
		}
	}

	public function admin_edit($id = null)
	{
		$this->layout = 'admin';
		// Verificar que sea admin
		if ($this->Auth->user('role') != 'admin') {
			$this->Flash->error('No tienes permisos para acceder');
			return $this->redirect(array('action' => 'index'));
		}

		if (!$id) {
			$this->Flash->error('Usuario inválido');
			return $this->redirect(array('action' => 'admin_dashboard'));
		}

		$user = $this->User->findById($id);
		if (!$user) {
			$this->Flash->error('Usuario no encontrado');
			return $this->redirect(array('action' => 'admin_dashboard'));
		}

		if ($this->request->is(array('post', 'put'))) {
			$this->User->id = $id;

			// Si no se ingresó password, no actualizar ese campo
			if (empty($this->request->data['User']['password'])) {
				unset($this->request->data['User']['password']);
			}

			$userName = $this->request->data['User']['nombre'] . ' ' . $this->request->data['User']['apellido'];
			$this->AuditLog->logAction('update', 'User', $id, "Editó el usuario: {$userName}");

			if ($this->User->save($this->request->data)) {
				$this->Flash->success('Usuario actualizado correctamente');
				return $this->redirect(array('action' => 'admin_dashboard'));
			}
			$this->Flash->error('No se pudo actualizar el usuario');
		}

		if (!$this->request->data) {
			$this->request->data = $user;
			// Limpiar password para que no se muestre
			unset($this->request->data['User']['password']);
		}
	}

	public function admin_delete($id = null)
	{
		// Verificar que sea admin
		if ($this->Auth->user('role') != 'admin') {
			$this->Flash->error('No tienes permisos para acceder');
			return $this->redirect(array('action' => 'index'));
		}

		$this->request->allowMethod('post');

		// No permitir eliminar al mismo usuario logueado
		if ($id == $this->Auth->user('id')) {
			$this->Flash->error('No puedes eliminar tu propia cuenta');
			return $this->redirect(array('action' => 'admin_dashboard'));
		}

		$user = $this->User->findById($id);
		if (!$user) {
			$this->Flash->error('Usuario no encontrado');
			return $this->redirect(array('action' => 'admin_dashboard'));
		}

		if ($this->User->delete($id)) {
			// AGREGAR LOG
			$userName = $user['User']['nombre'] . ' ' . $user['User']['apellido'];
			$this->AuditLog->logAction('delete', 'User', $id, "Eliminó el usuario: {$userName} ({$user['User']['email']})");

			$this->Flash->success('Usuario eliminado correctamente');
		} else {
			$this->Flash->error('No se pudo eliminar el usuario');
		}

		return $this->redirect(array('action' => 'admin_dashboard'));
	}

	public function admin_toggle_active($id = null)
	{
		// Verificar que sea admin
		if ($this->Auth->user('role') != 'admin') {
			$this->Flash->error('No tienes permisos para acceder');
			return $this->redirect(array('action' => 'index'));
		}

		$this->request->allowMethod('post');

		// Detectar si es AJAX
		$isAjax = $this->request->is('ajax') ||
			!empty($this->request->header('X-Requested-With')) ||
			(isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
				strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');

		// No permitir desactivar al mismo usuario logueado
		if ($id == $this->Auth->user('id')) {
			if ($isAjax) {
				$this->autoRender = false;
				$this->response->type('json');
				$this->response->body(json_encode(array('success' => false, 'message' => 'No puedes desactivar tu propia cuenta')));
				return $this->response;
			}
			$this->Flash->error('No puedes desactivar tu propia cuenta');
			return $this->redirect(array('action' => 'admin_dashboard'));
		}

		$user = $this->User->findById($id);
		if (!$user) {
			if ($isAjax) {
				$this->autoRender = false;
				$this->response->type('json');
				$this->response->body(json_encode(array('success' => false, 'message' => 'Usuario no encontrado')));
				return $this->response;
			}
			$this->Flash->error('Usuario no encontrado');
			return $this->redirect(array('action' => 'admin_dashboard'));
		}

		// Cambiar estado
		$newStatus = $user['User']['active'] ? 0 : 1;
		$this->User->id = $id;

		if ($this->User->saveField('active', $newStatus)) {
			$message = $newStatus ? 'Usuario activado' : 'Usuario desactivado';

			$userName = $user['User']['nombre'] . ' ' . $user['User']['apellido'];
			$action = $newStatus ? 'activate' : 'deactivate';
			$this->AuditLog->logAction($action, 'User', $id, "{$message}: {$userName}");

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

		return $this->redirect(array('action' => 'admin_dashboard'));
	}

	public function admin_change_password($id = null)
	{
		$this->layout = 'admin';
		// Verificar que sea admin
		if ($this->Auth->user('role') != 'admin') {
			$this->Flash->error('No tienes permisos para acceder');
			return $this->redirect(array('action' => 'index'));
		}

		if (!$id) {
			$this->Flash->error('Usuario inválido');
			return $this->redirect(array('action' => 'admin_dashboard'));
		}

		$user = $this->User->findById($id);
		if (!$user) {
			$this->Flash->error('Usuario no encontrado');
			return $this->redirect(array('action' => 'admin_dashboard'));
		}

		if ($this->request->is(array('post', 'put'))) {
			$this->User->id = $id;

			if ($this->User->save($this->request->data, array('fieldList' => array('password')))) {
				$this->Flash->success('Contraseña actualizada correctamente');
				return $this->redirect(array('action' => 'admin_dashboard'));
			}
			$this->Flash->error('No se pudo actualizar la contraseña');
		}

		$this->set('user', $user);
	}

	public function export_users()
	{
		// Verificar que sea admin
		if ($this->Auth->user('role') != 'admin') {
			$this->Flash->error('No tienes permisos para acceder');
			return $this->redirect(array('action' => 'index'));
		}

		// Obtener todos los usuarios
		$users = $this->User->find('all', array(
			'order' => array('User.id' => 'ASC'),
			'contain' => array('Course')
		));

		// Configurar headers para descarga CSV
		$this->autoRender = false;
		$this->response->type('csv');
		$this->response->download('usuarios_' . date('Y-m-d_His') . '.csv');

		// Abrir output para escribir CSV
		$output = fopen('php://output', 'w');

		// Escribir BOM para UTF-8 (para que Excel reconozca caracteres especiales)
		fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));

		// Encabezados del CSV
		fputcsv($output, array(
			'ID',
			'Nombre',
			'Apellido',
			'Email',
			'Rol',
			'Estado',
			'Cursos Inscritos',
			'Fecha Creación'
		), ';');

		// Escribir datos
		foreach ($users as $user) {
			// Obtener nombres de cursos
			$cursos = array();
			if (!empty($user['Course'])) {
				foreach ($user['Course'] as $course) {
					$cursos[] = $course['nombre'];
				}
			}
			$cursosStr = empty($cursos) ? 'Sin cursos' : implode(', ', $cursos);

			fputcsv($output, array(
				$user['User']['id'],
				$user['User']['nombre'],
				$user['User']['apellido'],
				$user['User']['email'],
				$user['User']['role'] == 'admin' ? 'Administrador' : 'Usuario',
				$user['User']['active'] ? 'Activo' : 'Inactivo',
				$cursosStr,
				date('d/m/Y H:i', strtotime($user['User']['created']))
			), ';');
		}

		fclose($output);
	}

	public function admin_logs()
	{
		$this->layout = 'admin';

		// Verificar que sea admin
		if ($this->Auth->user('role') != 'admin') {
			$this->Flash->error('No tienes permisos para acceder');
			return $this->redirect(array('action' => 'index'));
		}

		// Cargar el modelo AuditLog
		$this->loadModel('AuditLog');

		// Configurar paginación
		$this->paginate = array(
			'AuditLog' => array(
				'contain' => array('User'),
				'order' => array('AuditLog.created' => 'DESC'),
				'limit' => 20
			)
		);

		// Filtros
		$conditions = array();

		// Filtro por usuario
		if (!empty($this->request->query['user_id'])) {
			$conditions['AuditLog.user_id'] = $this->request->query['user_id'];
		}

		// Filtro por acción
		if (!empty($this->request->query['action'])) {
			$conditions['AuditLog.action'] = $this->request->query['action'];
		}

		// Filtro por modelo
		if (!empty($this->request->query['model'])) {
			$conditions['AuditLog.model'] = $this->request->query['model'];
		}

		// Filtro por fecha
		if (!empty($this->request->query['date_from'])) {
			$conditions['AuditLog.created >='] = $this->request->query['date_from'] . ' 00:00:00';
		}

		if (!empty($this->request->query['date_to'])) {
			$conditions['AuditLog.created <='] = $this->request->query['date_to'] . ' 23:59:59';
		}

		$this->paginate['AuditLog']['conditions'] = $conditions;
		$logs = $this->paginate('AuditLog');

		// Obtener lista de usuarios para el filtro
		$users = $this->User->find('list', array(
			'conditions' => array('User.role' => 'admin'),
			'fields' => array('User.id', 'User.email')
		));

		$this->set('logs', $logs);
		$this->set('users', $users);
	}
}
?>