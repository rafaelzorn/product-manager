<?php

namespace App\Services\Product;

use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\ProcessedFile;
use App\Services\Product\Contracts\ProductServiceInterface;
use App\Repositories\Product\Contracts\ProductRepositoryInterface;
use App\Repositories\ProcessedFile\Contracts\ProcessedFileRepositoryInterface;
use App\Http\Resources\Product\ProductResource;
use App\Constants\HttpStatusConstant;
use App\Constants\StoragePathConstant;

class ProductService implements ProductServiceInterface
{
    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var ProcessedFileRepositoryInterface
     */
    private $processedFileRepository;

    /**
     * @param ProductRepositoryInterface $productRepository
     *
     * @return void
     */
    public function __construct(
        ProductRepositoryInterface $productRepository,
        ProcessedFileRepositoryInterface $processedFileRepository
    )
    {
        $this->productRepository       = $productRepository;
        $this->processedFileRepository = $processedFileRepository;
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
            $originalFileName = $spreadsheet->getClientOriginalName();
            $storedFileName   = $spreadsheet->store(StoragePathConstant::IMPORTED_SPREADSHEETS);

            $processedFile = $this->processedFileRepository->create([
                'original_filename' => $originalFileName,
                'stored_filename'   => $storedFileName
            ]);

            $this->dispatchProcessing($processedFile);

            $data = [
                'endpoint_spreadsheet_processing_status' => $processedFile->id,
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

    /**
     * @param ProcessedFile $processedFile
     *
     * @return void
     */
    private function dispatchProcessing(ProcessedFile $processedFile): void
    {
        //
    }
}
