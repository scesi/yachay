<?php

class Evaluations_TestController extends Yachay_Controller_Action
{
    // FIXME Agregar restricciones de historial, sea lo que eso haya significado en ese momento
    public function valueAction() {
        $this->requirePermission('subjects', 'teach');
        $request = $this->getRequest();

        $url_evaluation = $request->getParam('evaluation');
        $url_test_evaluation = $request->getParam('test');
        $url_test_value_evaluation = $request->getParam('value');

        $model_evaluations = new Evaluations();
        $evaluation = $model_evaluations->findByIdent($url_evaluation);

        $this->requireExistence($evaluation, 'evaluation', 'evaluations_evaluation_view', 'resources_list');

        if ($evaluation->author != $this->user->ident) {
            $this->_redirect('/', array('prependBase' => true));
        }

        $model_evaluations_tests = new Evaluations_Tests();
        $test_evaluation = $model_evaluations_tests->findByIdent($url_test_evaluation);

        if (empty($test_evaluation)) {
            $this->_redirect('/', array('prependBase' => true));
        }

        $model_evaluations_tests_values = new Evaluations_Tests_Values();
        $value_test_evaluation = $model_evaluations_tests_values->findByIdent($url_test_value_evaluation);

        if (!empty($value_test_evaluation)) {
            $value_test_evaluation->delete();
            $this->_helper->flashMessenger->addMessage('La calificacion ha sido eliminada');
        }

        $this->_redirect($this->view->currentPage());
    }

    // FIXME Agregar restricciones de historial, sea lo que eso haya significado en ese momento
    public function configAction() {
        $this->requirePermission('subjects', 'teach');
        $request = $this->getRequest();

        $url_evaluation = $request->getParam('evaluation');
        $url_test_evaluation = $request->getParam('test');

        $model_evaluations = new Evaluations();
        $evaluation = $model_evaluations->findByIdent($url_evaluation);

        $this->requireExistence($evaluation, 'evaluation', 'evaluations_evaluation_view', 'resources_list');

        if ($evaluation->author != $this->user->ident) {
            $this->_redirect('/', array('prependBase' => true));
        }

        $model_evaluations_tests = new Evaluations_Tests();
        $test_evaluation = $model_evaluations_tests->findByIdent($url_test_evaluation);
        if (empty($test_evaluation)) {
            $this->_redirect('/', array('prependBase' => true));
        }

        $empty_test_value_evaluation = new Evaluations_Tests_Values_Empty();
        $this->view->test_value_evaluation = $empty_test_value_evaluation;

        if ($request->isPost()) {
            $model_evaluations_tests_values = new Evaluations_Tests_Values();
            $test_value_evaluation = $model_evaluations_tests_values->createRow();
            $test_value_evaluation->label = $request->getParam('label');
            $test_value_evaluation->value = $request->getParam('value');
            $test_value_evaluation->evaluation = $evaluation->ident;
            $test_value_evaluation->test = $test_evaluation->ident;

            if ($test_value_evaluation->isValid()) {
                if ($test_evaluation->minimumnote <= $test_value_evaluation->value && $test_value_evaluation->value <=  $test_evaluation->maximumnote) {
                    $test_value_evaluation->save();
                    $this->_helper->flashMessenger->addMessage('Se ha agregado un nuevo valor cualitativo');
                } else {
                    $this->_helper->flashMessenger->addMessage('El valor de la nota no esta dentro del rango');
                    $this->view->test_value_evaluation = $test_value_evaluation;
                }
            } else {
                foreach ($test_value_evaluation->getMessages() as $message) {
                    $this->_helper->flashMessenger->addMessage($message);
                }
                $this->view->test_value_evaluation = $test_value_evaluation;
            }
        } else {
            $this->history('evaluations/' . $evaluation->ident . '/' . $test_evaluation->ident);
        }

        $test_values_evaluation = $test_evaluation->findEvaluations_Tests_Values($test_evaluation->select()->order('value ASC'));

        $this->view->model_evaluations = $model_evaluations;
        $this->view->model_evaluations_tests = $model_evaluations_tests;
        $this->view->evaluation = $evaluation;
        $this->view->test_evaluation = $test_evaluation;
        $this->view->test_values_evaluation = $test_values_evaluation;

        $breadcrumb = array();
        $breadcrumb['Recursos'] = $this->view->url(array(), 'resources_list');
        $breadcrumb['Evaluaciones'] = $this->view->url(array('filter' => 'evaluations'), 'resources_filtered');
        $breadcrumb[$evaluation->label] = $this->view->url(array('evaluation' => $evaluation->ident), 'evaluations_evaluation_view');
        $this->breadcrumb($breadcrumb);
    }

    // FIXME Agregar restricciones de historial
    public function addAction() {
        $this->requirePermission('subjects', 'teach');
        $request = $this->getRequest();

        $url_evaluation = $request->getParam('evaluation');

        $model_evaluations = new Evaluations();
        $evaluation = $model_evaluations->findByIdent($url_evaluation);

        $this->requireExistence($evaluation, 'evaluation', 'evaluations_evaluation_view', 'resources_list');

        if ($evaluation->author != $this->user->ident) {
            $this->_redirect('/', array('prependBase' => true));
        }

        $empty_test_evaluation = new Evaluations_Tests_Empty();
        $this->view->test_evaluation = $empty_test_evaluation;

        if ($request->isPost()) {
            $model_evaluations_tests = new Evaluations_Tests();
            $test_evaluation = $model_evaluations_tests->createRow();
            $test_evaluation->label = $request->getParam('label');
            $test_evaluation->key = $request->getParam('key');
            $test_evaluation->minimumnote = $request->getParam('minimum');
            $test_evaluation->defaultnote = $request->getParam('default');
            $test_evaluation->maximumnote = $request->getParam('maximum');
            $test_evaluation->formula = $request->getParam('formula');
            $test_evaluation->order = $request->getParam('order');
            $test_evaluation->evaluation = $evaluation->ident;

            if ($test_evaluation->isValid()) {
                if ($test_evaluation->minimumnote <= $test_evaluation->defaultnote && $test_evaluation->defaultnote <=  $test_evaluation->maximumnote) {
                    $parser = new Evaluations_Parser($evaluation->ident);
                    $parser->mode = 'TEST';
                    $parsing_result = $parser->parse($test_evaluation->formula);
                    if (!empty($parsing_result)) {
                        $test_evaluation->save();
                        $evaluation->checkUseful();
                        $this->_helper->flashMessenger->addMessage('Se ha agregado una nueva calificación');
                    }
                } else {
                    $this->_helper->flashMessenger->addMessage('Los rangos de notas no son adecuados');
                    $this->view->test_evaluation = $test_evaluation;
                }
            } else {
                foreach ($test_evaluation->getMessages() as $message) {
                    $this->_helper->flashMessenger->addMessage($message);
                }
                $this->view->test_evaluation = $test_evaluation;
            }
        } else {
            $this->history('evaluations/' . $evaluation->ident . '/add');
        }

        $tests_evaluation = $evaluation->findEvaluations_Tests($evaluation->select()->order('order ASC'));

        $this->view->model_evaluations = $model_evaluations;
        $this->view->evaluation = $evaluation;
        $this->view->tests_evaluation = $tests_evaluation;

        $breadcrumb = array();
        $breadcrumb['Recursos'] = $this->view->url(array(), 'resources_list');
        $breadcrumb['Evaluaciones'] = $this->view->url(array('filter' => 'evaluations'), 'resources_filtered');
        $breadcrumb[$evaluation->label] = $this->view->url(array('evaluation' => $evaluation->ident), 'evaluations_evaluation_view');
        $this->breadcrumb($breadcrumb);
    }

    // FIXME Agregar restricciones de historial
    public function deleteAction() {
        $this->requirePermission('subjects', 'teach');
        $request = $this->getRequest();

        $url_evaluation = $request->getParam('evaluation');
        $url_test_evaluation = $request->getParam('test');

        $model_evaluations = new Evaluations();
        $evaluation = $model_evaluations->findByIdent($url_evaluation);

        $this->requireExistence($evaluation, 'evaluation', 'evaluations_evaluation_view', 'resources_list');

        if ($evaluation->author != $this->user->ident) {
            $this->_redirect('/', array('prependBase' => true));
        }

        $model_evaluations_tests = new Evaluations_Tests();
        $test_evaluation = $model_evaluations_tests->findByIdent($url_test_evaluation);

        if (!empty($test_evaluation)) {
            if ($evaluation->author == $this->user->ident) {
                $test_evaluation->delete();
                $evaluation->checkUseful();
                $this->_helper->flashMessenger->addMessage('La calificacion ha sido eliminada');
            } else {
                $this->_helper->flashMessenger->addMessage('Usted no puede eliminar la calificacion');
            }
        }

        $this->_redirect($this->view->currentPage());
    }
}
