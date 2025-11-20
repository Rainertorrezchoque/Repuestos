<?php
require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../models/Sale.php'; // Si quieres reportes de ventas
require_once __DIR__ . '/../helpers/auth.php';

// Incluimos la librería FPDF
require_once __DIR__ . '/../libs/fpdf.php';

class ReportController {

    public function index() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        requireLogin('/reportes');

        $title = "Reportes y Exportación";
        ob_start();
        include __DIR__ . '/../../views/reports/index.php';
        $content = ob_get_clean();
        include __DIR__ . '/../../views/layouts/app.php';
    }

    // --- REPORTE EXCEL (CSV) ---
    public function inventoryExcel() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        requireLogin('/reportes');

        // 1. Obtener datos (Reusamos la función search vacía para traer todo con sucursales)
        $p = new Product();
        $data = $p->search(''); 

        // 2. Configurar cabeceras para forzar descarga
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=Inventario_'.date('Y-m-d').'.csv');

        // 3. Abrir salida
        $output = fopen('php://output', 'w');

        // 4. Escribir encabezados de columna (BOM para que Excel lea bien tildes)
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        fputcsv($output, ['SKU', 'Producto', 'Marca', 'Sucursal', 'Stock', 'Ubicación', 'Precio Venta']);

        // 5. Escribir filas
        foreach ($data as $row) {
            fputcsv($output, [
                $row['sku'],
                $row['name'],
                $row['brand'],
                $row['branch_name'] ?? 'Sin Asignar',
                $row['quantity'],
                $row['location'],
                $row['price']
            ]);
        }
        fclose($output);
        exit;
    }

    // --- REPORTE PDF ---
    public function inventoryPdf() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        requireLogin('/reportes');

        $p = new Product();
        $data = $p->search('');

        // Iniciamos FPDF
        $pdf = new PDF_Inventory(); // Usamos la clase personalizada de abajo
        $pdf->AddPage();
        $pdf->SetFont('Arial', '', 10);

        foreach ($data as $row) {
            // utf8_decode es necesario porque FPDF usa ISO-8859-1
            $pdf->Cell(30, 8, utf8_decode($row['sku']), 1);
            
            // Truncar nombre si es muy largo
            $name = substr($row['name'], 0, 25);
            $pdf->Cell(60, 8, utf8_decode($name), 1);
            
            $pdf->Cell(40, 8, utf8_decode($row['branch_name'] ?? '-'), 1);
            
            // Color rojo si no hay stock
            if($row['quantity'] <= 0) $pdf->SetTextColor(200,0,0);
            $pdf->Cell(20, 8, $row['quantity'], 1, 0, 'C');
            $pdf->SetTextColor(0); // Reset color
            
            $pdf->Cell(25, 8, utf8_decode($row['location']), 1);
            $pdf->Cell(20, 8, $row['price'], 1, 0, 'R');
            
            $pdf->Ln(); // Salto de línea
        }

        $pdf->Output('I', 'Inventario.pdf'); // 'I' para mostrar en navegador, 'D' para descargar
        exit;
    }
}

// Clase auxiliar para el diseño del PDF (Encabezado y Pie)
class PDF_Inventory extends FPDF {
    function Header() {
        $this->SetFont('Arial', 'B', 15);
        $this->Cell(0, 10, 'Reporte General de Inventario', 0, 1, 'C');
        $this->SetFont('Arial', 'I', 10);
        $this->Cell(0, 10, 'Generado el: ' . date('d/m/Y H:i'), 0, 1, 'C');
        $this->Ln(5);

        // Cabecera de Tabla
        $this->SetFillColor(200, 220, 255);
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(30, 8, 'SKU', 1, 0, 'C', true);
        $this->Cell(60, 8, 'Producto', 1, 0, 'C', true);
        $this->Cell(40, 8, 'Sucursal', 1, 0, 'C', true);
        $this->Cell(20, 8, 'Cant.', 1, 0, 'C', true);
        $this->Cell(25, 8, 'Ubic.', 1, 0, 'C', true);
        $this->Cell(20, 8, 'Precio', 1, 0, 'C', true);
        $this->Ln();
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Pagina ' . $this->PageNo(), 0, 0, 'C');
    }
}