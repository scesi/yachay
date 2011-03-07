<h1><?= $this->PAGE->label ?></h1>

<?php foreach ($this->templates as $template) { ?>
    <h2>
        <?= $template->label ?>
        <strong class="task">
            <?php if ($this->USER->template == $template->label) { ?>
                <img src="<?= $this->TEMPLATE->htmlbase . 'images/tick.png' ?>" alt="Tema actual" title="Tema actual" />
                <?php if ($this->acl('templates', 'configure')) { ?>
                    <a href="<?= $this->url(array('template' => $template->label), 'templates_template_view') ?>"><img src="<?= $this->TEMPLATE->htmlbase . 'images/wrench.png' ?>" alt="Configurar" title="Configurar" /></a>
                <?php } ?>
            <?php } else { ?>
                <a href="<?= $this->url(array('template' => $template->label), 'templates_template_switch') ?>"><img src="<?= $this->TEMPLATE->htmlbase . 'images/paintcan.png' ?>" alt="Usar" title="Usar" /></a>
            <?php } ?>
        </strong>
    </h2>
    <p><?= $template->description ?></p>
<?php } ?>
