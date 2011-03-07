<?php

class Subjects_SubjectController extends Yeah_Action
{
    public function viewAction() {
        global $USER;

        $this->requirePermission('subjects', 'view');
        $request = $this->getRequest();

        $model_gestions = new Gestions();
        $model_areas = new Areas();
        $model_subjects = new Subjects();
        $model_groups = new Groups();

        $url_gestion = $request->getParam('gestion');
        $url_subject = $request->getParam('subject');
        if (empty($url_gestion)) {
            $gestion = $model_gestions->findByActive();
            $historial = false;
        } else {
            $gestion = $model_gestions->findByUrl($url_gestion);
            $historial = true;
        }

        $subject = $model_subjects->findByUrl($gestion->ident, $url_subject);
        $this->requireExistence($subject, 'subject', 'subjects_subject_view', 'subjects_list');

        context('subject', $subject);

        $url = $this->view->url(array(), 'subjects_list');
        if ($subject->status == 'inactive' && !$this->acl('subjects', 'edit')) {
            $session = new Zend_Session_Namespace();
            $session->messages->addMessage("La materia {$subject->label} no esta activa");
            $this->_redirect($url);
        }

        $model_subjects_users = new Subjects_Users();
        switch ($subject->visibility) {
            case 'public':
                break;
            case 'register':
                if ($USER->role == 1) {
                    $this->_redirect($url);
                }
                break;
            case 'private':
                if ($USER->role == 1) {
                    $this->_redirect($url);
                    return;
                }
                if ($this->acl('subjects', 'edit')) {
                    break;
                }
                if ($subject->moderator == $USER->ident) {
                    break;
                }
                $assign = $model_subjects_users->findBySubjectAndUser($subject->ident, $USER->ident);
                if (empty($assign)) {
                    $this->_redirect($url);
                }
                break;
        }

        $list_groups = $model_groups->selectByStatus($subject->ident, 'active');
        $list_areas = $subject->findAreasViaAreas_Subjects();

        $resources = $subject->findResourcesViaSubjects_Resources($subject->select()->order('tsregister DESC'));

        // PAGINATOR
        $request = $this->getRequest();
        $page = $request->getParam('page', 1);
        $paginator = Zend_Paginator::factory($resources);
        $paginator->setItemCountPerPage(10);
        $paginator->setCurrentPageNumber($page);
        $paginator->setPageRange(10);

        $this->view->historial = $historial;
        $this->view->gestion = $gestion;
        $this->view->areas = $list_areas;
        $this->view->subject = $subject;
        $this->view->groups = $list_groups;
        $this->view->model_groups_users = new Groups_Users();
        $this->view->resources = $paginator;
        $this->view->route = array (
            'key' => 'subjects_subject_view',
            'params' => array (
                'subject' => $subject->url,
            ),
        );

        if ($historial) {
            history('gestions/' . $gestion->url . '/' . $subject->url);
            $breadcrumb = array();
            if ($this->acl('gestions', 'list')) {
                $breadcrumb['Gestiones'] = $this->view->url(array(), 'gestions_list');
            }
            if ($this->acl('gestions', array('new', 'active', 'delete'))) {
                $breadcrumb['Administrador de gestiones'] = $this->view->url(array(), 'gestions_manager');
            }
            if ($this->acl('gestions', 'view')) {
                $breadcrumb[$gestion->label] = $this->view->url(array('gestion' => $gestion->url), 'gestions_gestion_view');
            }
            breadcrumb($breadcrumb);
        } else {
            history('subjects/' . $subject->url);
            $breadcrumb = array();
            if ($this->acl('subjects', 'list')) {
                $breadcrumb['Materias'] = $this->view->url(array(), 'subjects_list');
            }
            if ($this->acl('subjects', array('new', 'import', 'export', 'lock', 'delete'))) {
                $breadcrumb['Administrador de materias'] = $this->view->url(array(), 'subjects_manager');
            }
            breadcrumb($breadcrumb);
        }
    }

    public function editAction() {
        $this->requirePermission('subjects', 'edit');
        $request = $this->getRequest();

        $model_areas = new Areas();
        $model_subjects = new Subjects();
        $model_gestions = new Gestions();

        $gestion = $model_gestions->findByActive();
        $subject = $model_subjects->findByUrl($gestion->ident, $request->getParam('subject'));

        $this->requireExistence($subject, 'subject', 'subjects_subject_view', 'subjects_list');

        context('subject', $subject);

        $checks = array();
        $list_areas = $subject->findAreasViaAreas_Subjects();

        foreach ($list_areas as $area) {
            $checks[] = $area->ident;
        }

        if ($request->isPost()) {
            $session = new Zend_Session_Namespace();

            $subject->label = $request->getParam('label');
            $subject->url = convert($subject->label);
            $subject->moderator = $request->getParam('moderator');
            $subject->code = $request->getParam('code');
            $subject->visibility = $request->getParam('visibility');
            $subject->description = $request->getParam('description');

            $checks = $request->getParam('areas');
            if (empty($checks)) {
                $checks = array();
            }

            if ($subject->isValid()) {
                $model_areas_subjects = new Areas_Subjects();

                $subject->save();
                $model_areas_subjects->cleanSubjects($subject->ident);

                foreach ($checks as $area) {
                    $assign = $model_areas_subjects->createRow();
                    $assign->area = $area;
                    $assign->subject = $subject->ident;
                    $assign->save();
                }

                $session->messages->addMessage("La materia {$subject->label} se ha actualizado correctamente");
                $session->url = $subject->url;
                $this->_redirect($request->getParam('return'));
            } else {
                foreach ($subject->getMessages() as $message) {
                    $session->messages->addMessage($message);
                }
            }
        }

        $this->view->model_subjects = $model_subjects;
        $this->view->areas = $model_areas->selectAll();
        $this->view->checks = $checks;
        $this->view->subject = $subject;

        history('subjects/' . $subject->url . '/edit');
        $breadcrumb = array();
        if ($this->acl('subjects', 'list')) {
            $breadcrumb['Materias'] = $this->view->url(array(), 'subjects_list');
        }
        if ($this->acl('subjects', array('new', 'import', 'export', 'lock', 'delete'))) {
            $breadcrumb['Administrador de materias'] = $this->view->url(array(), 'subjects_manager');
        }
        if ($this->acl('subjects', 'view')) {
            $breadcrumb[$subject->label] = $this->view->url(array('subject' => $subject->url), 'subjects_subject_view');
        }
        breadcrumb($breadcrumb);
    }

    public function lockAction() {
        $this->requirePermission('subjects', 'lock');

        $request = $this->getRequest();
        $url = $request->getParam('subject');

        $model_subjects = new Subjects();
        $model_gestions = new Gestions();

        $gestion = $model_gestions->findByActive();
        $subject = $model_subjects->findByUrl($gestion->ident, $url);
        $this->requireExistence($subject, 'subject', 'subjects_subject_view', 'subjects_list');

        $session = new Zend_Session_Namespace();
        $label = $subject->label;

        $subject->status = 'inactive';
        $subject->save();
        $session->messages->addMessage("La materia $label ha sido desactivada");

        $this->_redirect($this->view->currentPage());
    }

    public function unlockAction() {
        $this->requirePermission('subjects', 'lock');

        $request = $this->getRequest();
        $url = $request->getParam('subject');

        $model_subjects = new Subjects();
        $model_gestions = new Gestions();

        $gestion = $model_gestions->findByActive();
        $subject = $model_subjects->findByUrl($gestion->ident, $url);
        $this->requireExistence($subject, 'subject', 'subjects_subject_view', 'subjects_list');

        $session = new Zend_Session_Namespace();
        $label = $subject->label;

        $subject->status = 'active';
        $subject->save();
        $session->messages->addMessage("La materia $label ha sido activada");

        $this->_redirect($this->view->currentPage());
    }

    public function deleteAction() {
        $this->requirePermission('subjects', 'delete');

        $request = $this->getRequest();
        $url = $request->getParam('subject');

        $model_subjects = new Subjects();
        $model_gestions = new Gestions();

        $gestion = $model_gestions->findByActive();
        $subject = $model_subjects->findByUrl($gestion->ident, $url);
        $this->requireExistence($subject, 'subject', 'subjects_subject_view', 'subjects_list');

        $session = new Zend_Session_Namespace();
        $label = $subject->label;
        if ($subject->isEmpty()) {
            $subject->delete();
            $session->messages->addMessage("La materia $label ha sido eliminada");
        } else {
            $session->messages->addMessage("La materia $label no puede ser eliminada");
        }

        $this->_redirect($this->view->currentPage());
    }
}
