<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\UtilityException;
use App\Http\Controllers\Controller;
use App\Http\Resources\PageCollection;
use App\Http\Resources\PageResource;
use App\Http\Resources\PopupCollection;
use App\Http\Resources\UtilityResource;
use App\Models\Page;
use App\Models\PopupBanner;
use App\Models\SiteConfig;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SiteConfigController extends Controller
{
    public function siteConfig()
    {
        try {
            $configs = SiteConfig::select('group', 'type', 'value')->orderBy("created_at", "desc")->get();

            $WA_NUMBER = $configs->where('group', 'WA')->where('type', 'WA_NUMBER')->first();
            $WA_TEMPLATE = $configs->where('group', 'WA')->where('type', 'WA_TEMPLATE')->first();
            $COMPANY_ADDRESS = $configs->where('group', 'COMPANY')->where('type', 'COMPANY_ADDRESS')->first();
            $COMPANY_NAME = $configs->where('group', 'COMPANY')->where('type', 'COMPANY_NAME')->first();
            $COMPANY_EMAIL = $configs->where('group', 'COMPANY')->where('type', 'COMPANY_EMAIL')->first();
            $NOTIF = $configs->where('group', 'NOTIF')->where('type', 'NOTIF')->pluck('value');

            $response = [
                'WA_NUMBER' => $WA_NUMBER ? $WA_NUMBER->value : '',
                'WA_TEMPLATE' => $WA_TEMPLATE ? $WA_TEMPLATE->value : '',
                'COMPANY_ADDRESS' => $COMPANY_ADDRESS ? $COMPANY_ADDRESS->value : '',
                'COMPANY_NAME' => $COMPANY_NAME ? $COMPANY_NAME->value : '',
                'COMPANY_EMAIL' => $COMPANY_EMAIL ? $COMPANY_EMAIL->value : '',
                'NOTIF' => $NOTIF,
            ];

            return new UtilityResource($response);
        } catch (\Throwable $th) {
            report($th);
            throw new UtilityException("Something Wrong", Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function pageConfig(Request $request)
    {
        try {

            $page = Page::with('sections.contents.images')->when($request->filled('page_name'), function ($query) use ($request) {
                $query->where(function ($query) use ($request) {
                    $query->whereRaw('UPPER(page_name) = ?', [$request->page_name]);
                });
            })->get();
            if ($page->count() > 1) {
                return new PageCollection($page);
            } else {
                return new PageResource($page[0]);
            }
        } catch (\Throwable $th) {
            report($th);
            throw new UtilityException("Something Wrong", Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function popupConfig(Request $request)
    {
        try {

            $currentDate = Carbon::now()->format('Y-m-d');

            $banner = PopupBanner::with('image')
                ->whereRaw('? between start_date and end_date', [$currentDate])
                ->orderBy('id', 'desc')
                ->get();

            return new PopupCollection($banner);
        } catch (\Throwable $th) {
            report($th);
            throw new UtilityException("Something Wrong", Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
