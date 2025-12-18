<?php
/**
 * Class Database - Hybrid Connection (MySQLi & PDO)
 * Dioptimalkan untuk keamanan dan kemudahan debugging.
 */
class Database 
{ 
    protected $host; 
    protected $user; 
    protected $password; 
    protected $db_name; 
    public $conn; // Resource untuk MySQLi (Legacy)
    public $pdo;  // Resource untuk PDO (Modern)

    public function __construct() 
    { 
        $this->getConfig(); 

        /**
         * 1. KONEKSI MySQLi
         * Digunakan untuk mendukung modul lama agar tidak blank.
         */
        $this->conn = new mysqli($this->host, $this->user, $this->password, $this->db_name); 
        if ($this->conn->connect_error) { 
            die("Koneksi MySQLi Gagal: " . $this->conn->connect_error); 
        }

        /**
         * 2. KONEKSI PDO
         * Digunakan untuk fitur baru dengan Prepared Statements agar aman dari SQL Injection.
         */
        try {
            $dsn = "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8mb4";
            $this->pdo = new PDO($dsn, $this->user, $this->password);
            
            // Set agar PDO melemparkan Exception jika ada error.
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Set agar hasil fetch otomatis menjadi array asosiatif.
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            die("Koneksi PDO Gagal: " . $e->getMessage());
        }
    } 

    /**
     * Mengambil konfigurasi database dari file config.php.
     */
    private function getConfig() 
    { 
        if (file_exists(__DIR__ . "/../config.php")) {
            include __DIR__ . "/../config.php";
            $this->host     = $config['host']; 
            $this->user     = $config['username']; 
            $this->password = $config['password']; 
            $this->db_name  = $config['db_name']; 
        } else {
            // Pengaturan fallback
            $this->host = "localhost";
            $this->user = "root";
            $this->password = "";
            $this->db_name = "lab11_php_oop";
        }
    } 

    /**
     * --- [FITUR PDO: runQuery] ---
     * Fungsi utama untuk eksekusi query yang aman.
     */
    public function runQuery($sql, $params = []) 
    {
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            /**
             * PERBAIKAN: Tampilkan error asli agar Anda tahu masalahnya 
             * (Misal: kolom 'gambar' tidak ditemukan).
             */
            die("<strong>Query Error:</strong> " . $e->getMessage() . "<br><strong>SQL:</strong> " . $sql);
        }
    }

    /**
     * --- [FITUR MySQLi] ---
     * Tetap dipertahankan agar dashboard lama tetap berjalan normal.
     */
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
        return $result ? $result->fetch_assoc() : null; 
    } 

    public function insert($table, $data) 
    { 
        $column = [];
        $value = [];
        if (is_array($data)) { 
            foreach ($data as $key => $val) { 
                $column[] = $key; 
                $value[] = "'" . $this->conn->real_escape_string($val) . "'"; 
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
                $val_safe = $this->conn->real_escape_string($val);
                $update_value[] = "$key='$val_safe'"; 
            } 
        } 
        $update_value = implode(",", $update_value); 
        $sql = "UPDATE " . $table . " SET " . $update_value . " WHERE " . $where; 
        return $this->conn->query($sql); 
    } 
} 
?>