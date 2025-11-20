<?php
require_once __DIR__ . '/../config/database.php';

class Product
{
    private $conn;
    private $table = "products";

    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->connect();
    }

    public function all($limit = 100, $offset = 0)
    {
        $stmt = $this->conn->prepare("SELECT p.*, c.name as category_name FROM products p LEFT JOIN categories c ON c.id=p.category_id ORDER BY p.name LIMIT :lim OFFSET :off");
        $stmt->bindValue(':lim', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':off', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM products WHERE id=:id LIMIT 1");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    

    public function update($id, $data)
    {
        $sql = "UPDATE products SET sku=:sku, name=:name, description=:desc, category_id=:cat, brand=:brand, cost=:cost, price=:price, min_stock=:min, is_active=:active, visible_public=:visible WHERE id=:id";
        $data['id'] = $id;
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($data);
    }

    public function delete($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM products WHERE id=:id");
        return $stmt->execute(['id' => $id]);
    }

    
    public function search($term)
    {
        
        $term = "%$term%";

        $sql = "SELECT 
                    p.id, p.sku, p.name, p.brand, p.price, p.description,
                    b.id as branch_id, 
                    COALESCE(b.name, 'Sin Asignar') as branch_name,
                    COALESCE(i.quantity, 0) as quantity, 
                    COALESCE(i.location, '') as location
                FROM products p
                LEFT JOIN inventories i ON p.id = i.product_id
                LEFT JOIN branches b ON i.branch_id = b.id
                WHERE p.name LIKE :term 
                   OR p.sku LIKE :term 
                   OR p.description LIKE :term
                   OR p.brand LIKE :term
                ORDER BY p.name ASC, i.quantity DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':term', $term);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    
    public function getBranches()
    {
        $sql = "SELECT id, name FROM branches WHERE is_active = 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

     
    public function create($data, $initialStock = null)
    {
        try {
            
            $this->conn->beginTransaction();

            
            $sql = "INSERT INTO products (sku, name, description, category_id, brand, cost, price, min_stock, is_active, visible_public) 
                    VALUES (:sku, :name, :desc, :cat, :brand, :cost, :price, :min, :active, :visible)";

            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':sku' => $data['sku'],
                ':name' => $data['name'],
                ':desc' => $data['desc'],
                ':cat' => $data['cat'],
                ':brand' => $data['brand'],
                ':cost' => $data['cost'],
                ':price' => $data['price'],
                ':min' => $data['min'],
                ':active' => $data['active'],
                ':visible' => $data['visible']
            ]);

            $productId = $this->conn->lastInsertId();

            
            if ($initialStock && !empty($initialStock['branch_id']) && $initialStock['quantity'] > 0) {
                $sqlInv = "INSERT INTO inventories (product_id, branch_id, quantity, location) 
                           VALUES (:pid, :bid, :qty, :loc)";
                $stmtInv = $this->conn->prepare($sqlInv);
                $stmtInv->execute([
                    ':pid' => $productId,
                    ':bid' => $initialStock['branch_id'],
                    ':qty' => $initialStock['quantity'],
                    ':loc' => $initialStock['location']
                ]);
            }

            $this->conn->commit();
            return true;
            
        } catch (Exception $e) {
            $this->conn->rollBack(); 
            return false;
        }
    }

    public function getCategories()
    {
        $sql = "SELECT id, name FROM categories ORDER BY name ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 3. Stock Crítico 
    public function getCriticalStock() {
        
        $sql = "SELECT p.name, p.sku, b.name as branch, i.quantity, p.min_stock
                FROM inventories i
                JOIN products p ON i.product_id = p.id
                JOIN branches b ON i.branch_id = b.id
                WHERE i.quantity <= p.min_stock
                ORDER BY i.quantity ASC
                LIMIT 10";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}