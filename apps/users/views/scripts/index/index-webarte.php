<h1><?php echo $this->route->label ?></h1>

<?php if (count($this->users)) { ?>
    <?php echo $this->paginator($this->users, $this->pager) ?>
    <div id="block">
    <?php foreach ($this->users as $user) { ?>
        <?php echo $this->partial('index/user-webarte.php', array('user' => $user, 'me' => $this->user, 'model_friends' => $this->model_friends, 'config' => $this->config, 'template' => $this->template)) ?>
    <?php } ?>
    </div>
    <div class="clear"></div>
    <?php echo $this->paginator($this->users, $this->pager) ?>
<?php } else { ?>
    <p>No existen usuarios registrados</p>
<?php } ?>
