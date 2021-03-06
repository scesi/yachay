<?php

class Teams_MemberController extends Yachay_Controller_Action
{
    public function deleteAction() {
        $this->requirePermission('subjects', 'view');
        $request = $this->getRequest();

        $model_gestions = new Gestions();
        $gestion = $model_gestions->findByActive();

        $model_subjects = new Subjects();
        $url_subject = $request->getParam('subject');
        $subject = $model_subjects->findByUrl($url_subject, $gestion->ident);
        $this->requireExistence($subject, 'subject', 'subjects_subject_view', 'subjects_list');

        $model_groups = new Groups();
        $url_group = $request->getParam('group');
        $group = $model_groups->findByUrl($url_group, $subject->ident);
        $this->requireExistenceGroup($group, $subject);

        $model_teams = new Teams();
        $url_team = $request->getParam('team');
        $team = $model_teams->findByUrl($url_team, $group->ident);
        $this->requireExistenceTeam($team, $group, $subject);
        $this->requireMemberTeam($team);

        $model_users = new Users();
        $url_user = $request->getParam('user');
        $user = $model_users->findByUrl($url_user);
        $this->requireExistence($user, 'user', 'users_user_view', 'users_list');

        $assignement = new Teams_Users();
        $assign = $assignement->findByTeamAndUser($team->ident, $user->ident);
        $assign->delete();

        $this->_helper->flashMessenger->addMessage('El usuario ' . $user->label . ' ha sido retirado del equipo');
        $this->_redirect($this->view->currentPage());
    }
}
