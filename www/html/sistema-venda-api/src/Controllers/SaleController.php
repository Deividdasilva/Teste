<?php

namespace App\Controllers;

use App\Transport\Outbound\Response;
use App\Services\Product\ProductService;
use App\Services\Sale\SaleService;
use App\Transport\Utils\HttpStatus;
use Exception;
use Faker\Provider\bn_BD\Utils;
use OpenApi\Annotations as OA;


/**
 * Controller sale.
 */
class SaleController
{
    /**
     * @var SaleService
     */
    private $saleService;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->saleService = new SaleService();
    }

    /**
     * @OA\Post(
     *     path="/sales",
     *     tags={"Sales"},
     *     summary="Create a new sale",
     *     description="Creates a new sale with multiple products.",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Payload for creating a new sale",
     *         @OA\JsonContent(
     *             type="object",
     *             required={"products"},
     *             @OA\Property(
     *                 property="products",
     *                 type="array",
     *                 description="List of products and their quantities",
     *                 @OA\Items(
     *                     type="object",
     *                     required={"quantity", "product_id"},
     *                     @OA\Property(property="quantity", type="integer", format="int32", description="The quantity of the product", example=2),
     *                     @OA\Property(property="product_id", type="integer", format="int64", description="The unique identifier of the product", example=4)
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Sale created successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Sale created successfully"),
     *             @OA\Property(
     *                 property="sale",
     *                 description="Details of the newly created sale",
     *                 ref="#/components/schemas/Sale"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input, object invalid"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error"
     *     )
     * )
     */
    public function createSale()
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
            $product = $this->saleService->create($data);

            if ($product) {
                Response::json(
                    HttpStatus::CREATED,
                    [
                        'message' => 'Sale created successfully'
                    ]
                );
            } else {
                Response::json(
                    HttpStatus::INTERNAL_SERVER_ERROR,
                    ['message' => 'Failed to create sale']
                );
            }
        } catch (Exception $e) {
            Response::json(
                HttpStatus::INTERNAL_SERVER_ERROR,
                ['message' => 'Error creating sale']
            );
        }
    }

     /**
     * @OA\Delete(
     *     path="/sales/{id}",
     *     tags={"Sales"},
     *     summary="Delete a sale by its ID",
     *     description="Deletes a sale from the database using its unique ID.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="The unique identifier of the sale to be deleted.",
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Sale deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Sale not found"
     *     )
     * )
     */
    public function deleteSale(int $id)
    {
        try {
            $sale = $this->saleService->delete($id);


            if ($sale) {
                Response::json(
                    HttpStatus::OK,
                    [
                        'message' => 'Sale deleted successfully',
                    ]
                );
            } else {
                Response::json(
                    HttpStatus::INTERNAL_SERVER_ERROR,
                    ['message' => 'Failed to delete sale']
                );
            }
        } catch (Exception $e) {
            Response::json(
                HttpStatus::INTERNAL_SERVER_ERROR,
                ['message' => 'Error delete sale']
            );
        }
    }

    /**
     * @OA\Get(
     *     path="/sales",
     *     tags={"Sales"},
     *     summary="Retrieve all sales",
     *     description="Returns a list of all sales, including detailed item and product information.",
     *     @OA\Response(
     *         response=200,
     *         description="List of sales",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Sale")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request"
     *     )
     * )
     * @OA\Schema(
     *     schema="Sale",
     *     type="object",
     *     properties={
     *         @OA\Property(property="id", type="integer", format="int64", example=18),
     *         @OA\Property(property="items", type="array", @OA\Items(ref="#/components/schemas/SaleItem")),
     *         @OA\Property(property="total_amount", type="number", format="float", example=750),
     *         @OA\Property(property="total_tax", type="number", format="float", example=75),
     *         @OA\Property(property="created_at", type="string", format="date-time", example="2024-04-12T16:53:34Z"),
     *         @OA\Property(property="updated_at", type="string", format="date-time"),
     *         @OA\Property(property="deleted_at", type="string", format="date-time")
     *     }
     * )
     * @OA\Schema(
     *     schema="SaleItem",
     *     type="object",
     *     properties={
     *         @OA\Property(property="id", type="integer", format="int64", example=5),
     *         @OA\Property(property="product", ref="#/components/schemas/Product"),
     *         @OA\Property(property="quantity", type="integer", example=2),
     *         @OA\Property(property="price_per_unit", type="number", format="float", example=150),
     *         @OA\Property(property="tax_per_unit", type="number", format="float", example=15),
     *         @OA\Property(property="total_price", type="number", format="float", example=300),
     *         @OA\Property(property="total_tax", type="number", format="float", example=30),
     *         @OA\Property(property="created_at", type="string", format="date-time", example="2024-04-12T16:53:34Z"),
     *         @OA\Property(property="updated_at", type="string", format="date-time"),
     *         @OA\Property(property="deleted_at", type="string", format="date-time")
     *     }
     * )
     */
    public function getAllSales()
    {
        try {
            $data = $this->saleService->getAll();

            Response::json(HttpStatus::OK, ['data' => $data]);
        } catch (Exception $e) {
            Response::json(
                HttpStatus::BAD_REQUEST,
                ['data' => 'Error']
            );
        }
    }
   
    /**
     * @OA\Get(
     *     path="/sales/{id}",
     *     tags={"Sales"},
     *     summary="Get a sale by ID",
     *     description="Retrieves detailed information about a specific sale by sale ID.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="The unique identifier of the sale",
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Detailed information about the sale",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", format="int64", description="The unique identifier for the sale", example=18),
     *             @OA\Property(
     *                 property="items",
     *                 type="array",
     *                 description="List of items in the sale",
     *                 @OA\Items(ref="#/components/schemas/SaleItem")
     *             ),
     *             @OA\Property(property="total_amount", type="number", format="float", description="Total amount of the sale", example=750),
     *             @OA\Property(property="total_tax", type="number", format="float", description="Total tax collected for the sale", example=75),
     *             @OA\Property(property="created_at", type="string", format="date-time", description="Creation date of the sale", example="2024-04-12T16:53:34Z"),
     *             @OA\Property(property="updated_at", type="string", format="date-time", description="Last update date of the sale"),
     *             @OA\Property(property="deleted_at", type="string", format="date-time", description="Deletion date of the sale, if applicable")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Sale not found"
     *     )
     * )
     */
    public function getByIdSale(int $id)
    {
        try {
            $data = $this->saleService->getById($id);
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
