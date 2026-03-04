<?php
/**
 * Database Connection Class
 * Clase para manejar la conexión a la base de datos del sistema
 */

class Database {
    private $connection;
    private $config;
    
    public function __construct() {
        $this->config = require __DIR__ . '/../config/database.php';
        $this->connect();
    }
    
    /**
     * Establece la conexión con la base de datos
     */
    private function connect() {
        try {
            $this->connection = new mysqli(
                $this->config['host'],
                $this->config['username'],
                $this->config['password'],
                $this->config['database'],
                $this->config['port']
            );
            
            if ($this->connection->connect_error) {
                throw new Exception("Error de conexión: " . $this->connection->connect_error);
            }
            
            // Configurar charset
            $this->connection->set_charset($this->config['charset']);
            
        } catch (Exception $e) {
            die("Error al conectar a la base de datos: " . $e->getMessage());
        }
    }
    
    /**
     * Obtiene la conexión activa
     * @return mysqli
     */
    public function getConnection() {
        return $this->connection;
    }
    
    /**
     * Ejecuta una consulta SELECT
     * @param string $query
     * @return array
     */
    public function select($query) {
        $result = $this->connection->query($query);
        
        if (!$result) {
            return [];
        }
        
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        
        return $data;
    }
    
    /**
     * Ejecuta una consulta INSERT, UPDATE o DELETE
     * @param string $query
     * @return bool
     */
    public function execute($query) {
        return $this->connection->query($query);
    }
    
    /**
     * Obtiene el ID del último registro insertado
     * @return int
     */
    public function lastInsertId() {
        return $this->connection->insert_id;
    }
    
    /**
     * Escapa una cadena para prevenir SQL injection
     * @param string $string
     * @return string
     */
    public function escape($string) {
        return $this->connection->real_escape_string($string);
    }
    
    /**
     * Cierra la conexión
     */
    public function close() {
        if ($this->connection) {
            $this->connection->close();
        }
    }
    
    /**
     * Destructor - cierra la conexión automáticamente
     */
    public function __destruct() {
        $this->close();
    }
}
