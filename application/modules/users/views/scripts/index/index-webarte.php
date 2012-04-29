<h1><?php echo $this->PAGE->label ?></h1>

<?php if (count($this->users)) { ?>
    <?php echo $this->paginator($this->users, $this->route) ?>
    <div id="block">
    <?php foreach ($this->users as $user) { ?>
        <?php echo $this->partial('index/user-webarte.php', array('user' => $user, 'USER' => $this->USER, 'model_friends' => $this->model_friends, 'config' => $this->config, 'TEMPLATE' => $this->TEMPLATE)) ?>
    <?php } ?>
    </div>
    <div class="clear"></div>
    <?php echo $this->paginator($this->users, $this->route) ?>
<?php } else { ?>
    <p>No existen usuarios registrados</p>
<?php } ?>
