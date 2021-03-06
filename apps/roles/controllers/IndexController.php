<?php

class Roles_IndexController extends Yachay_Controller_Action {

    public function indexAction() {
        $this->requirePermission('roles', 'list');

        $model_roles = new Roles();

        $this->view->model_roles = $model_roles;
        $this->view->roles = $model_roles->selectAll();

        $this->history('roles');
        $breadcrumb = array();
        if ($this->acl('roles', array('new', 'assign', 'delete'))) {
            $breadcrumb['Administrador de roles'] = $this->view->url(array(), 'roles_manager');
        }
        $this->breadcrumb($breadcrumb);
    }
}
