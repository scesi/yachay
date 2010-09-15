<h1>Administrador de usuarios</h1>
<?php global $USER; ?>
<form method="post" action="" accept-charset="utf-8">
    <input type="hidden" name="return" value="<?= $this->currentPage() ?>" />

    <table>
        <tr>
        <?php if (Yeah_Acl::hasPermission('users', 'list')) { ?>
            <td>[<a href="<?= $this->url(array(), 'users_list') ?>">Lista</a>]</td>
        <?php } ?>
        <?php if (Yeah_Acl::hasPermission('users', 'new')) { ?>
            <td>[<a href="<?= $this->url(array(), 'users_new') ?>">Nuevo</a>]</td>
        <?php } ?>
        <?php if (Yeah_Acl::hasPermission('users', 'lock')) { ?>
            <td><input type="submit" name="lock" value="Bloquear" /></td>
            <td><input type="submit" name="unlock" value="Desbloquear" /></td>
        <?php } ?>
        <?php if (Yeah_Acl::hasPermission('users', 'delete')) { ?>
            <td><input type="submit" name="delete" value="Eliminar" /></td>
        <?php } ?>
        <?php if (Yeah_Acl::hasPermission('users', 'import')) { ?>
            <td>[<a href="<?= $this->url(array(), 'users_import') ?>">Importar</a>]</td>
        <?php } ?>
        <?php if (Yeah_Acl::hasPermission('users', 'export')) { ?>
            <td>[<a href="<?= $this->url(array(), 'users_export') ?>">Exportar</a>]</td>
        <?php } ?>
        </tr>
    </table>

    <hr />
<?php if (count($this->users)) { ?>
    <center>
        <table width="100%">
            <tr>
                <th>&nbsp;</th>
                <th><?= $this->utf2html($this->model->_mapping['label']) ?></th>
                <th>Nombre Completo</th>
                <th>Opciones</th>
                <th><?= $this->utf2html($this->model->_mapping['tsregister']) ?></th>
            </tr>
        <?php foreach ($this->users as $user) { ?>
            <tr>
                <td>
                <?php if (Yeah_Acl::hasPermission('users', array('lock', 'delete'))) { ?>
                    <input type="checkbox" name="check[]" value="<?= $user->ident ?>" />
                <?php } ?>
                </td>
                <td><?= $this->utf2html($user->label) ?></td>
                <td><?= $this->utf2html($user->getFullName()) ?></td>
                <td>
                <?php if (Yeah_Acl::hasPermission('users', 'view')) { ?>
                        <a href="<?= $this->url(array('user' => $user->url), 'users_user_view') ?>">Ver</a>
                <?php } ?>
                <?php if ($USER->hasFewerPrivileges($user)) { ?>
                    <?php if (Yeah_Acl::hasPermission('users', 'edit')) { ?>
                        <a href="<?= $this->url(array('user' => $user->url), 'users_user_edit') ?>">Editar</a>
                    <?php } ?>
                    <?php if (Yeah_Acl::hasPermission('users', 'lock')) { ?>
                        <?php if ($user->status == 'active') { ?>
                            <a href="<?= $this->url(array('user' => $user->url), 'users_user_lock') ?>">Bloquear</a>
                        <?php } else { ?>
                            <a href="<?= $this->url(array('user' => $user->url), 'users_user_unlock') ?>">Desbloquear</a>
                        <?php } ?>
                    <?php } ?>
                    <?php if (Yeah_Acl::hasPermission('users', 'delete')) { ?>
                        <a href="<?= $this->url(array('user' => $user->url), 'users_user_delete') ?>">Eliminar</a>
                    <?php } ?>
                <?php } ?>
                </td>
                <td><center><?= $this->timestamp($user->tsregister) ?></center></td>
            </tr>
        <?php } ?>
        </table>
    </center>
<?php } else { ?>
    <p>No existen usuarios registrados</p>
<?php } ?>
    <hr />

    <table>
        <tr>
        <?php if (Yeah_Acl::hasPermission('users', 'list')) { ?>
            <td>[<a href="<?= $this->url(array(), 'users_list') ?>">Lista</a>]</td>
        <?php } ?>
        <?php if (Yeah_Acl::hasPermission('users', 'new')) { ?>
            <td>[<a href="<?= $this->url(array(), 'users_new') ?>">Nuevo</a>]</td>
        <?php } ?>
        <?php if (Yeah_Acl::hasPermission('users', 'lock')) { ?>
            <td><input type="submit" name="lock" value="Bloquear" /></td>
            <td><input type="submit" name="unlock" value="Desbloquear" /></td>
        <?php } ?>
        <?php if (Yeah_Acl::hasPermission('users', 'delete')) { ?>
            <td><input type="submit" name="delete" value="Eliminar" /></td>
        <?php } ?>
        <?php if (Yeah_Acl::hasPermission('users', 'import')) { ?>
            <td>[<a href="<?= $this->url(array(), 'users_import') ?>">Importar</a>]</td>
        <?php } ?>
        <?php if (Yeah_Acl::hasPermission('users', 'export')) { ?>
            <td>[<a href="<?= $this->url(array(), 'users_export') ?>">Exportar</a>]</td>
        <?php } ?>
        </tr>
    </table>
</form>
