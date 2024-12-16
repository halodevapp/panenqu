<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\ContactForm;
use App\Models\Product;
use App\Models\Subscriber;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class DashboardController extends Controller
{
    public function index()
    {
        $activities = Activity::paginate(10);
        return view('dashboard.dashboard_index', compact('activities'));
    }

    public function logActivities(Request $request)
    {
        $orderableColum = [
            "3" => "activity_log.created_at"
        ];

        $orderBy = array_key_exists($request->order[0]['column'], $orderableColum) ? $orderableColum[$request->order[0]['column']] : "activity_log.created_at";
        $orderDir = array_key_exists("dir", $request->order[0]) ? $request->order[0]["dir"] : "desc";

        $count =  Activity::count();
        $activities = Activity::with(['causer', 'subject'])
            ->take($request->length)
            ->skip($request->start)
            ->orderBy($orderBy, $orderDir)
            ->get();

        $data = [
            "draw" => $request->draw,
            "recordsTotal" => $count,
            "recordsFiltered" => $count,
            "data" => $activities
        ];
        return json_encode($data);
    }

    public function summary(Request $request)
    {
        $products = Product::count();
        $articles = Article::count();
        $subscribers = Subscriber::count();
        $contactForm = ContactForm::count();

        return json_encode(compact("products", "articles", "subscribers", "contactForm"));
    }
}
