# CLASS DE CONEXIÓN A BASE DE DATOS (PDO, IFX_INFORMIX)

Esta Clase de Conexión permite conectar a los siguientes motores de base de datos:

-PDO
    -Mysql
    -Posgresql
    -Odbc
    -Oracle

-IFX
    -Informix
    
**NOTA: **
Para poder utilizar la conexión ifx_informix, se debe ejecutar con la versión de PHP 5.2 ó inferior.

**EXTENSIONES DE PHP REQUERIDOS**
Para que la clase funcione correctamente se deben activar las siguientes extensiones de php:

    - extension=php_pdo_mysql.dll
    - extension=php_pdo_pgsql.dll
    - extension=php_pdo_odbc.dll
    - extension=php_pdo_oci.dll
 
 **EJEMPLOS DE USO**
 
```
<?php
    /*DEFINIMOS VARIABLES DE CONEXIÓN*/
        $DB_TIPE        = 'Tipo de Conexión';
        $DB_HOST        = 'IP';
        $DB_NAME        = 'Nombre de Base de Datos / Servicio';
        $DB_USER        = 'Nombre de usuario';
        $DB_PASS        = 'Contraseña de Usuario';
        $DB_PORT        = 'Puerto de Conexión';
        $DB_SERVICE     = 'Nombre del Servicio (Utilizado para Oracle e Informix)';
        $DB_SERVER      = 'Nombre del Servidor (Utilizado para Informix)';
    /*DEFINIMOS VARIABLES DE CONEXIÓN*/
     
    /*INCLUIMOS LA CLASE*/
        include 'class/DataBase/DB.class.php';
    /*END INCLUIMOS LA CLASE*/
     
    /*INICIALIZAMOS LA CLASE*/
     
        $CX = new DataBase ($DB_TIPE,$DB_HOST,$DB_NAME,$DB_USER,$DB_PASS,$DB_PORT);
         
    /*END INICIALIZAMOS LA CLASE*/
     
    /*Una vez iniciada la clase podemos ejecutar las funciones de SELECT, INSERT, UPDATE, DELETE*/
     
    /*EJEMPLO DE SELECT*/
        $sql        = "SELECT * FROM nombre_tabla ";  /*Consulta SQL select que deseamos consultar*/
        $resultado  = $CX -> select ($sql);           /*Si la consulta es exitosa, se regresara un arreglo con la información*/
        var_dump($resultado);
    /*END EJEMPLO DE SELECT*/
     
    /*EJEMPLO INSERT*/
        /*Realizamos un arreglo que almacenara el nombre del campo de la tabla de la base de datos y su respectivo valor a insertar*/
        $datos = [
            "campo1" => 'Valor1',
            "campo2" => 'Valor2'
        ];
        $insert = $CX -> insert ('Nombre_tabla',$datos); /*Devuelve valor 'Ok' ó 'ERROR'*/
    /*END EJEMPLO INSERT*/
     
    /*EJEMPLO UPDATE*/
        /*Realizamos un arreglo que almacenara el nombre del campo de la tabla de la base de datos y su respectivo valor a Actualizar*/
        $datos = [
            "campo1" => 'Valor1',
            "campo2" => 'Valor2'
        ];
        /*El where (Filtro) se puede realizar de 2 formas, ya sea por una cadena string indicando el where ó realizando un arreglo*/
            /*Arreglo*/
                $where = [
                    "campo1" => 'valor1',
                    "campo2" => 'Valor2'
                ];
            /*String*/
                $where = "campo1 = 'valor1' AND campo2 = 'valor2' ";
        $update = $CX -> update ('Nombre_tabla',$datos,$where); /*Devuelve valor 'Ok' ó 'ERROR'*/
        /*
             NOTA: El $where no es un valor obligatorio, si no necesita realizar filtro, solo no envie la variable en la función
        */
    /*END EJEMPLO UPDATE*/
     
    /*EJEMPLO DELETE*/
        /*El where (Filtro) se puede realizar de 2 formas, ya sea por una cadena string indicando el where ó realizando un arreglo*/
            /*Arreglo*/
                $where = [
                    "campo1" => 'valor1',
                    "campo2" => 'Valor2'
                ];
            /*String*/
                $where = "campo1 = 'valor1' AND campo2 = 'valor2' ";
        $delete = $CX -> delete ('Nombre_tabla',$datos,$where); /*Devuelve valor 'Ok' ó 'ERROR'*/
        /*
            NOTA: El $where no es un valor obligatorio, si no necesita realizar filtro, solo no envie la variable en la función
        */
    /*END EJEMPLO DELETE*/
 ?>
 ```