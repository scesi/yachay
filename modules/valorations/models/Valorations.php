<?php

class modules_valorations_models_Valorations
{
    public function addActivity($score) {
        global $USER;

        $model_users = Yeah_Adapter::getModel('users');
        $user = $model_users->findByIdent($USER->ident);

        $user->activity = $user->activity + $score;
        $user->save();
    }

    public function decreaseActivity($score, $user = NULL) {
        global $USER;

        $ident = $USER->ident;
        if (!empty($user)) {
            $ident = $user;
        }

        $model_users = Yeah_Adapter::getModel('users');
        $user = $model_users->findByIdent($ident);

        $user->activity = $user->activity - $score;
        $user->save();
    }

    public function addSociability($contact, $score1, $score2) {
        global $USER;

        $model_users = Yeah_Adapter::getModel('users');
        $user = $model_users->findByIdent($USER->ident);

        $user->sociability = $user->sociability + $score1;
        $user->save();

        $contact->sociability = $contact->sociability + $score2;
        $contact->save();
    }

    public function decreaseSociability($contact, $score1, $score2) {
        global $USER;

        $model_users = Yeah_Adapter::getModel('users');
        $user = $model_users->findByIdent($USER->ident);

        $user->sociability = $user->sociability - $score1;
        $user->save();

        $contact->sociability = $contact->sociability + $score2;
        $contact->save();
    }

    public function addParticipation($score) {
        global $USER;

        $model_users = Yeah_Adapter::getModel('users');
        $user = $model_users->findByIdent($USER->ident);

        $user->participation = $user->participation + $score;
        $user->save();
    }

    public function decreaseParticipation($score, $user = NULL) {
        global $USER;

        $ident = $USER->ident;
        if (!empty($user)) {
            $ident = $user;
        }

        $model_users = Yeah_Adapter::getModel('users');
        $user = $model_users->findByIdent($ident);

        $user->participation = $user->participation - $score;
        $user->save();
    }
}