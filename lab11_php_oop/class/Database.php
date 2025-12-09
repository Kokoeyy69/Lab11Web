<?php 
class Database 
{ 
    protected $host; 
    protected $user; 
    protected $password; 
    protected $db_name; 
    protected $conn; 

    public function __construct() 
    { 
        $this->getConfig(); 
        $this->conn = new mysqli($this->host, $this->user, $this->password, $this->db_name); 
        
        if ($this->conn->connect_error) { 
            die("Connection failed: " . $this->conn->connect_error); 
        } 
    } 

    private function getConfig() 
    { 
        include("config.php"); 
        $this->host     = $config['host']; 
        $this->user     = $config['username']; 
        $this->password = $config['password']; 
        $this->db_name  = $config['db_name']; 
    } 

    public function query($sql) 
    { 
        return $this->conn->query($sql); 
    } 

    public function get($table, $where = null) 
    { 
        if ($where) { 
            $where = " WHERE " . $where; 
        } 

        $sql = "SELECT * FROM " . $table . $where; 
        $result = $this->conn->query($sql); 
        return $result->fetch_assoc(); 
    } 

    public function insert($table, $data) 
    { 
        if (is_array($data)) { 
            foreach ($data as $key => $val) { 
                $column[] = $key; 
                $value[] = "'{$val}'"; 
            } 
        } 

        $columns = implode(",", $column); 
        $values  = implode(",", $value); 
        
        $sql = "INSERT INTO " . $table . " (" . $columns . ") VALUES (" . $values . ")"; 
        return $this->conn->query($sql); 
    } 

    public function update($table, $data, $where) 
    { 
        $update_value = []; 
        
        if (is_array($data)) { 
            foreach ($data as $key => $val) { 
                $update_value[] = "$key='{$val}'"; 
            } 
        } 

        $update_value = implode(",", $update_value); 

        $sql = "UPDATE " . $table . " SET " . $update_value . " WHERE " . $where; 
        return $this->conn->query($sql); 
    } 
} 
?>
