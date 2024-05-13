<?php

namespace App\Controllers;

use App\Transport\Outbound\Response;
use App\Services\Product\ProductService;
use App\Transport\Utils\HttpStatus;
use Exception;
use OpenApi\Annotations as OA;


/**
 * Controller responsible for creating a new product.
 *
 * @OA\Info(
 *     title="Teste Desenvolvedor SoftExpert",
 *     version="1.0.0",
 *     description="Teste SoftExpert",
 *     @OA\Contact(
 *         email="deivid.silva@rede.ulbra.br"
 *     )
 * )
 */
class ProductController
{
    /**
     * @var ProductService
     */
    private $productService;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->productService = new ProductService();
    }

    /**
     * Controller responsible for create one product.
     * 
     * @OA\Post(
     *     path="/products",
     *     tags={"Products"},
     *     summary="Create a new product",
     *     description="Adds a new product to the database with the provided data.",
     *     @OA\RequestBody(
     *         required=true,
     *         description="JSON payload",
     *         @OA\JsonContent(
     *             type="object",
     *             required={"description", "price", "product_type"},
     *             @OA\Property(property="description", type="string", example="mouse"),
     *             @OA\Property(property="price", type="number", format="float", example=150),
     *             @OA\Property(property="ean", type="string", format="string", example="AB12345678910"),
     *             @OA\Property(property="purchase_price", type="number", format="float", example=75),
     *             @OA\Property(property="sales_margin", type="number", format="float", example=100),
     *             @OA\Property(property="quantity", type="integer", format="int64", example=150),
     *             @OA\Property(property="minimum_quantity", type="integer", format="int64", example=10),
     *             @OA\Property(property="product_type", type="object",
     *                 required={"id"},
     *                 @OA\Property(property="id", type="integer", format="int64", example=1)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Product created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Product")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input"
     *     )
     * )
     */
    public function createProduct()
    {
        $data = json_decode(file_get_contents('php://input'), true);

        if (!$data) {
            OutboundResponse::json(
                HttpStatus::BAD_REQUEST,
                ['message' => 'Invalid data provided']
            );

            return;
        }

        try {
            $product = $this->productService->create($data);

            if ($product) {
                Response::json(
                    HttpStatus::CREATED,
                    [
                        'message' => 'Product created successfully',
                        'data' => $product->toArray()
                    ]
                );
            } else {
                Response::json(
                    HttpStatus::INTERNAL_SERVER_ERROR,
                    ['message' => 'Failed to create product']
                );
            }
        } catch (Exception $e) {
            Response::json(
                HttpStatus::INTERNAL_SERVER_ERROR,
                ['message' => 'Error creating product']
            );
        }
    }

    /**
     * @OA\Put(
     *     path="/products/{id}",
     *     tags={"Products"},
     *     summary="Update an existing product",
     *     description="Updates an existing product with the provided ID, using the provided data.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="The unique identifier of the product to be updated.",
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="JSON payload for updating the product",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="description", type="string", example="cadeira"),
     *             @OA\Property(property="price", type="number", format="float", example=200),
     *             @OA\Property(property="product_type", type="object",
     *                 @OA\Property(property="id", type="integer", format="int64", example=2)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Product updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Product")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Product not found"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input"
     *     )
     * )
     */
    public function updateProduct(int $id)
    {
        $data = json_decode(file_get_contents('php://input'), true);

        if (!$data) {
            Response::json(
                HttpStatus::BAD_REQUEST,
                ['message' => 'Invalid data provided']
            );

            return;
        }

        try {
            $product = $this->productService->update($id, $data);

            if ($product) {
                Response::json(
                    HttpStatus::CREATED,
                    [
                        'message' => 'Product updated successfully',
                        'data' => $product->toArray()
                    ]
                );
            } else {
                Response::json(
                    HttpStatus::INTERNAL_SERVER_ERROR,
                    ['message' => 'Failed to updated product']
                );
            }
        } catch (Exception $e) {
            Response::json(
                HttpStatus::INTERNAL_SERVER_ERROR,
                ['message' => 'Error updating product']
            );
        }
    }

     /**
     * @OA\Delete(
     *     path="/products/{id}",
     *     tags={"Products"},
     *     summary="Delete a product by its ID",
     *     description="Deletes a product from the database using its unique ID.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="The unique identifier of the product to be deleted.",
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Product deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Product not found"
     *     )
     * )
     */
    public function deleteProduct(int $id)
    {
        try {
            $product = $this->productService->delete($id);

            if ($product) {
                Response::json(
                    HttpStatus::OK,
                    [
                        'message' => 'Product deleted successfully',
                    ]
                );
            } else {
                Response::json(
                    HttpStatus::INTERNAL_SERVER_ERROR,
                    ['message' => 'Failed to delete product']
                );
            }
        } catch (Exception $e) {
            Response::json(
                HttpStatus::INTERNAL_SERVER_ERROR,
                ['message' => 'Error delete product']
            );
        }
    }

    /**
     * @OA\Get(
     *     path="/products",
     *     tags={"Products"},
     *     @OA\Response(
     *         response=200,
     *         description="Returns a list of products",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Product")
     *         )
     *     )
     * )
     * 
     * @OA\Schema(
     *     schema="Product",
     *     type="object",
     *     @OA\Property(property="id", type="integer", format="int64", example=4),
     *     @OA\Property(property="name", type="string", example="mouse gammer"),
     *     @OA\Property(property="price", type="number", format="float", example=150),
     *     @OA\Property(property="ean", type="string", example="AB12345678910"),
     *     @OA\Property(property="purchase_price", type="number", format="float", example=75),
     *     @OA\Property(property="sales_margin", type="number", format="float", example=100),
     *     @OA\Property(property="quantity", type="integer", format="int64", example=150),
     *     @OA\Property(property="minimum_quantity", type="integer", format="int64", example=10),
     *     @OA\Property(property="product_type", ref="#/components/schemas/ProductType"),
     *     @OA\Property(property="created_at", type="string", format="date-time", example="2024-04-12T00:02:25Z"),
     *     @OA\Property(property="updated_at", type="string", format="date-time"),
     *     @OA\Property(property="deleted_at", type="string", format="date-time")
     * )
     * @OA\Schema(
     *     schema="ProductType",
     *     type="object",
     *     @OA\Property(property="id", type="integer", format="int64", example=1),
     *     @OA\Property(property="description", type="string", example="informatica"),
     *     @OA\Property(property="tax", type="number", format="float", example=10),
     *     @OA\Property(property="created_at", type="string", format="date-time", example="2024-04-11T22:30:11Z"),
     *     @OA\Property(property="updated_at", type="string", format="date-time"),
     *     @OA\Property(property="deleted_at", type="string", format="date-time")
     * )
     */
    public function getAllProduct()
    {
        try {
            $data = $this->productService->getAll();

            Response::json(HttpStatus::OK, ['data' => $data]);
        } catch (Exception $e) {
            error_log("Error: " . $e->getMessage());
            Response::json(
                HttpStatus::BAD_REQUEST,
                ['data' => 'Error']
            );
        }
    }

    /**
     * @OA\Get(
     *     path="/products/{id}",
     *     tags={"Products"},
     *     summary="Get a product by its ID",
     *     description="Retrieves a product using its unique ID.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="The unique identifier of the product.",
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/Product")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Product not found"
     *     )
     * )
     */
    public function getByIdProduct(int $id)
    {
        try {
            $data = $this->productService->getById($id);
            if ($data) {
                $data = $data->toArray();
            }

            Response::json(HttpStatus::OK, ['data' => $data]);
        } catch (Exception $e) {
            error_log("Error: " . $e->getMessage());
            Response::json(
                HttpStatus::BAD_REQUEST,
                ['data' => 'Error']
            );
        }
    }
}
