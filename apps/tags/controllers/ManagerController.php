<?php

class Tags_ManagerController extends Yachay_Controller_Action
{
    public function indexAction() {
        $this->requirePermission('tags', 'list');
        $this->requirePermission('tags', 'delete');

        $model_tags = new Tags();

        $request = $this->getRequest();
        if ($request->isPost()) {
            $delete = $request->getParam('delete');
            if (!empty($delete)) {
                $this->_forward('delete');
            }
        } else {
            $this->history('tags/manager');
        }

        $this->view->model_tags = $model_tags;
        $this->view->tags = $model_tags->selectAll();

        $breadcrumb = array();
        if ($this->acl('tags', 'list')) {
            $breadcrumb['Etiquetas'] = $this->view->url(array(), 'tags_list');
        }
        $this->breadcrumb($breadcrumb);
    }

    public function deleteAction() {
        $this->requirePermission('tags', 'delete');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $model_tags = new Tags();
            $model_tags_resources = new Tags_Resources();
            $model_tags_communities = new Tags_Communities();
            $model_tags_users = new Tags_Users();

            $check = $request->getParam("check");

            $count = 0;
            foreach ($check as $value) {
                $tag = $model_tags->findByIdent($value);
                if (!empty($tag)) {
                    $model_tags_resources->deleteResourcesInTag($tag->ident);
                    $model_tags_communities->deleteCommunitiesInTag($tag->ident);
                    $model_tags_users->deleteUsersInTag($tag->ident);

                    $tag->delete();
                    $count++;
                }
            }

            $this->_helper->flashMessenger->addMessage("Se han eliminado $count etiquetas");
        }

        $this->_redirect($this->view->currentPage());
    }
}
