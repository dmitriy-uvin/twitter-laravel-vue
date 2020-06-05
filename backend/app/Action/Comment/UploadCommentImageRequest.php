<?php

declare(strict_types=1);

namespace App\Action\Comment;


use Illuminate\Http\UploadedFile;

class UploadCommentImageRequest
{
    private $id;
    private $image;

    public function __construct(int $id, UploadedFile $image)
    {
        $this->id = $id;
        $this->image = $image;
    }

    public function getId():int
    {
        return $this->id;
    }

    public function getImage(): UploadedFile
    {
        return $this->image;
    }
}
