<?php
class DatabaseHelper{
    private $db;

    public function __construct(){
        $this->db = new mysqli("localhost", "root", "", "databaseistitutoperprove");
        if ($this->db->connect_error) {
            die("Connection failed: " . $this->db->connect_error);
        }        
    }

    /**
     * Esegue una query.
     * @param mixed $query la query da eseguire
     * @param mixed $params i parametri necessari ad essa
     * @throws \Exception
     * @return array<array|bool|null>
     */
    public function executeQuery($query, $params = []){
        try {
            $stmt = $this->db->prepare($query);
            if (!$stmt) {
                die("Errore nella query SQL: " . $this->db->error);
            }
            
            if (!empty($params)) {
                if(!is_array($params)){
                    $params = [$params];
                }

                //Stabilisce il parametro da utilizzare in base al tipo.
                $types = '';
                foreach ($params as $param) {
                    if (is_int($param)) {
                        $types .= 'i'; 
                    } elseif (is_float($param)) {
                        $types .= 'd'; 
                    } elseif (is_string($param)) {
                        $types .= 's'; 
                    } else {
                        $types .= 'b'; 
                    }
                }
                $stmt->bind_param($types, ...$params);
            }

            $stmt->execute();

            $result = $stmt->get_result();
            $results = [];
            while ($row = $result->fetch_assoc()) {
                $results[] = $row;
            }

            return $results;
        } catch (mysqli_sql_exception $e) {
            throw new Exception($e->getMessage());
        }
    }

  
    /**
     * Costruisce la query.
     * @param mixed $action
     * @param mixed $table
     * @param mixed $data
     * @param mixed $columns
     * @param mixed $condition
     * @param mixed $valuesForCondition
     * @throws \Exception
     * @return array{query: string, returningValuesForCondition: mixed}
     */
    public function buildQuery($action, $table, $data = [], $columns = '*', $condition = '', $valuesForCondition = []){
        $query = '';
        $returningValuesForCondition = [];

        switch($action) {
            case 'select':
                $query = "SELECT $columns FROM $table $condition";
                $returningValuesForCondition = $valuesForCondition;
                break;

            case 'insert':
                $keys = implode(', ', array_keys($data));
                $placeHolders = implode(', ', array_fill(0, count($data), '?'));
                $query = "INSERT INTO $table ($keys) VALUES ($placeHolders)";
                $returningValuesForCondition = array_values($data);
                break;

            case 'update':
                $setColumns = implode(', ', array_map(function($key) {
                    return "$key = ?";
                }, array_keys($data)));
                $query = "UPDATE $table SET $setColumns $condition";
                $returningValuesForCondition = array_values($data);
                break;

            case 'delete':
                $query = "DELETE FROM $table $condition";
                break;

            default:
                throw new Exception('Azione non valida');
        }

        return ['query' => $query, 'returningValuesForCondition' => $returningValuesForCondition];
    }

    /**
     * Esegue una query e restituisce il risultato
     * @param mixed $action
     * @param mixed $table
     * @param mixed $data
     * @param mixed $columns
     * @param mixed $condition
     * @param mixed $valuesForCondition
     * @return array<array|bool|null>
     */
    public function runQuery($action, $table, $data = [], $columns = '*', $condition = '', $valuesForCondition = []){
        $queryData = $this->buildQuery($action, $table, $data, $columns, $condition, $valuesForCondition);
        return $this->executeQuery($queryData['query'], $queryData['returningValuesForCondition']);
    }
}
?>