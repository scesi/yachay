
/*============================================================================*/
/* Informacion necesaria para el manejo de valoraciones de los usuarios       */
/*============================================================================*/

/*============================================================================*/
/* Registro del paquete                                                       */
/*============================================================================*/
INSERT INTO `package`
(`label`, `url`, `type`, `dependency`, `tsregister`, `description`)
VALUES
('valorations', 'valorations', 'util', 'resources', UNIX_TIMESTAMP(), 'Modulo manejador de las valoraciones de los usuarios del sistema');
