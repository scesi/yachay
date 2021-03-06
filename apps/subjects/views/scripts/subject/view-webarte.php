<h1><?php echo $this->subject->label ?>
<strong class="task">
<?php if ($this->subject->status == 'active') { ?>
    <img src="<?php echo $this->template->htmlbase . 'images/tick.png' ?>" alt="Materia activa" title="Materia activa" />
<?php } else { ?>
    <img src="<?php echo $this->template->htmlbase . 'images/cross.png' ?>" alt="Materia inactiva" title="Materia inactiva" />
<?php } ?>
<?php if (!$this->historial && ($this->subject->amModerator() || $this->subject->amMember())) { ?>
    <a href="<?php echo $this->url(array('subject' => $this->subject->url), 'subjects_subject_assign') ?>"><img src="<?php echo $this->template->htmlbase . 'images/group.png' ?>" alt="Ver miembros" title="Ver miembros" /></a>
<?php } ?>
<?php if (!$this->historial && $this->acl('subjects', 'edit')) { ?>
    <a href="<?php echo $this->url(array('subject' => $this->subject->url), 'subjects_subject_edit') ?>"><img src="<?php echo $this->template->htmlbase . 'images/pencil.png' ?>" alt="Editar" title="Editar" /></a>
<?php } ?>
</strong>
</h1>
<?php if (!empty($this->gestion)) { ?><p><span class="mark">Gestion: </span><?php echo $this->gestion->label ?></p><?php } ?>

<p><?php echo $this->subject->description ?></p>

<h2>Areas involucradas
<strong class="task">
<?php if (!$this->historial && $this->acl('areas', array('new', 'delete'))) { ?>
    <a href="<?php echo $this->url(array(), 'areas_manager') ?>"><img src="<?php echo $this->template->htmlbase . 'images/key.png' ?>" alt="Administrador de areas" title="Administrador de areas" /></a>
<?php } ?>
</strong>
</h2>
<?php if (count($this->areas)) { ?>
<ul>
<?php foreach ($this->areas as $area) { ?>
    <li>
        <a href="<?php echo $this->url(array('area' => $area->url), 'areas_area_view') ?>"><?php echo $area->label ?></a>
    </li>
<?php } ?>
</ul>
<?php } else { ?>
<p>La materia no se encuentra registrada en ningun area.</p>
<?php } ?>

<?php if (!$this->historial) { ?>
    <h2>Grupos registrados
        <strong class="task">
    <?php if ($this->subject->amModerator()) { ?>
        <a href="<?php echo $this->url(array('subject' => $this->subject->url), 'groups_manager') ?>"><img src="<?php echo $this->template->htmlbase . 'images/key.png' ?>" alt="Administrador de grupos" title="Administrador de grupos" /></a>
    <?php } ?>
        </strong>
    </h2>

    <?php if (count($this->groups)) { ?>
    <ul>
        <?php foreach ($this->groups as $group) { ?>
        <li>
            <a href="<?php echo $this->url(array('subject' => $this->subject->url, 'group' => $group->url), 'groups_group_view') ?>">Grupo <?php echo $group->label ?></a>: <?php echo $group->getTeacher()->getFullName() ?>
            <?php $assign = $this->model_groups_users->findByGroupAndUser($group->ident, $this->user->ident); ?>
            <?php if (!empty($assign)) { ?>
                <span class="mark"><?php echo $this->typeAssign($assign->type) ?></span>
            <?php } ?>
        </li>
        <?php } ?>
    </ul>
    <?php } else { ?>
        <p>La materia no se posee ningun grupo registrado.</p>
    <?php } ?>
<?php } ?>

<?php echo $this->partial($this->template('resources', 'resource'), array('resources' => $this->resources, 'pager' => $this->pager, 'config' => $this->config, 'template' => $this->template, 'paginator' => true,)) ?>
