<?php

/*//////////////////////////////////////////////////////////
//                                                        //
//  Clase:  Conector Mysql,Pgsql,Oracle,ODBC,Informix     //
//  Desarrollador:  Andrés Felipe Parra Ferreira          //
//  Fecha:     08-06-2017                                 //
//  Descripción:   Conector PDO Multi Base de Datos       //
//  WebSite: http://www.wookplay.com                      //
//////////////////////////////////////////////////////////*/

    /*DEFINIR CONFIGURACIÓN PHP*/
        error_reporting(E_ERROR | E_PARSE | E_NOTICE);  /*IGNORAMOS ERRORES QUE NO DESEAMOS VER*/
        ini_set('memory_limit', '512M');                /*DEFINIMOS LA CANTIDAD DE MEMORIA QUE QUEDEMOS TENER DISPONIBLE*/
        set_time_limit (0);                             /*DESACTIVAMOS EL TIEMPO LIMITE DE EJECUCIÓN DEL SCRIPT*/
    /*END DEFINIR CONFIGURACIÓN PHP*/

    /*INICIAMOS LA ESTRUCTURA DE LA CLASE*/
        class DataBase{

            /*CREAMOS EL CONSTRUCTOR DE LA CLASE*/
            public function __construct($DB_TIPE,$DB_HOST,$DB_NAME,$DB_USER,$DB_PASS,$DB_PORT,$DB_SERVICE = '',$DB_SERVER = '') {
                /*INICIALIZAMOS LAS VARIABLES DE LA CLASE*/
                    $this->tipo        = $DB_TIPE;                                          /*ALMACENAMOS EL TIPO DE CONEXIÓN*/
                    $this->host        = $DB_HOST;                                          /*ALMACENAMOS EL HOST DE CONEXIÓN*/
                    $this->dbname      = $DB_NAME;                                          /*ALMACENAMOS EL NOMBRE DE LA BASE DE DATOS*/
                    $this->username    = $DB_USER;                                          /*ALMACENAMOS EL NOMBRE DE USUARIO DE LA BASE DE DATOS*/
                    $this->pass        = $DB_PASS;                                          /*ALMACENAMOS EL PASSWORD DEL USUARIO DE LA BASE DE DATOS*/
                    $this->port        = $DB_PORT;                                          /*ALMACENAMOS EL PUERTO DE CONEXIÓN DE LA BASE DE DATOS*/
                    $this->service     = $DB_SERVICE;                                          /*ALMACENAMOS EL PUERTO DE CONEXIÓN DE LA BASE DE DATOS*/
                    $this->server      = $DB_SERVER;                                          /*ALMACENAMOS EL PUERTO DE CONEXIÓN DE LA BASE DE DATOS*/
                    $this->db_tns      = "(DESCRIPTION = (ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)(HOST = $this->host)(PORT = $this->port))) (CONNECT_DATA = (SERVICE_NAME = $this->service)))";
                    $this->ok_msj      = "OK";                                              /*DEFINIMOS MENSAJE DE OK DE CONEXIÓN POR DEFECTO*/
                    $this->error_msj   = "Error en la Conexión: ";                          /*DEFINIMOS MENSAJE DE ERROR DE CONEXIÓN POR DEFECTO*/
                    $this->tip         = array('mysql','pgsql','odbc','oracle','informix');      /*DEFINIMOS LOS TIPOS DE CONEXIONES PERMITIDAS*/
                    $this->conexion();                                                      /*ALMACENAMOS LA CONEXIÓN A LA BASE DE DATOS*/
                /*END INICIALIZAMOS LAS VARIABLES DE LA CLASE*/
            }
            /*END CREAMOS EL CONSTRUCTOR DE LA CLASE*/

            /*FUNCIÓN DE CONEXIÓN*/
                public function conexion(){
                    switch ($this->tipo) {
                        case $this->tip[0]:
                        case $this->tip[1]:
                            /*REALIZAMOS LA CONEXIÓN PDO (MYSQL, POSGRESQL)*/
                                try {
                                    $DSN            = ($this->port == '') ? $this->tipo.':dbname='.$this->dbname.';host='.$this->host : $this->tipo.':dbname='.$this->dbname.';port='.$this->port.';host='.$this->host;    /*PREPARAMOS EL DSN DE CONEXIÓN*/
                                    $this->conexion = new PDO($DSN, $this->username, $this->pass);                  /*CREANDO CONEXIÓN*/
                                    $this->conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);       /*CONFIGURACIÓN DE LAS EXEPCIONES DE ERROR PDO*/
                                    return $this->ok_msj;                                                           /*RETORNAMOS SI LA CONEXIÓN FUE EXITOSA*/
                                } catch(PDOException $e) {
                                    echo $this->error_msj . $e->getMessage();                                     /*RETORNAMOS SI SE PRESENTA ERROR AL CONECTAR*/
                                }
                            /*REALIZAMOS LA CONEXIÓN PDO (MYSQL, POSGRESQL)*/
                        case $this->tip[2]:
                            /*REALIZAMOS LA CONEXIÓN PDO (ODBC)*/
                                try {

                                    $this->conexion = new PDO($this->tipo.":".$this->host, $this->username, $this->pass);   /*CREANDO CONEXIÓN*/
                                    $this->conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);               /*CONFIGURACIÓN DE LAS EXEPCIONES DE ERROR PDO*/
                                    return $this->ok_msj;                                                                   /*RETORNAMOS SI LA CONEXIÓN FUE EXITOSA*/
                                } catch(PDOException $e) {
                                    return $this->error_msj . $e->getMessage();                                             /*RETORNAMOS SI SE PRESENTA ERROR AL CONECTAR*/
                                }
                            /*REALIZAMOS LA CONEXIÓN PDO (ODBC)*/
                        case $this->tip[3]:
                                  /*PREPARAMOS EL TNS DE CONEXIÓN ORACLE*/
                            /*REALIZAMOS LA CONEXIÓN PDO (ORACLE)*/
                                try{
                                    $this->conexion = new PDO("oci:dbname=".$this->db_tns,$this->username,$this->pass);
                                    $this->conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                                    return $this->ok_msj;
                                }catch(PDOException $e){
                                    return $this->error_msj . $e->getMessage();
                                }
                            /*END REALIZAMOS LA CONEXIÓN PDO  (ORACLE)*/
                        case $this->tip[4]:
                            try{
                                $this->conexion = new PDO("informix:host=".$this->host.";service=".$this->service.";database=".$this->dbname.";server=".$this->server."; protocol=onsoctcp;EnableScrollableCursors=1;DB_LOCALE=en_US.819;CLIENT_LOCALE=en_US.CP1252", $this->username, $this->pass);
                                $this->conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                                return $this->conexion;
                            }
                            catch(PDOException $e){
                                echo "Error: ".$e->getMessage();
                            }
                            break;
                    }
                }
            /*FUNCIÓN DE CONEXIÓN*/

            /*FUNCIÓN PARA REFRESCAR LA CONEXIÓN*/
                function refresh(){
                    unset($this->conexion);
                    $this->conexion = '';
                    $this->conexion();
                }
            /*END FUNCIÓN PARA REFRESCAR LA CONEXIÓN*/

            /*FUNCIÓN DE CREACIÓN SQL INSERT*/
                function sql_insert($tabla,$datos){
                    ksort($datos);
                    $campos     = implode('`, `',  array_keys($datos));
                    $registros  = "" . implode("', '",  array_values($datos));
                    $sql        = "INSERT INTO $tabla (`$campos`) VALUES ('$registros')";
                    return $sql;
                }
            /*END FUNCIÓN DE CREACIÓN SQL INSERT*/

            /*FUNCIÓN DE CREACIÓN SQL UPDATE*/
                function sql_update($tabla,$datos,$where, $tip = ''){
                    $wer = '';
                    if(is_array($where)){
                      foreach ($where as $clave=>$valor){ $wer.= $clave."='".$valor."' AND "; }
                      $wer   = substr($wer, 0, -4);
                      $where = $wer;
                    }
                    ksort($datos);
                    $datos_update = NULL;
                    foreach ($datos as $key => $values){
                        $datos_update .= (is_string($values)) ? "$key='$values'," : "$key=$values,";
                    }
                    $datos_update   = rtrim($datos_update,',');
                    $sql            = ($where == NULL || $where == '') ? "UPDATE $tabla SET $datos_update" :  $sql = "UPDATE $tabla SET $datos_update WHERE $where";
                    return $sql;
                }
            /*END FUNCIÓN DE CREACIÓN SQL UPDATE*/

            /*FUNCION DE CREACIÓN SQL DELETE*/
                function sql_delete($tabla,$where = ''){
                    $wer = '';
                    if(is_array($where)){
                      foreach ($where as $clave=>$valor){
                        $wer.= $clave."='".$valor."' and ";
                      }
                      $wer   = substr($wer, 0, -4);
                      $where = $wer;
                    }
                    $sql = ($where==NULL || $where=='') ? "DELETE FROM $tabla" : "DELETE FROM $tabla WHERE $where";
                    return $sql;
                }
            /*END FUNCION DE CREACIÓN SQL DELETE*/

            /*FUNCIÓN SELECT*/
                public function select($sql){
                    switch ($this->tipo) {
                        case $this->tip[0]:
                        case $this->tip[1]:
                        case $this->tip[2]:
                        case $this->tip[3]:
                        case $this->tip[4]:
                            $this->refresh();                           /*REFRESCAMOS LA CONEXIÓN*/
                            echo $sql;
                            $prep   = $this->conexion->prepare($sql);   /*PREPARAMOS LA CONSULTA*/
                            $prep->execute();                           /*EJECUTAMOS LA CONSULTA*/
                            $result = $prep->fetchAll(PDO::FETCH_ASSOC);/*ALMACENAMOS EL RESULTADO*/
                            unset($this->conexion);                     /*DESCONECTAMOS*/
                            $this->conexion = '';                       /*DESCONECTAMOS*/
                            return $result;                             /*DEVOLVEMOS EL RESULTADO*/
                    }
                }
            /*END FUNCIÓN SELECT*/

            /*FUNCIÓN INSERT*/
                public function insert($tabla,$datos){

                    $sql    = $this->sql_insert($tabla,$datos);
                    //echo $sql;
                    switch ($this->tipo) {
                        case $this->tip[0]:
                        case $this->tip[1]:
                        case $this->tip[2]:
                        case $this->tip[3]:
                        case $this->tip[4]:
                            $this->refresh();                           /*REFRESCAMOS LA CONEXIÓN*/
                            $prep   = $this->conexion->prepare($sql);   /*PREPARAMOS LA CONSULTA*/

                            if(!$prep->execute()){
                                unset($this->conexion);                     /*DESCONECTAMOS*/
                                $this->conexion = '';                       /*DESCONECTAMOS*/
                                return 'ERROR';
                            }else{
                                return 'OK';
                            }
                    }
                }
            /*END FUNCIÓN INSERT*/

            /*FUNCIÓN UPDATE*/
                public function update($tabla,$datos,$where = '',$tip = ''){

                    $sql    = $this->sql_update($tabla,$datos,$where,$tip);

                    switch ($this->tipo) {
                        case $this->tip[0]:
                        case $this->tip[1]:
                        case $this->tip[2]:
                        case $this->tip[3]:
                        case $this->tip[4]:
                            $this->refresh();                           /*REFRESCAMOS LA CONEXIÓN*/
                            $prep   = $this->conexion->prepare($sql);   /*PREPARAMOS LA CONSULTA*/

                            if($prep->execute()){
                                return 'OK';
                            }else{
                                unset($this->conexion);                     /*DESCONECTAMOS*/
                                $this->conexion = '';                       /*DESCONECTAMOS*/
                                return 'ERROR';
                            }
                    }
                }
            /*END FUNCIÓN UPDATE*/

            /*FUNCIÓN DELETE*/
                public function delete($tabla,$where = ''){

                    $sql    = $this->sql_delete($tabla,$where);

                    switch ($this->tipo) {
                        case $this->tip[0]:
                        case $this->tip[1]:
                        case $this->tip[2]:
                        case $this->tip[3]:
                        case $this->tip[4]:
                            $this->refresh();                           /*REFRESCAMOS LA CONEXIÓN*/
                            $prep   = $this->conexion->prepare($sql);   /*PREPARAMOS LA CONSULTA*/

                            if($prep->execute()){
                                return 'OK';
                            }else{
                                unset($this->conexion);                     /*DESCONECTAMOS*/
                                $this->conexion = '';                       /*DESCONECTAMOS*/
                                return 'ERROR';
                            }
                    }
                }
            /*END FUNCIÓN DELETE*/
        }
    /*END INICIAMOS LA ESTRUCTURA DE LA CLASE*/
