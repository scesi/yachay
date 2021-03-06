<p>
    Estimado usuario, propietario del correo <?php echo $this->user->email ?>, usted ha sido agregado como usuario en la pagina
    <?php echo $this->servername ?>, red social para tematicas academicas, si usted no esta involucrado con este servidor en ninguna
    forma, es probable que se haya cometido un error, para lo cual solo debe ignorar este correo, en este momento.
</p>
<br />
<p>
    Si por el contrario, es el correo que ha estado esperando toda su vida, a continuaci&oacute;n le damos cierta
    informaci&oacute;n que ha usted posiblemente le gustaria saber: 
</p>
<br />
    <center>
        <table>
            <tr><td colspan="2"><b>Informacion de registro:</b></td></tr>
            <tr>
                <td>Fecha de registro:</td>
                <td><?php echo $this->timestamp($this->user->tsregister) ?></td>
            </tr>
        <?php if (!empty($this->author)) { ?>
            <tr>
                <td>Autor del registro:</td>
                <td><?php echo $this->author ?></td>
            </tr>
        <?php } ?>
            <tr>
                <td>Rol utilizado:</td>
                <td><?php echo $this->user->getRole()->label ?></td>
            </tr>
            <tr>
                <td colspan="2"><b>Informaci&oacute;n que fue establecida, pero que puede cambiar:</b></td>
            </tr>
            <tr>
                <td>Apellido utilizado:</td>
                <td><?php echo $this->none($this->user->surname) ?></td>
            </tr>
            <tr>
                <td>Nombre utilizado:</td>
                <td><?php echo $this->none($this->user->name) ?></td>
            </tr>
            <tr>
                <td>Carrera utilizada:</td>
                <td><?php echo $this->none($this->user->getCareer()->label) ?></td>
            </tr>
            <tr>
                <td colspan="2"><b>Informaci&oacute;n de acceso:</b></td>
            </tr>
            <tr>
                <td>Usuario:</td>
                <td><?php echo $this->user->label ?></td>
            </tr>
            <tr>
                <td>Contrase&ntilde;a:</td>
                <td><?php echo $this->password ?></td>
            </tr>
        </table>
    </center>
<br />
<p>
    La informacion de acceso es de vital importancia para el uso y aprovechamiento del sistema, guardelas bien.
</p>
<br />
<p>
    Tambien se sugiere que cambie su contrase&ntilde;a en cuanto acceda al sistema.
</p>
