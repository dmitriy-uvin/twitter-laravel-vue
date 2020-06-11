<?php

declare(strict_types = 1);

namespace App\Http\Request\Api;

use App\Http\Request\ApiFormRequest;

final class SendNotificationToUserHttpRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'receiver' => '',
            'liker' => '',
            'liked_entity_id' => '',
            'type' => ''
        ];
    }
}
