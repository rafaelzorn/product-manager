<?php

namespace App\Services\Product;

use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Services\Product\Contracts\ProductServiceInterface;
use App\Services\ProcessSpreadsheet\Contracts\ProcessSpreadsheetServiceInterface;
use App\Repositories\Product\Contracts\ProductRepositoryInterface;
use App\Http\Resources\Product\ProductResource;
use App\Jobs\Imports\Factory\ImportFactoryJob;
use App\Jobs\Imports\Product\ProductImportJob;
use App\Constants\HttpStatusConstant;

class ProductService implements ProductServiceInterface
{
    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var ProcessSpreadsheetServiceInterface
     */
    private $processSpreadsheetService;

    /**
     * @var ImportFactoryJob
     */
    private $importFactoryJob;

    /**
     * @param ProductRepositoryInterface $productRepository
     * @param ProcessSpreadsheetServiceInterface $processSpreadsheetService
     * @param ProductImportJobFactory $productImportJobFactory
     *
     * @return void
     */
    public function __construct(
        ProductRepositoryInterface $productRepository,
        ProcessSpreadsheetServiceInterface $processSpreadsheetService,
        ImportFactoryJob $importFactoryJob
    )
    {
        $this->productRepository         = $productRepository;
        $this->processSpreadsheetService = $processSpreadsheetService;
        $this->importFactoryJob          = $importFactoryJob;
    }

    /**
     * @return array
     */
    public function getAllProducts(): array
    {
        $products = $this->productRepository->get();
        $products = ProductResource::collection($products);

        return [
            'code' => HttpStatusConstant::OK,
            'data' => $products,
        ];
    }

    /**
     * @param UploadedFile $spreadsheet
     *
     * @return array
     */
    public function importProducts(UploadedFile $spreadsheet): array
    {
        try {
            $this->importFactoryJob->setClass(ProductImportJob::class);

            $processedFile = $this->processSpreadsheetService->process(
                $spreadsheet,
                $this->importFactoryJob
            );

            $endpoint = route('processed-files.show', ['id' => $processedFile->id]);

            $data = [
                'endpoint_spreadsheet_processing_status' => $endpoint,
            ];

            return [
                'code'    => HttpStatusConstant::OK,
                'data'    => $data,
                'message' => trans('messages.spreadsheet_sent_for_processing'),
            ];
        } catch (Exception $e) {
            return [
                'code'    => HttpStatusConstant::INTERNAL_SERVER_ERROR,
                'message' => trans('messages.internal_server_error'),
            ];
        }
    }

    /**
     * @param int $id
     *
     * @return array
     */
    public function getProduct(int $id): array
    {
        try {
            $product = $this->productRepository->findOrFail($id);
            $product = new ProductResource($product);

            return [
                'code' => HttpStatusConstant::OK,
                'data' => $product,
            ];
        } catch (Exception $e) {
            switch (get_class($e)) {
                case ModelNotFoundException::class:
                    return [
                        'code'    => HttpStatusConstant::NOT_FOUND,
                        'message' => trans('messages.not_found', ['attribute' => 'Product']),
                    ];
                default:
                    return [
                        'code'    => HttpStatusConstant::INTERNAL_SERVER_ERROR,
                        'message' => trans('messages.internal_server_error'),
                    ];
            }
        }
    }

    /**
     * @param int $id
     * @param array $data
     *
     * @return array
     */
    public function updateProduct(int $id, array $data): array
    {
        try {
            $product = $this->productRepository->updateProduct($id, $data);
            $product = new ProductResource($product);

            return [
                'code' => HttpStatusConstant::OK,
                'data' => $product,
            ];
        } catch (Exception $e) {
            switch (get_class($e)) {
                case ModelNotFoundException::class:
                    return [
                        'code'    => HttpStatusConstant::NOT_FOUND,
                        'message' => trans('messages.not_found', ['attribute' => 'Product']),
                    ];
                default:
                    return [
                        'code'    => HttpStatusConstant::INTERNAL_SERVER_ERROR,
                        'message' => trans('messages.internal_server_error'),
                    ];
            }
        }
    }

    /**
     * @param int $id
     *
     * @return array
     */
    public function deleteProduct(int $id): array
    {
        try {
            $this->productRepository->findOrFail($id)->delete();

            return [
                'code'    => HttpStatusConstant::OK,
                'message' => trans('messages.record_removed', ['attribute' => 'Product']),
            ];
        } catch (Exception $e) {
            switch (get_class($e)) {
                case ModelNotFoundException::class:
                    return [
                        'code'    => HttpStatusConstant::NOT_FOUND,
                        'message' => trans('messages.not_found', ['attribute' => 'Product']),
                    ];
                default:
                    return [
                        'code'    => HttpStatusConstant::INTERNAL_SERVER_ERROR,
                        'message' => trans('messages.internal_server_error'),
                    ];
            }
        }
    }
}
