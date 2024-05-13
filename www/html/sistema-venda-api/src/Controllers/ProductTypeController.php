<?php

namespace App\Controllers;

use App\Transport\Outbound\Response;
use App\Services\ProductType\ProductTypeService;
use App\Transport\Utils\HttpStatus;
use Exception;
use OpenApi\Annotations as OA;


/**
 * Controller responsible for creating a new productType.
 */
class ProductTypeController
{
    /**
     * @var ProductTypeService
     */
    private $productTypeService;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->productTypeService = new ProductTypeService();
    }

    /**
     * @OA\Post(
     *     path="/product-types",
     *     tags={"Product Types"},
     *     summary="Create a new product type",
     *     description="Adds a new product type to the database with the provided data.",
     *     @OA\RequestBody(
     *         required=true,
     *         description="JSON payload for creating a new product type",
     *         @OA\JsonContent(
     *             type="object",
     *             required={"description", "tax"},
     *             @OA\Property(property="description", type="string", example="Electronics"),
     *             @OA\Property(property="tax", type="number", format="float", example=15)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Product type created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/ProductType")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input"
     *     )
     * )
     */
    public function createProductType()
    {
        $data = json_decode(file_get_contents('php://input'), true);

        if (!$data) {
            Response::json(
                HttpStatus::BAD_REQUEST, 
                [
                    'message' => 'Invalid data provided'
                ]);

            return;
        }

        try {
            $productType = $this->productTypeService->create($data);

            if ($productType) {
                Response::json(
                    HttpStatus::CREATED,
                    [
                        'message' => 'Product Type created successfully',
                        'data' => $productType->toArray()
                    ]
                );
            } else {
                Response::json(
                    HttpStatus::INTERNAL_SERVER_ERROR,
                    ['message' => 'Failed to create product type']
                );
            }
        } catch (Exception $e) {
            Response::json(
                HttpStatus::INTERNAL_SERVER_ERROR,
                ['message' => 'Error creating product type']
            );
        }
    }

    /**
     * @OA\Delete(
     *     path="/product-types/{id}",
     *     tags={"Product Types"},
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
     *         description="Product Type deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Product Type not found"
     *     )
     * )
     */
    public function deleteProductType(int $id)
    {
        try {
            $productType = $this->productTypeService->delete($id);

            if ($productType) {
                Response::json(
                    HttpStatus::OK,
                    [
                        'message' => 'Product Type deleted successfully',
                    ]
                );
            } else {
                Response::json(
                    HttpStatus::INTERNAL_SERVER_ERROR,
                    ['message' => 'Failed to delete product type']
                );
            }
        } catch (Exception $e) {
            error_log("Error delete product: " . $e->getMessage());
            Response::json(
                HttpStatus::INTERNAL_SERVER_ERROR,
                ['message' => 'Error delete product type']
            );
        }
    }

     /**
     * @OA\Get(
     *     path="/product-types",
     *     tags={"Product Types"},
     *     summary="Get all product types",
     *     description="Retrieves a list of all product types available in the database.",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/ProductType")
     *         )
     *     )
     * )
     */
    public function getAllProductTypes()
    {
        try {
            $data = $this->productTypeService->getAll();

            Response::json(
                HttpStatus::OK,
                ['data' => $data]
            );
        } catch (Exception $e) {
            Response::json(
                HttpStatus::INTERNAL_SERVER_ERROR,
                ['data' => 'Error']
            );
        }
    }

    /**
     * @OA\Get(
     *     path="/product-types/{id}",
     *     tags={"Product Types"},
     *     summary="Get a product type by ID",
     *     description="Retrieves a specific product type by its unique ID.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="The unique identifier of the product type to be retrieved.",
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/ProductType")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Product type not found"
     *     )
     * )
     */
    public function getByIdProductType(int $id)
    {
        try {
            $data = $this->productTypeService->getById($id);
            if ($data) {
                $data = $data->toArray();
            }

            Response::json(
                HttpStatus::OK,
                ['data' => $data]
            );
        } catch (Exception $e) {
            Response::json(
                HttpStatus::BAD_REQUEST,
                ['data' => 'error']
            );
        }
    }

    /**
     * @OA\Put(
     *     path="/product-types/{id}",
     *     tags={"Product Types"},
     *     summary="Update an existing product type",
     *     description="Updates an existing product type with the provided ID, using the provided data.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="The unique identifier of the product type to be updated.",
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="JSON payload for updating the product type",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="description", type="string", example="Electronics"),
     *             @OA\Property(property="tax", type="number", format="float", example=20)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Product type updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/ProductType")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Product type not found"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input"
     *     )
     * )
     */
    public function updateProductType(int $id)
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
            $productType = $this->productTypeService->update($id, $data);

            if ($productType) {
                Response::json(
                    HttpStatus::CREATED,
                    [
                        'message' => 'Product type updated successfully',
                        'data' => $productType->toArray()
                    ]
                );
            } else {
                Response::json(
                    HttpStatus::INTERNAL_SERVER_ERROR,
                    ['message' => 'Failed to updated product type']
                );
            }
        } catch (Exception $e) {
            Response::json(
                HttpStatus::INTERNAL_SERVER_ERROR,
                ['message' => 'Error updating product type']
            );
        }
    }
}
