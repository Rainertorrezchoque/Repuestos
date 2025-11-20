<?php

return [
    // --- Autenticación ---
    "/" => "AuthController@login",
    "/login" => "AuthController@login",
    "/logout" => "AuthController@logout",

    // --- Productos ---
    "/productos" => "ProductController@index",
    "/productos/buscar" => "ProductController@search",
    "/productos/create" => "ProductController@create",
    "/productos/store" => "ProductController@store",
    "/productos/edit" => "ProductController@edit",
    "/productos/update" => "ProductController@update",
    "/productos/delete" => "ProductController@delete",

    // --- Ventas ---
    "/ventas" => "SalesController@index",
    "/ventas/crear" => "SalesController@create",

    // --- Stock ---
    
    "/stock" => "StockController@index",
    "/stock/reserve" => "StockController@reserve", 
    "/stock/approve" => "StockController@approve", 
    "/stock/reject" => "StockController@reject",   


    // --- Dashboard ---
    "/dashboard" => "DashboardController@index",

    "/productos/buscar-json" => "ProductController@searchJson",

    // --- USUARIOS ---
    "/usuarios" => "UserController@index",
    "/usuarios/crear" => "UserController@create",
    "/usuarios/store" => "UserController@store",
    "/usuarios/editar" => "UserController@edit",
    "/usuarios/update" => "UserController@update",

    // --- REPORTES ---
    "/reportes" => "ReportController@index",
    "/reportes/inventario-excel" => "ReportController@inventoryExcel",
    "/reportes/inventario-pdf" => "ReportController@inventoryPdf",
];