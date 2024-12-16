<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class BaseCollection extends ResourceCollection
{
    public $message = 'Success';

    public function __construct($resource = [], $message = null)
    {
        parent::__construct($resource);
        if ($message) {
            $this->message = $message;
        }
    }

    public function with($request)
    {
        return [
            'success' => true,
            'message' =>  $this->message
        ];
    }
}
