<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\CustomerException;
use App\Http\Controllers\Controller;
use App\Http\Resources\CustomerCategoryCollection;
use App\Http\Resources\CustomerCollection;
use App\Models\Customer;
use App\Models\CustomerCategory;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CustomerController extends Controller
{
    public function groupByCategory()
    {
        try {
            $customerCategory = CustomerCategory::with("customer.images")->has("customer")
                ->orderBy("sequence")
                ->get();

            return new CustomerCategoryCollection($customerCategory);
        } catch (\Throwable $th) {
            report($th);

            throw new CustomerException("Something Wrong", Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function byCategory($category)
    {
        try {
            $customers = Customer::with('images')->orderBy('id', 'desc')->whereHas('category', function ($query) use ($category) {
                $query->where('category_name', $category);
            })->get();
            return new CustomerCollection($customers);
        } catch (\Throwable $th) {
            report($th);

            throw new CustomerException("Something Wrong", Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
