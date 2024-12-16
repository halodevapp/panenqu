<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\ContactFormCategoryException;
use App\Http\Controllers\Controller;
use App\Http\Resources\ContactFormCategoryCollection;
use App\Models\ContactFormCategory;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ContactFormCategoryController extends Controller
{
    public function __invoke()
    {
        try {
            $categories = ContactFormCategory::orderBy('category_name', 'asc')->get();
            return new ContactFormCategoryCollection($categories);
        } catch (\Throwable $th) {
            report($th);
            throw new ContactFormCategoryException("Something Wrong", Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
