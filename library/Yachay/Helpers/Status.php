<?php

class Yachay_Helpers_Status
{
    public function status($val) {
        switch ($val) {
            case 'active':
                return 'Activo';
            case 'inactive':
                return 'Inactivo';
        }
    }
}
