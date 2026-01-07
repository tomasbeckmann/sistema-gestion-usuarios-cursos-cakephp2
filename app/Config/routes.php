<?php
/**
 * Routes configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different URLs to chosen controllers and their actions (functions).
 *
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @package       app.Config
 * @since         CakePHP(tm) v 0.2.9
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

/**
 * Here, we are connecting '/' (base path) to controller called 'Pages',
 * its action called 'display', and we pass a param to select the view file
 * to use (in this case, /app/View/Pages/home.ctp)...
 */
Router::connect('/', array('controller' => 'users', 'action' => 'login'));
/**
 * ...and connect the rest of 'Pages' controller's URLs.
 */
Router::connect('/pages/*', array('controller' => 'pages', 'action' => 'display'));

/**
 * Rutas personalizadas para el admin
 */
// Dashboard principal
Router::connect('/admin', array('controller' => 'users', 'action' => 'dashboard'));
Router::connect('/admin/dashboard', array('controller' => 'users', 'action' => 'dashboard'));

// Gestión de usuarios
Router::connect('/admin/users', array('controller' => 'users', 'action' => 'admin_dashboard'));
Router::connect('/admin/users/add', array('controller' => 'users', 'action' => 'admin_add'));
Router::connect('/admin/users/edit/:id', array('controller' => 'users', 'action' => 'admin_edit'), array('pass' => array('id'), 'id' => '[0-9]+'));
Router::connect('/admin/users/delete/:id', array('controller' => 'users', 'action' => 'admin_delete'), array('pass' => array('id'), 'id' => '[0-9]+'));
Router::connect('/admin/users/toggle-active/:id', array('controller' => 'users', 'action' => 'admin_toggle_active'), array('pass' => array('id'), 'id' => '[0-9]+'));
Router::connect('/admin/users/change-password/:id', array('controller' => 'users', 'action' => 'admin_change_password'), array('pass' => array('id'), 'id' => '[0-9]+'));

// Gestión de cursos
Router::connect('/admin/courses', array('controller' => 'courses', 'action' => 'admin_index'));
Router::connect('/admin/courses/add', array('controller' => 'courses', 'action' => 'admin_add'));
Router::connect('/admin/courses/edit/:id', array('controller' => 'courses', 'action' => 'admin_edit'), array('pass' => array('id'), 'id' => '[0-9]+'));
Router::connect('/admin/courses/view/:id', array('controller' => 'courses', 'action' => 'admin_view'), array('pass' => array('id'), 'id' => '[0-9]+'));
Router::connect('/admin/courses/delete/:id', array('controller' => 'courses', 'action' => 'admin_delete'), array('pass' => array('id'), 'id' => '[0-9]+'));
Router::connect('/admin/courses/toggle-active/:id', array('controller' => 'courses', 'action' => 'admin_toggle_active'), array('pass' => array('id'), 'id' => '[0-9]+'));
Router::connect('/admin/courses/add-user/:id', array('controller' => 'courses', 'action' => 'admin_add_user'), array('pass' => array('id'), 'id' => '[0-9]+'));
Router::connect('/admin/courses/remove-user/:courseId/:userId', array('controller' => 'courses', 'action' => 'admin_remove_user'), array('pass' => array('courseId', 'userId'), 'courseId' => '[0-9]+', 'userId' => '[0-9]+'));

/**
 * Load all plugin routes. See the CakePlugin documentation on
 * how to customize the loading of plugin routes.
 */
CakePlugin::routes();

/**
 * Load the CakePHP default routes. Only remove this if you do not want to use
 * the built-in default routes.
 */
require CAKE . 'Config' . DS . 'routes.php';

/**
 * Load all plugin routes. See the CakePlugin documentation on
 * how to customize the loading of plugin routes.
 */
CakePlugin::routes();

/**
 * Load the CakePHP default routes. Only remove this if you do not want to use
 * the built-in default routes.
 */
require CAKE . 'Config' . DS . 'routes.php';
