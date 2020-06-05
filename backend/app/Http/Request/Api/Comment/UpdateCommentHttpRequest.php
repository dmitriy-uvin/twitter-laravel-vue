<?php

declare(strict_types=1);

namespace App\Http\Request\Api\Comment;

use App\Http\Request\ApiFormRequest;

final class UpdateCommentHttpRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'body' => 'required',
        ];
    }
}
