<?php

namespace App\Policies;

use App\Models\SiteConfig;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SiteConfigPolicy
{
    use HandlesAuthorization;

    public function notificationView(User $user)
    {
        return $user->can('NOTIF_VIEW');
    }

    public function notificationUpdate(User $user, SiteConfig $siteConfig)
    {
        return $user->can('NOTIF_UPDATE');
    }

    public function notificationCreate(User $user)
    {
        return $user->can('NOTIF_CREATE');
    }

    public function notificationDelete(User $user, SiteConfig $siteConfig)
    {
        return $user->can('NOTIF_DELETE');
    }

    public function whatsappConfig(User $user)
    {
        return $user->can('WHATSAPP_CONFIG');
    }

    public function companyConfig(User $user)
    {
        return $user->can('COMPANY_CONFIG');
    }

    public function instagramConfig(User $user)
    {
        return $user->can('INSTAGRAM_CONFIG');
    }

    public function pageConfig(User $user)
    {
        return $user->can('PAGE_CONFIG');
    }

    public function popupConfig(User $user)
    {
        return $user->can('POPUP_CONFIG');
    }
}
