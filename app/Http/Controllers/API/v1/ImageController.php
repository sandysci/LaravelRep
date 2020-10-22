<?php

namespace App\Http\Controllers\API\v1;

use App\Domain\Utils\ImageUpload;
use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Image\CreateRequest;

class ImageController extends Controller
{
    public function store(CreateRequest $request)
    {
        try {
            $dto = $request->convertToDto();
            $imageUrl = ImageUpload::uploadImageAndGetUrl($dto->image, 'user_avatar');
            return ApiResponse::responseSuccess(['url' => $imageUrl], 'Image added');
        } catch (\Exception $e) {
            return ApiResponse::responseException($e, 400, 'Error uploading image');
        }
    }
}
