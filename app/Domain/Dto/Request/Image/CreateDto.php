<?php
namespace App\Domain\Dto\Request\Image;

use Illuminate\Http\UploadedFile;

class CreateDto
{
    public UploadedFile $image;

    public function __construct(
        UploadedFile $image
    ) {
        $this->image = $image;
    }
}
