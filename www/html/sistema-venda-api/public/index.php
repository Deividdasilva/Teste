<?php

use App\Controllers\ProductController;
use App\Controllers\ProductTypeController;
use App\Controllers\SaleController;
use App\Transport\Outbound\Response;
use App\Transport\Utils\HttpStatus;

require_once __DIR__ . '/../vendor/autoload.php';

$uri = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Access-Control-Max-Age: 3600");
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

$productController = new ProductController();
$productTypeController = new ProductTypeController();
$saleController = new SaleController();

switch ($uri) {
    case '/products':
        if ($method === 'GET') {
            $productController->getAllProduct();
        } elseif ($method === 'POST') {
            $productController->createProduct();
        } else {
            Response::json(
                HttpStatus::BAD_REQUEST,
                ["data" => "Page not found"]
            );
            break;
        }
        break;

    case (preg_match('/\/products\/(\d+)/', $uri, $matches) ? true : false):
        $id = $matches[1];
        switch ($method) {
            case 'GET':
                $productController->getByIdProduct($id);
                break;
            case 'PUT':
                $productController->updateProduct($id);
                break;
            case 'DELETE':
                $productController->deleteProduct($id);
                break;
            default:
                Response::json(
                    HttpStatus::BAD_REQUEST,
                    ["data" => "Page not found"]
                );
                break;
        }
        break;

    case '/product-types':
        if ($method === 'GET') {
            $productTypeController->getAllProductTypes();
        } elseif ($method === 'POST') {
            $productTypeController->createProductType();
        } else {
            Response::json(
                HttpStatus::BAD_REQUEST,
                ["data" => "Page not found"]
            );
            break;
        }
        break;

    case (preg_match('/\/product-types\/(\d+)/', $uri, $matches) ? true : false):
        $productTypeId = $matches[1];
        switch ($method) {
            case 'GET':
                $productTypeController->getByIdProductType($productTypeId);
                break;
            case 'PUT':
                $productTypeController->updateProductType($productTypeId);
                break;
            case 'DELETE':
                $productTypeController->deleteProductType($productTypeId);
                break;
            default:
                Response::json(
                    HttpStatus::BAD_REQUEST,
                    ["data" => "Page not found"]
                );
                break;
        }
        break;
    case '/sales':
        if ($method === 'GET') {
            $saleController->getAllSales();
        } elseif ($method === 'POST') {
            $saleController->createSale();
        } else {
            Response::json(
                HttpStatus::BAD_REQUEST,
                ["data" => "Page not found"]
            );
            break;
        }
        break;

    case (preg_match('/\/sales\/(\d+)/', $uri, $matches) ? true : false):
        $saleId = $matches[1];
        switch ($method) {
            case 'GET':
                $saleController->getByIdSale($saleId);
                break;
            case 'DELETE':
                $saleController->deleteSale($saleId);
                break;
            default:
                Response::json(
                    HttpStatus::BAD_REQUEST,
                    ["data" => "Page not found"]
                );
                break;
        }
        break;

    default:
        Response::json(
            HttpStatus::BAD_REQUEST,
            ["data" => "Page not found"]
        );
        break;
}
