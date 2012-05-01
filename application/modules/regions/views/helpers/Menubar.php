<?php

class Regions_View_Helper_Menubar
{
    public function menubar($name, $value) {
        $model_regions = new Regions();
        $regions = $model_regions->selectByRegion('menubar');

        $empty = new Regions_Empty();

        $options = array();
        $options[] = '<option value="0">' . $empty->label . '</option>';

        foreach ($regions as $region) {
            $selected = '';
            if ($value->ident == $region->ident) {
                $selected = 'selected="selected" ';
            }
            $options[] = '<option ' . $selected . 'value="' . $region->ident . '">' . $region->label . '</option>';
        }

        return '<select name="'. $name . '">'. implode('', $options) . '</select>';
    }
}