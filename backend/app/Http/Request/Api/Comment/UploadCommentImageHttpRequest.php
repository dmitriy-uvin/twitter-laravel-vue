<?php

declare(strict_types=1);

namespace App\Http\Request\Api\Comment;

use App\Http\Request\ApiFormRequest;

class UploadCommentImageHttpRequest extends ApiFormRequest
{
    public function rules()
    {
        return [
            'image' => 'required|image'
        ];
    }
}
