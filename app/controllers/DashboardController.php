<?php
require_once __DIR__ . '/../models/Sale.php';
require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../helpers/auth.php';

class DashboardController {

    public function index() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        requireLogin('/dashboard');

        // 1. Instanciar Modelos
        $saleModel = new Sale();
        $productModel = new Product();

        // 2. Obtener KPIs (Indicadores Clave)
        $dailyTotal = $saleModel->getDailyTotal();
        $ranking = $saleModel->getEmployeeRanking();
        $criticalStock = $productModel->getCriticalStock();
        
        // Contar alertas
        $alertsCount = count($criticalStock);

        // 3. Cargar Vista
        $title = "Panel de Control - El Ojo del Dueño";
        ob_start();
        include __DIR__ . '/../../views/dashboard/index.php';
        $content = ob_get_clean();
        include __DIR__ . '/../../views/layouts/app.php';
    }
}