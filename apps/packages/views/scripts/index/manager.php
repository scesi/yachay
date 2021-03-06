<?php

echo '<h1>' . $this->route->label . '</h1>';
echo '<form method="post" action="" accept-charset="utf-8">';
echo '<input type="hidden" name="return" value="' . $this->currentPage() . '" />';

echo '<table><tr>';
if (Yachay_Acl::hasPermission('packages', 'list')) {
    echo '<td>[<a href="' . $this->url(array(), 'packages_list') . '">Lista</a>]</td>';
}
if (Yachay_Acl::hasPermission('packages', 'new')) {
    echo '<td>[<a href="' . $this->url(array(), 'packages_new') . '">Nuevo</a>]</td>';
}
if (Yachay_Acl::hasPermission('packages', 'lock')) {
    echo '<td><input type="submit" name="unlock" value="Activar" /></td>';
    echo '<td><input type="submit" name="lock" value="Desactivar" /></td>';
}
echo '</tr></table>';

echo '<hr />';

echo '<center>';
echo '<table width="100%"><tr><th>&nbsp;</th>';
echo '<th>Paquete</th>';
echo '<th>Tipo</th>';
echo '<th>Opciones</th>';
echo '<th>Fecha de Registro</th>';
echo '</tr>';

foreach ($this->list as $node) {
    echo '<tr><td>';
    if (Yachay_Acl::hasPermission('packages', 'lock')) {
        echo '<input type="checkbox" name="check[]" value="' . $node->ident . '" />';
    }
    echo '</td>';
    echo '<td>' . str_repeat('&nbsp;', $node->level()) . $node->label . '</td>';
    echo '<td>' . $node->type . '</td>';
    echo '<td><center>';

    if (Yachay_Acl::hasPermission('packages', 'view')) {
        echo '<a href="' . $this->url(array('package' => $node->url), 'packages_package_view') . '">Ver</a> ';
    }
    if (Yachay_Acl::hasPermission('packages', 'lock')) {
        if ($node->status == 'active') {
            echo '<a href="' . $this->url(array('package' => $node->url), 'packages_package_lock') . '">Desactivar</a>';
        } else {
            echo '<a href="' . $this->url(array('package' => $node->url), 'packages_package_unlock') . '">Activar</a>';
        }
    }

    echo '</center></td>';
    echo '<td><center>' . $this->timestamp($node->tsregister) . '</center></td>';
    echo '</tr>';
}

echo '</table></center>';

echo '<hr />';

echo '<table><tr>';
if (Yachay_Acl::hasPermission('packages', 'list')) {
    echo '<td>[<a href="' . $this->url(array(), 'packages_list') . '">Lista</a>]</td>';
}
if (Yachay_Acl::hasPermission('packages', 'new')) {
    echo '<td>[<a href="' . $this->url(array(), 'packages_new') . '">Nuevo</a>]</td>';
}
if (Yachay_Acl::hasPermission('packages', 'lock')) {
    echo '<td><input type="submit" name="unlock" value="Activar" /></td>';
    echo '<td><input type="submit" name="lock" value="Desactivar" /></td>';
}
echo '</tr></table>';
echo '</form>';
