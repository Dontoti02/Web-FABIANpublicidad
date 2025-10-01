<?php
class ExcelHandler {
    
    /**
     * Generate Excel template for products
     */
    public static function generateProductTemplate() {
        $headers = [
            'nombre' => 'Nombre del Producto',
            'descripcion' => 'Descripción',
            'precio' => 'Precio',
            'stock' => 'Stock',
            'categoria' => 'Categoría',
            'imagen' => 'Imagen (nombre del archivo)'
        ];
        
        // Sample data
        $sampleData = [
            [
                'nombre' => 'Ejemplo: Leche Entera 1L',
                'descripcion' => 'Leche fresca entera de 1 litro',
                'precio' => '4.50',
                'stock' => '100',
                'categoria' => 'Lácteos',
                'imagen' => 'leche-entera.jpg'
            ],
            [
                'nombre' => 'Ejemplo: Pan Integral',
                'descripcion' => 'Pan integral artesanal',
                'precio' => '2.80',
                'stock' => '50',
                'categoria' => 'Panadería',
                'imagen' => 'pan-integral.jpg'
            ]
        ];
        
        return self::generateCSV($headers, $sampleData, 'plantilla_productos.csv');
    }
    
    /**
     * Export products to Excel/CSV
     */
    public static function exportProducts($products) {
        $headers = [
            'id_producto' => 'ID',
            'nombre' => 'Nombre',
            'descripcion' => 'Descripción',
            'precio' => 'Precio',
            'stock' => 'Stock',
            'categoria_nombre' => 'Categoría',
            'imagen' => 'Imagen',
            'estado' => 'Estado'
        ];
        
        return self::generateCSV($headers, $products, 'productos_export.csv');
    }
    
    /**
     * Parse uploaded Excel/CSV file
     */
    public static function parseProductFile($filePath) {
        $products = [];
        $errors = [];
        
        if (!file_exists($filePath)) {
            return ['products' => [], 'errors' => ['Archivo no encontrado']];
        }
        
        $handle = fopen($filePath, 'r');
        if (!$handle) {
            return ['products' => [], 'errors' => ['No se pudo abrir el archivo']];
        }
        
        $headers = fgetcsv($handle, 1000, ',');
        if (!$headers) {
            fclose($handle);
            return ['products' => [], 'errors' => ['Archivo vacío o formato incorrecto']];
        }
        
        // Map headers to expected fields
        $headerMap = self::mapHeaders($headers);
        
        $row = 1;
        while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
            $row++;
            
            if (count($data) < count($headers)) {
                $errors[] = "Fila $row: Datos incompletos";
                continue;
            }
            
            $product = [];
            foreach ($headerMap as $index => $field) {
                if ($field && isset($data[$index])) {
                    $product[$field] = trim($data[$index]);
                }
            }
            
            // Validate required fields
            $validation = self::validateProduct($product, $row);
            if (!empty($validation['errors'])) {
                $errors = array_merge($errors, $validation['errors']);
                continue;
            }
            
            $products[] = $validation['product'];
        }
        
        fclose($handle);
        
        return ['products' => $products, 'errors' => $errors];
    }
    
    /**
     * Generate CSV file
     */
    private static function generateCSV($headers, $data, $filename) {
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        
        $output = fopen('php://output', 'w');
        
        // Add BOM for UTF-8
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        
        // Write headers
        fputcsv($output, array_values($headers));
        
        // Write data
        foreach ($data as $row) {
            $csvRow = [];
            foreach (array_keys($headers) as $key) {
                $csvRow[] = $row[$key] ?? '';
            }
            fputcsv($output, $csvRow);
        }
        
        fclose($output);
        exit();
    }
    
    /**
     * Map CSV headers to database fields
     */
    private static function mapHeaders($headers) {
        $map = [];
        $fieldMap = [
            'nombre' => ['nombre', 'name', 'producto', 'product'],
            'descripcion' => ['descripcion', 'description', 'desc'],
            'precio' => ['precio', 'price', 'cost', 'costo'],
            'stock' => ['stock', 'cantidad', 'quantity', 'qty'],
            'categoria' => ['categoria', 'category', 'cat'],
            'imagen' => ['imagen', 'image', 'img', 'foto', 'photo']
        ];
        
        foreach ($headers as $index => $header) {
            $header = strtolower(trim($header));
            $mapped = null;
            
            foreach ($fieldMap as $field => $variations) {
                if (in_array($header, $variations)) {
                    $mapped = $field;
                    break;
                }
            }
            
            $map[$index] = $mapped;
        }
        
        return $map;
    }
    
    /**
     * Validate product data
     */
    private static function validateProduct($product, $row) {
        $errors = [];
        $cleanProduct = [];
        
        // Required fields
        if (empty($product['nombre'])) {
            $errors[] = "Fila $row: Nombre es requerido";
        } else {
            $cleanProduct['nombre'] = $product['nombre'];
        }
        
        // Price validation
        if (empty($product['precio']) || !is_numeric($product['precio']) || $product['precio'] < 0) {
            $errors[] = "Fila $row: Precio debe ser un número válido mayor o igual a 0";
        } else {
            $cleanProduct['precio'] = floatval($product['precio']);
        }
        
        // Stock validation
        if (empty($product['stock']) || !is_numeric($product['stock']) || $product['stock'] < 0) {
            $errors[] = "Fila $row: Stock debe ser un número entero mayor o igual a 0";
        } else {
            $cleanProduct['stock'] = intval($product['stock']);
        }
        
        // Optional fields
        $cleanProduct['descripcion'] = $product['descripcion'] ?? '';
        $cleanProduct['categoria'] = $product['categoria'] ?? '';
        $cleanProduct['imagen'] = $product['imagen'] ?? 'default.jpg';
        
        return ['product' => $cleanProduct, 'errors' => $errors];
    }
    
    /**
     * Get category ID by name
     */
    public static function getCategoryIdByName($categoryName, $categories) {
        foreach ($categories as $category) {
            if (strcasecmp($category['nombre'], $categoryName) === 0) {
                return $category['id_categoria'];
            }
        }
        return 1; // Default category
    }
}
?>