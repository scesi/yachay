<p>Usted ha pedido una renovación de su contraseña en el servidor <?php echo $this->servername ?>.</p>
<p>Ingrese la siguiente contraseña en el formulario de acceso:</p>
<center>
    <?php echo $this->code ?>
</center>
<p>Recuerde que esta contraseña sirve para un acceso y tiene una validez de 24 horas a partir de la fecha de petición.</p>
<p>
    <i><b>Fecha de petición:</b>&nbsp;<?php echo $this->timestamp($this->petition) ?></i>
    <br/>
    <i><b>Fecha de expiración:</b>&nbsp;<?php echo $this->timestamp($this->expiration) ?></i>
</p>
