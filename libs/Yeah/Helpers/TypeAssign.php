<?php

class Yeah_Helpers_TypeAssign
{
    public function typeAssign($val) {
        switch ($val) {
            case 'teacher':
                return 'Docente';
            case 'auxiliar':
                return 'Auxiliar';
            case 'student':
                return 'Estudiante';
            case 'guest':
                return 'Visitante';
        }
    }
}