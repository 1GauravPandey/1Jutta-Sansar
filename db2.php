<?php
/**
 * Database Connection Class for Jutta Sansaar Ecommerce
 * Handles MySQL database connections using PDO
 */

class Database {
    private static $instance = null;
    private $connection;
    
    // Database configuration
    private $host = 'localhost';
    private $dbname = 'jutta_sansaar';
    private $username = 'root';
    private $password = '';
    private $charset = 'utf8mb4';
    
    /**
     * Private constructor to prevent direct instantiation
     */
    private function __construct() {
        $this->connect();
    }
    
    /**
     * Get singleton instance of Database class
     * @return Database
     */
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Establish database connection using PDO
     */
    private function connect() {
        try {
            $dsn = "mysql:host={$this->host};dbname={$this->dbname};charset={$this->charset}";
            
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES {$this->charset} COLLATE utf8mb4_unicode_ci"
            ];
            
            $this->connection = new PDO($dsn, $this->username, $this->password, $options);
            
        } catch (PDOException $e) {
            $this->handleConnectionError($e);
        }
    }
    
    /**
     * Get PDO connection instance
     * @return PDO
     */
    public function getConnection() {
        return $this->connection;
    }
    
    /**
     * Handle database connection errors
     * @param PDOException $e
     */
    private function handleConnectionError(PDOException $e) {
        error_log("Database Connection Error: " . $e->getMessage());
        
        // In production, show generic error message
        if (defined('ENVIRONMENT') && ENVIRONMENT === 'production') {
            die("Database connection failed. Please try again later.");
        } else {
            // In development, show detailed error
            die("Database Connection Error: " . $e->getMessage());
        }
    }
    
    /**
     * Prepare and execute a query
     * @param string $sql
     * @param array $params
     * @return PDOStatement
     */
    public function query($sql, $params = []) {
        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            error_log("Query Error: " . $e->getMessage() . " SQL: " . $sql);
            throw new Exception("Database query failed: " . $e->getMessage());
        }
    }
    
    /**
     * Fetch a single row
     * @param string $sql
     * @param array $params
     * @return array|false
     */
    public function fetchRow($sql, $params = []) {
        $stmt = $this->query($sql, $params);
        return $stmt->fetch();
    }
    
    /**
     * Fetch all rows
     * @param string $sql
     * @param array $params
     * @return array
     */
    public function fetchAll($sql, $params = []) {
        $stmt = $this->query($sql, $params);
        return $stmt->fetchAll();
    }
    
    /**
     * Insert data and return last insert ID
     * @param string $sql
     * @param array $params
     * @return string
     */
    public function insert($sql, $params = []) {
        $this->query($sql, $params);
        return $this->connection->lastInsertId();
    }
    
    /**
     * Update data and return affected rows
     * @param string $sql
     * @param array $params
     * @return int
     */
    public function update($sql, $params = []) {
        $stmt = $this->query($sql, $params);
        return $stmt->rowCount();
    }
    
    /**
     * Delete data and return affected rows
     * @param string $sql
     * @param array $params
     * @return int
     */
    public function delete($sql, $params = []) {
        $stmt = $this->query($sql, $params);
        return $stmt->rowCount();
    }
    
    /**
     * Begin transaction
     */
    public function beginTransaction() {
        $this->connection->beginTransaction();
    }
    
    /**
     * Commit transaction
     */
    public function commit() {
        $this->connection->commit();
    }
    
    /**
     * Rollback transaction
     */
    public function rollback() {
        $this->connection->rollBack();
    }
    
    /**
     * Check if currently in a transaction
     * @return bool
     */
    public function inTransaction() {
        return $this->connection->inTransaction();
    }
    
    /**
     * Get database configuration (without sensitive data)
     * @return array
     */
    public function getConfig() {
        return [
            'host' => $this->host,
            'dbname' => $this->dbname,
            'charset' => $this->charset,
            'username' => $this->username
            // Password intentionally excluded for security
        ];
    }
    
    /**
     * Test database connection
     * @return bool
     */
    public function testConnection() {
        try {
            $this->connection->query('SELECT 1');
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }
    
    /**
     * Get database version
     * @return string
     */
    public function getVersion() {
        try {
            $stmt = $this->connection->query('SELECT VERSION() as version');
            $result = $stmt->fetch();
            return $result['version'];
        } catch (PDOException $e) {
            return 'Unknown';
        }
    }
    
    /**
     * Prevent cloning of the instance
     */
    private function __clone() {}
    
    /**
     * Prevent unserialization of the instance
     */
    public function __wakeup() {
        throw new Exception("Cannot unserialize singleton");
    }
}

/**
 * Helper class for common database operations
 */
class DatabaseHelper {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    /**
     * Get products with pagination
     * @param int $page
     * @param int $limit
     * @param array $filters
     * @return array
     */
    public function getProducts($page = 1, $limit = 24, $filters = []) {
        $offset = ($page - 1) * $limit;
        
        $sql = "SELECT p.*, b.name as brand_name, c.name as category_name 
                FROM products p 
                LEFT JOIN brands b ON p.brand_id = b.brand_id 
                LEFT JOIN categories c ON p.category_id = c.category_id 
                WHERE p.is_active = 1";
        
        $params = [];
        
        // Apply filters
        if (!empty($filters['category_id'])) {
            $sql .= " AND p.category_id = :category_id";
            $params['category_id'] = $filters['category_id'];
        }
        
        if (!empty($filters['brand_id'])) {
            $sql .= " AND p.brand_id = :brand_id";
            $params['brand_id'] = $filters['brand_id'];
        }
        
        if (!empty($filters['gender'])) {
            $sql .= " AND p.gender = :gender";
            $params['gender'] = $filters['gender'];
        }
        
        if (!empty($filters['min_price'])) {
            $sql .= " AND p.base_price >= :min_price";
            $params['min_price'] = $filters['min_price'];
        }
        
        if (!empty($filters['max_price'])) {
            $sql .= " AND p.base_price <= :max_price";
            $params['max_price'] = $filters['max_price'];
        }
        
        if (!empty($filters['search'])) {
            $sql .= " AND (p.name LIKE :search OR p.description LIKE :search)";
            $params['search'] = '%' . $filters['search'] . '%';
        }
        
        $sql .= " ORDER BY p.created_at DESC LIMIT :limit OFFSET :offset";
        $params['limit'] = $limit;
        $params['offset'] = $offset;
        
        return $this->db->fetchAll($sql, $params);
    }
    
    /**
     * Get total product count for pagination
     * @param array $filters
     * @return int
     */
    public function getProductCount($filters = []) {
        $sql = "SELECT COUNT(*) as count FROM products p WHERE p.is_active = 1";
        $params = [];
        
        // Apply same filters as getProducts method
        if (!empty($filters['category_id'])) {
            $sql .= " AND p.category_id = :category_id";
            $params['category_id'] = $filters['category_id'];
        }
        
        if (!empty($filters['brand_id'])) {
            $sql .= " AND p.brand_id = :brand_id";
            $params['brand_id'] = $filters['brand_id'];
        }
        
        if (!empty($filters['gender'])) {
            $sql .= " AND p.gender = :gender";
            $params['gender'] = $filters['gender'];
        }
        
        if (!empty($filters['min_price'])) {
            $sql .= " AND p.base_price >= :min_price";
            $params['min_price'] = $filters['min_price'];
        }
        
        if (!empty($filters['max_price'])) {
            $sql .= " AND p.base_price <= :max_price";
            $params['max_price'] = $filters['max_price'];
        }
        
        if (!empty($filters['search'])) {
            $sql .= " AND (p.name LIKE :search OR p.description LIKE :search)";
            $params['search'] = '%' . $filters['search'] . '%';
        }
        
        $result = $this->db->fetchRow($sql, $params);
        return (int) $result['count'];
    }
    
    /**
     * Get user by email
     * @param string $email
     * @return array|false
     */
    public function getUserByEmail($email) {
        $sql = "SELECT * FROM users WHERE email = :email AND is_active = 1";
        return $this->db->fetchRow($sql, ['email' => $email]);
    }
    
    /**
     * Create new user
     * @param array $userData
     * @return string
     */
    public function createUser($userData) {
        $sql = "INSERT INTO users (first_name, last_name, email, password_hash, phone, date_of_birth, gender) 
                VALUES (:first_name, :last_name, :email, :password_hash, :phone, :date_of_birth, :gender)";
        
        return $this->db->insert($sql, [
            'first_name' => $userData['first_name'],
            'last_name' => $userData['last_name'],
            'email' => $userData['email'],
            'password_hash' => password_hash($userData['password'], PASSWORD_DEFAULT),
            'phone' => $userData['phone'] ?? null,
            'date_of_birth' => $userData['date_of_birth'] ?? null,
            'gender' => $userData['gender'] ?? null
        ]);
    }
}

// Example usage and configuration
if (basename(__FILE__) == basename($_SERVER['SCRIPT_NAME'])) {
    // This block runs only when db.php is accessed directly (for testing)
    
    try {
        // Test the database connection
        $db = Database::getInstance();
        
        if ($db->testConnection()) {
            echo "✅ Database connection successful!\n";
            echo "Database version: " . $db->getVersion() . "\n";
            
            // Test a simple query
            $result = $db->fetchRow("SELECT COUNT(*) as table_count 
                                   FROM information_schema.tables 
                                   WHERE table_schema = 'jutta_sansaar'");
            
            echo "Tables in database: " . $result['table_count'] . "\n";
            
        } else {
            echo "❌ Database connection failed!\n";
        }
        
    } catch (Exception $e) {
        echo "❌ Error: " . $e->getMessage() . "\n";
    }
}

// Configuration constants (define these in your main config file)
if (!defined('DB_HOST')) {
    define('DB_HOST', 'localhost');
    define('DB_NAME', 'jutta_sansaar');
    define('DB_USER', 'root');
    define('DB_PASS', '');
    define('DB_CHARSET', 'utf8mb4');
}

// Error reporting (set to 0 in production)
if (!defined('ENVIRONMENT')) {
    define('ENVIRONMENT', 'development');
}

?>