<?php

namespace App\Http\Controllers;

use App\Exceptions\PageException;
use App\Helpers\MediaStorage;
use App\Http\Requests\CompanyUpdateRequest;
use App\Http\Requests\NotificationStoreRequest;
use App\Http\Requests\NotificationUpdateRequest;
use App\Http\Requests\PopupBannerStoreRequest;
use App\Http\Requests\PopupBannerUpdateRequest;
use App\Http\Requests\WhatsappUpdateRequest;
use App\Http\Resources\PageCollection;
use App\Http\Resources\PageResource;
use App\Http\Resources\PopupCollection;
use App\Models\InstagramMedia;
use App\Models\Media;
use App\Models\Page;
use App\Models\PageContent;
use App\Models\PageContentType;
use App\Models\PageSection;
use App\Models\PopupBanner;
use App\Models\SiteConfig;
use App\Models\SocmedAccessToken;
use Carbon\Carbon;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use stdClass;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Str;

class SiteConfigController extends Controller
{
    /**
     * NOTIFICATIONS
     */
    public function notificationIndex(Request $request)
    {
        $this->authorize('notificationView', SiteConfig::class);


        $notifications = SiteConfig::where(function ($query) use ($request) {
            $query->where('value', 'like', "%{$request->search}%")
                ->orWhereHas('createdBy', function ($query) use ($request) {
                    $query->where('name', 'like', "%{$request->search}%");
                })
                ->orWhereHas('updatedBy', function ($query) use ($request) {
                    $query->where('name', 'like', "%{$request->search}%");
                });
        })->notifications()->orderBy('id', 'desc')->paginate(10);
        return view('site_config.notification_index', compact('notifications'));
    }

    public function notificationCreate()
    {
        $this->authorize('notificationCreate', SiteConfig::class);

        return view('site_config.notification_create');
    }

    public function notificationStore(NotificationStoreRequest $request)
    {
        DB::beginTransaction();
        try {
            SiteConfig::create([
                'group' => SiteConfig::NOTIF_GROUP,
                'type' => SiteConfig::NOTIF_TYPE,
                'value' => $request->value,
                'created_by' => Auth::user()->id,
                'updated_by' => Auth::user()->id,
            ]);

            DB::commit();

            return redirect(route("config.notification.index"))
                ->with("response-message", "Create notification berhasil")
                ->with("response-status", "success");
        } catch (Exception $th) {
            report($th);

            DB::rollBack();

            return redirect(route("config.notification.index"))
                ->with("response-message", "Create notification gagal")
                ->with("response-status", "error");
        }
        DB::rollBack();
    }

    public function notificationEdit(Request $request, $id)
    {
        $notification = SiteConfig::notifications()->where('id', $id)->first();

        $this->authorize('notificationUpdate', $notification);

        return view('site_config.notification_edit', compact('notification'));
    }

    public function notificationUpdate(NotificationUpdateRequest $request, $id)
    {
        DB::beginTransaction();
        try {

            $notification = SiteConfig::notifications()->where('id', $id)->first();
            $this->authorize('notificationUpdate', $notification);

            $notification->value = $request->value;
            $notification->updated_by = Auth::user()->id;
            $notification->save();

            DB::commit();

            return redirect(route("config.notification.index"))
                ->with("response-message", "Update notification berhasil")
                ->with("response-status", "success");
        } catch (Exception $th) {
            report($th);


            DB::rollBack();

            if ($th instanceof AuthorizationException) {
                return redirect(route("config.notification.index"))
                    ->with("response-message", $th->getMessage())
                    ->with("response-status", "error");
            }

            return redirect(route("config.notification.index"))
                ->with("response-message", "Update notification gagal")
                ->with("response-status", "error");
        }
        DB::rollBack();
    }

    public function notificationDestroy($id)
    {
        DB::beginTransaction();
        try {
            $notification = SiteConfig::notifications()->where('id', $id)->first();
            $this->authorize('notificationDelete', $notification);

            $notification->deleted_at = Carbon::now();
            $notification->deleted_by = Auth::user()->id;
            $notification->save();

            DB::commit();

            return redirect(route("config.notification.index"))
                ->with("response-message", "Delete notification berhasil")
                ->with("response-status", "success");
        } catch (Exception $th) {
            report($th);

            DB::rollBack();

            if ($th instanceof AuthorizationException) {
                return redirect(route("config.notification.index"))
                    ->with("response-message", $th->getMessage())
                    ->with("response-status", "error");
            }

            return redirect(route("config.notification.index"))
                ->with("response-message", "Delete notification gagal")
                ->with("response-status", "error");
        }
        DB::rollBack();
    }


    /**
     * WHATSAPP
     */
    public function whatsappIndex(Request $request)
    {
        $this->authorize('whatsappConfig', SiteConfig::class);

        $whatsapps = SiteConfig::where(function ($query) use ($request) {
            $query->where('value', 'like', "%{$request->search}%")
                ->orWhereHas('createdBy', function ($query) use ($request) {
                    $query->where('name', 'like', "%{$request->search}%");
                })
                ->orWhereHas('updatedBy', function ($query) use ($request) {
                    $query->where('name', 'like', "%{$request->search}%");
                });
        })->whatsapp()->orderBy('id', 'desc')->paginate(10);
        return view('site_config.whatsapp_index', compact('whatsapps'));
    }

    public function whatsappEdit(Request $request, $id)
    {
        $whatsapp = SiteConfig::whatsapp()->where('id', $id)->first();
        return view('site_config.whatsapp_edit', compact('whatsapp'));
    }

    public function whatsappUpdate(WhatsappUpdateRequest $request, $id)
    {
        $this->authorize('whatsappConfig', SiteConfig::class);

        DB::beginTransaction();
        try {

            $whatsapp = SiteConfig::whatsapp()->where('id', $id)->first();

            $whatsapp->value = $request->value;
            $whatsapp->updated_by = Auth::user()->id;
            $whatsapp->save();

            DB::commit();

            return redirect(route("config.whatsapp.index"))
                ->with("response-message", "Update whatsapp berhasil")
                ->with("response-status", "success");
        } catch (Exception $th) {
            report($th);

            DB::rollBack();

            return redirect(route("config.whatsapp.index"))
                ->with("response-message", "Update whatsapp gagal")
                ->with("response-status", "error");
        }
        DB::rollBack();
    }

    /**
     * COMPANY
     */
    public function companyIndex(Request $request)
    {
        $this->authorize('companyConfig', SiteConfig::class);

        $companies = SiteConfig::where(function ($query) use ($request) {
            $query->where('value', 'like', "%{$request->search}%")
                ->orWhereHas('createdBy', function ($query) use ($request) {
                    $query->where('name', 'like', "%{$request->search}%");
                })
                ->orWhereHas('updatedBy', function ($query) use ($request) {
                    $query->where('name', 'like', "%{$request->search}%");
                });
        })->company()->orderBy('id', 'desc')->paginate(10);
        return view('site_config.company_index', compact('companies'));
    }

    public function companyEdit(Request $request, $id)
    {
        $this->authorize('companyConfig', SiteConfig::class);
        $company = SiteConfig::company()->where('id', $id)->first();
        return view('site_config.company_edit', compact('company'));
    }

    public function companyUpdate(CompanyUpdateRequest $request, $id)
    {
        $this->authorize('companyConfig', SiteConfig::class);
        DB::beginTransaction();
        try {

            $company = SiteConfig::company()->where('id', $id)->first();

            $company->value = $request->value;
            $company->updated_by = Auth::user()->id;
            $company->save();

            DB::commit();

            return redirect(route("config.company.index"))
                ->with("response-message", "Update company berhasil")
                ->with("response-status", "success");
        } catch (Exception $th) {
            report($th);

            DB::rollBack();

            return redirect(route("config.company.index"))
                ->with("response-message", "Update company gagal")
                ->with("response-status", "error");
        }
        DB::rollBack();
    }

    public function instagramIndex()
    {
        $this->authorize('instagramConfig', SiteConfig::class);

        try {
            $token = SocmedAccessToken::where('client_id', env('IG_CLIENT_ID'))->first();

            $instagramRequest = Http::get('https://graph.instagram.com/me/media', [
                'fields' => 'media_type,media_url,permalink,thumbnail_url,timestamp,username,caption',
                'access_token' => $token->refresh_token,
                'limit' => 8,
            ]);

            $instagram = json_decode($instagramRequest->body());
            if (property_exists($instagram, 'error')) {
                throw new Exception($instagram->error->message);
            }

            $data = array(
                'instagram' => $instagram,
                'success' => true,
            );

            return view('instagram.instagram_index', compact('data'));
        } catch (\Throwable $th) {
            report($th);

            $data = array(
                'success' => false,
            );
            return view('instagram.instagram_index', compact('data'));
        }
    }

    public function instagramRedirect()
    {
        $this->authorize('instagramConfig', SiteConfig::class);

        $cliend_id = env('IG_CLIENT_ID');
        $redirect_uri = env('IG_CALLBACK_URI');
        return redirect("https://api.instagram.com/oauth/authorize?client_id={$cliend_id}&redirect_uri={$redirect_uri}&scope=user_profile,user_media&response_type=code");
    }

    public function instagramCallback(Request $request)
    {
        $this->authorize('instagramConfig', SiteConfig::class);

        try {
            $code = $request->code;
            $cliend_id = env('IG_CLIENT_ID');
            $cliend_secret = env('IG_CLIENT_SECRET');
            $redirect_uri = env('IG_CALLBACK_URI');

            $instagram = Http::asForm()->post('https://api.instagram.com/oauth/access_token', [
                'client_id' => $cliend_id,
                'client_secret' => $cliend_secret,
                'code' => $code,
                'grant_type' => 'authorization_code',
                'redirect_uri' => $redirect_uri
            ]);

            $response = json_decode($instagram->body());

            if (property_exists($response, 'error_message')) {
                return redirect(route("config.instagram.index"))
                    ->with("response-message", $response->error_message)
                    ->with("response-status", "error");
            }


            $existing = SocmedAccessToken::where('client_id', $cliend_id)->first();

            if ($existing) {
                $existing->deleted_at = Carbon::now();
                $existing->deleted_by = Auth::user()->id;
                $existing->save();
            }

            /**
             * Exchange short lived access token to long lived user access token
             */

            $longLived = Http::get("https://graph.instagram.com/access_token", [
                'grant_type' => 'ig_exchange_token',
                'client_secret' => env('IG_CLIENT_SECRET'),
                'access_token' => $response->access_token
            ]);

            $refreshToken = json_decode($longLived->body());

            SocmedAccessToken::create([
                'client_id' => $cliend_id,
                'user_id' => $response->user_id,
                'access_code' => $code,
                'access_token' => $response->access_token,
                'refresh_token' => $refreshToken->access_token,
                'created_by' => Auth::user()->id,
                'updated_by' => Auth::user()->id,
            ]);

            $instagramRequest = Http::get('https://graph.instagram.com/me/media', [
                'fields' => 'media_type,media_url,permalink,thumbnail_url,timestamp,username,caption',
                'access_token' => $refreshToken->access_token
            ]);

            $instagram = json_decode($instagramRequest->body(), true);

            if (array_key_exists('data', $instagram)) {
                InstagramMedia::truncate();

                $instagramData = array();
                foreach ($instagram['data'] as $data) {

                    if ($data['media_type'] == 'IMAGE' || $data['media_type'] == 'CAROUSEL_ALBUM') {
                        $data['thumbnail_url'] = null;
                    }

                    array_push($instagramData, $data);
                }
                InstagramMedia::insert($instagramData);
            }

            return redirect(route('config.instagram.index'));
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function pageIndex()
    {
        $this->authorize('pageConfig', SiteConfig::class);
        return view('site_config.page_index');
    }

    public function pageGetAll(Request $request)
    {
        $this->authorize('pageConfig', SiteConfig::class);

        $orderable = [
            "1" => "pages.page_name",
        ];

        if (array_key_exists($request->order[0]['column'], $orderable)) {
            $orderBy = $orderable[$request->order[0]['column']];
            $orderDir = $request->order[0]['dir'];
        } else {
            $orderBy = "pages.id";
            $orderDir = "desc";
        }

        try {
            $page = Page::where(function ($query) use ($request) {
                $query->where("page_name", "like", "%{$request->search['value']}%");
            })
                ->offset($request->start)
                ->limit($request->length)
                ->orderBy($orderBy, $orderDir)
                ->get();

            $pageResource = new PageCollection($page);
            $recordsTotal = Page::count();
            $recordsFiltered = trim($request->search['value']) == "" ?  $recordsTotal : $page->count();

            return response()->json([
                "draw" => $request->draw,
                "recordsTotal" => $recordsTotal,
                "recordsFiltered" => $recordsFiltered,
                "data" => $pageResource
            ]);
        } catch (\Throwable $th) {
            report($th);

            throw $th;
        }
    }

    public function pageEdit(Page $page)
    {
        $this->authorize('pageConfig', SiteConfig::class);
        $sectionTypes = PageContentType::all();
        return view('site_config.page_edit', compact('page', 'sectionTypes'));
    }

    public function pageContentStore(Request $request)
    {
        DB::beginTransaction();
        try {
            $this->authorize('pageConfig', SiteConfig::class);

            $currentSequence = PageContent::select('seq')
                ->where('page_section', $request->sectionID)
                ->where('page_type', $request->typeID)
                ->orderBy('seq', 'DESC')
                ->first();

            $seq = 1;
            if ($currentSequence) {
                $seq = $currentSequence->seq + 1;
            }

            $pageContent = PageContent::create([
                'page_section' => $request->sectionID,
                'page_type' => $request->typeID,
                'seq' => $seq,
                'key' => $request->key ? $request->key : '',
                'value' => $request->value ? $request->value : '',
                'created_by' => Auth::user()->id
            ]);

            if (!$pageContent) {
                throw new Exception("Simpan section content gagal");
            }

            /**
             * Jika page type == Image
             */
            if ($request->typeID == 2) {
                if ($request->hasFile("image_desktop")) {
                    $image = $request->file("image_desktop");

                    $storagePath = Media::BUCKET . "/" . config('app.env') . "/" . Media::PAGE_PATH . "/" . $pageContent->id;
                    $fileName = $image->hashName();
                    $mimeType = $image->getClientMimeType();

                    $image->storeAs($storagePath, $fileName);

                    $filePath = $storagePath . "/" . $fileName;
                    if (Storage::exists($filePath)) {
                        Media::create([
                            'model_category' => Page::MEDIA_CATEGORY,
                            'model_id' =>  $pageContent->id,
                            'viewport' => Media::DESKTOP,
                            'media_type' => $mimeType,
                            'media_name' => $fileName,
                            'path' => $filePath,
                            'created_by' => Auth::user()->id,
                            'updated_by' => Auth::user()->id,
                        ]);
                    }
                } else {
                    throw new Exception("Upload image untuk desktop view");
                }

                if ($request->hasFile("image_mobile")) {
                    $image = $request->file("image_mobile");

                    $storagePath = Media::BUCKET . "/" . config('app.env') . "/" . Media::PAGE_PATH . "/" . $pageContent->id;
                    $fileName = $image->hashName();
                    $mimeType = $image->getClientMimeType();

                    $image->storeAs($storagePath, $fileName);

                    $filePath = $storagePath . "/" . $fileName;
                    if (Storage::exists($filePath)) {
                        Media::create([
                            'model_category' => Page::MEDIA_CATEGORY,
                            'model_id' =>  $pageContent->id,
                            'viewport' => Media::MOBILE,
                            'media_type' => $mimeType,
                            'media_name' => $fileName,
                            'path' => $filePath,
                            'created_by' => Auth::user()->id,
                            'updated_by' => Auth::user()->id,
                        ]);
                    }
                } else {
                    throw new Exception("Upload image untuk mobile view");
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Success',
                'data' => []
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();

            report($th);
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
                'data' => []
            ]);
        }
        DB::rollBack();
    }

    public function pageContentShow(Request $request)
    {
        $this->authorize('pageConfig', SiteConfig::class);

        try {
            $content = PageContent::with(['group', 'section', 'images'])->where('id', $request->contentID)->first();

            foreach ($content->images as $key => $image) {
                $content->images[$key]->url = (new MediaStorage())->getFile($image->id, $image->media_name);
            }

            return response()->json([
                'success' => true,
                'message' => 'Success',
                'data' => $content
            ]);
        } catch (\Throwable $th) {
            report($th);
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
                'data' => []
            ]);
        }
    }

    public function pageContentUpdate(Request $request)
    {

        DB::beginTransaction();
        try {
            $this->authorize('pageConfig', SiteConfig::class);

            $content = PageContent::with('images')->where("id", $request->contentID)->first();

            if (!$content) {
                throw new Exception("Page content not found");
            }

            $content->key = $request->key ? $request->key : '';
            $content->value = $request->value ? $request->value : '';
            $content->updated_by = Auth::user()->id;

            if ($request->hasFile("image_desktop")) {

                $imageDesktop = $content->images->where("viewport", Media::DESKTOP)->first();

                Storage::delete($imageDesktop->path);

                $image = $request->file("image_desktop");

                $storagePath = Media::BUCKET . "/" . config('app.env') . "/" . Media::PAGE_PATH . "/" . $content->id;
                $fileName = $image->hashName();
                $mimeType = $image->getClientMimeType();

                $image->storeAs($storagePath, $fileName);

                $filePath = $storagePath . "/" . $fileName;

                $imageDesktop->media_type = $mimeType;
                $imageDesktop->media_name = $fileName;
                $imageDesktop->path = $filePath;
                $imageDesktop->updated_at = Carbon::now();
                $imageDesktop->updated_by = Auth::user()->id;
                $imageDesktop->save();
            }

            if ($request->hasFile("image_mobile")) {

                $imageMobile = $content->images->where("viewport", Media::MOBILE)->first();

                Storage::delete($imageMobile->path);

                $image = $request->file("image_mobile");

                $storagePath = Media::BUCKET . "/" . config('app.env') . "/" . Media::PAGE_PATH . "/" . $content->id;
                $fileName = $image->hashName();
                $mimeType = $image->getClientMimeType();

                $image->storeAs($storagePath, $fileName);

                $filePath = $storagePath . "/" . $fileName;

                $imageMobile->media_type = $mimeType;
                $imageMobile->media_name = $fileName;
                $imageMobile->path = $filePath;
                $imageMobile->updated_at = Carbon::now();
                $imageMobile->updated_by = Auth::user()->id;
                $imageMobile->save();
            }

            $content->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Success',
                'data' => $content
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            report($th);
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
                'data' => []
            ]);
        }
        DB::rollBack();
    }

    public function pageSectionStore(Request $request)
    {
        $this->authorize('pageConfig', SiteConfig::class);

        try {
            $section = str_replace(' ', '_', trim($request->section));
            $section = Str::upper($section);

            $isExist = PageSection::select('id')->where('page_id', $request->pageID)
                ->where('section_name', $section)
                ->first();

            if ($isExist) {
                throw new Exception("Section sudah ada");
            }

            $section = PageSection::create([
                'page_id' => $request->pageID,
                'section_name' => $section,
                'created_by' => Auth::user()->id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Success',
                'data' => $section
            ]);
        } catch (\Throwable $th) {
            report($th);
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
                'data' => []
            ]);
        }
    }

    public function pageCreate(Request $request)
    {
        $this->authorize('pageConfig', SiteConfig::class);

        try {
            $pageName = str_replace(' ', '_', trim($request->pageName));
            $pageName = Str::upper($pageName);

            $isExist = Page::select('id')
                ->where('page_name', $pageName)
                ->first();

            if ($isExist) {
                throw new Exception("Page sudah ada");
            }

            $page = Page::create([
                'page_name' => $pageName,
                'created_by' => Auth::user()->id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Success',
                'data' => $page
            ]);
        } catch (\Throwable $th) {
            report($th);
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
                'data' => []
            ]);
        }
    }

    public function pageDestroy(Request $request)
    {
        $this->authorize('pageConfig', SiteConfig::class);

        DB::beginTransaction();
        try {

            $pageContent = PageContent::with('section')->where('id', $request->page)->first();
            if (!$pageContent) {
                throw new PageException("Page content not found");
            }

            $pageContent->deleted_at = Carbon::now();
            $pageContent->deleted_by = Auth::user()->id;
            $pageContent->save();

            DB::commit();

            return redirect(route("config.page.edit", ['page' => $pageContent->section->page_id]))
                ->with("response-message", "Delete page content berhasil")
                ->with("response-status", "success");
        } catch (Exception $th) {
            report($th);

            DB::rollBack();

            return redirect(route("config.page.edit", ['page' => $pageContent->section->page_id]))
                ->with("response-message", "Delete page content gagal")
                ->with("response-status", "error");
        }
        DB::rollBack();
    }


    public function popupIndex()
    {
        $this->authorize('popupConfig', SiteConfig::class);

        return view('site_config.popup_banner_index');
    }

    public function popupGetAll(Request $request)
    {
        $this->authorize('popupConfig', SiteConfig::class);

        $orderable = [
            "2" => "popup_banners.start_date",
            "3" => "popup_banners.end_date",
        ];

        if (array_key_exists($request->order[0]['column'], $orderable)) {
            $orderBy = $orderable[$request->order[0]['column']];
            $orderDir = $request->order[0]['dir'];
        } else {
            $orderBy = "popup_banners.id";
            $orderDir = "desc";
        }

        try {
            $popup = PopupBanner::where(function ($query) use ($request) {
                $query->where("description", "like", "%{$request->search['value']}%");
            })
                ->offset($request->start)
                ->limit($request->length)
                ->orderBy($orderBy, $orderDir)
                ->get();

            $popupResource = new PopupCollection($popup);
            $recordsTotal = PopupBanner::count();
            $recordsFiltered = trim($request->search['value']) == "" ?  $recordsTotal : $popup->count();

            return response()->json([
                "draw" => $request->draw,
                "recordsTotal" => $recordsTotal,
                "recordsFiltered" => $recordsFiltered,
                "data" => $popupResource
            ]);
        } catch (\Throwable $th) {
            report($th);

            throw $th;
        }
    }

    public function popupCreate()
    {
        $this->authorize('popupConfig', SiteConfig::class);

        return view('site_config.popup_banner_create');
    }

    public function popupStore(PopupBannerStoreRequest $request)
    {
        DB::beginTransaction();
        try {
            $this->authorize('popupConfig', SiteConfig::class);

            $user = Auth::user();

            $banner = PopupBanner::create([
                "description" => $request->description,
                "start_date" => $request->start_date,
                "end_date" => $request->end_date,
                "web_link" => $request->web_link,
                "created_by" => $user->id,
                "updated_by" => $user->id,
            ]);

            if (!$banner) {
                throw new Exception("Create pop up banner gagal");
            }

            /**
             * Upload Banner
             */
            $uploadPath = Media::BUCKET  . "/" . config('app.env') . "/" . Media::BANNER_PATH . "/" . $banner->id;
            $image = Storage::put($uploadPath, $request->image);

            if ($request->file('image')->isValid()) {
                $fileExploded = explode("/", $image);
                $fileName = end($fileExploded);

                Media::create([
                    'model_category' => PopupBanner::MEDIA_CATEGORY,
                    'model_id' =>  $banner->id,
                    'viewport' => Media::DESKTOP,
                    'media_type' => $request->file('image')->getClientMimeType(),
                    'media_name' => $fileName,
                    'path' => $image,
                    'created_by' => $user->id,
                    'updated_by' => $user->id,
                ]);
            } else {
                throw new Exception("Upload pop up banner gagal");
            }

            DB::commit();

            return redirect(route("config.popup.index"))
                ->with("response-message", "Create pop up banner berhasil")
                ->with("response-status", "success");
        } catch (\Throwable $th) {
            report($th);

            DB::rollBack();

            return redirect(route("config.popup.index"))
                ->with("response-message", $th->getMessage())
                ->with("response-status", "error");
        }
        DB::rollBack();
    }

    public function popupEdit(Request $request)
    {
        $banner = PopupBanner::with('image')->where("id", $request->id)->first();
        $banner->image->url = (new MediaStorage())->getFile($banner->image->id, $banner->image->media_name);
        return view('site_config.popup_banner_edit', compact('banner'));
    }

    public function popupUpdate(PopupBannerUpdateRequest $request)
    {
        DB::beginTransaction();
        try {

            $banner = PopupBanner::with('image')->where("id", $request->id)->first();

            if (!$banner) {
                throw new Exception("Banner tidak ditemukan");
            }

            $user = Auth::user();

            $banner->description = $request->description;
            $banner->start_date = $request->start_date;
            $banner->end_date = $request->end_date;
            $banner->web_link = $request->web_link;
            $banner->updated_by = $user->id;
            $banner->save();

            if ($request->hasFile('image')) {
                /**
                 * Upload Banner
                 */
                $uploadPath = Media::BUCKET  . "/" . config('app.env') . "/" . Media::BANNER_PATH . "/" . $banner->id;
                $image = Storage::put($uploadPath, $request->image);

                if ($request->file('image')->isValid()) {
                    $fileExploded = explode("/", $image);
                    $fileName = end($fileExploded);

                    $banner->image->media_type = $request->file('image')->getClientMimeType();
                    $banner->image->media_name = $fileName;
                    $banner->image->path = $image;
                    $banner->image->updated_by = $user->id;
                    $banner->image->save();
                } else {
                    throw new Exception("Upload pop up banner gagal");
                }
            }

            DB::commit();

            return redirect(route("config.popup.index"))
                ->with("response-message", "Update pop up banner berhasil")
                ->with("response-status", "success");
        } catch (\Throwable $th) {
            report($th);

            DB::rollBack();

            return redirect(route("config.popup.index"))
                ->with("response-message", $th->getMessage())
                ->with("response-status", "error");
        }
        DB::rollBack();
    }

    public function popupDestroy(Request $request)
    {
        DB::beginTransaction();
        try {

            $banner = PopupBanner::with('image')->where("id", $request->id)->first();

            if (!$banner) {
                throw new Exception("Banner tidak ditemukan");
            }

            $user = Auth::user();
            $currentDate = Carbon::now();

            $banner->deleted_by = $user->id;
            $banner->deleted_at = $currentDate;
            $banner->save();

            $banner->image->deleted_by = $user->id;
            $banner->image->deleted_at = $currentDate;
            $banner->image->save();

            DB::commit();

            return redirect(route("config.popup.index"))
                ->with("response-message", "Delete pop up banner berhasil")
                ->with("response-status", "success");
        } catch (\Throwable $th) {
            report($th);

            DB::rollBack();

            return redirect(route("config.popup.index"))
                ->with("response-message", $th->getMessage())
                ->with("response-status", "error");
        }
        DB::rollBack();
    }
}
