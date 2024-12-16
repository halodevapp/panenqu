<?php

namespace App\View\Composers;

use App\Models\SiteConfig;
use App\Repositories\UserRepository;
use Illuminate\View\View;

class EmailComposer
{
    /**
     * The user repository implementation.
     *
     * @var \App\Repositories\UserRepository
     */
    protected $siteConfig;

    /**
     * Create a new profile composer.
     *
     * @param  \App\Repositories\UserRepository  $users
     * @return void
     */
    public function __construct()
    {
        // Dependencies are automatically resolved by the service container...
        $configs = SiteConfig::select('group', 'type', 'value')->get();

        $WA_NUMBER = $configs->where('group', 'WA')->where('type', 'WA_NUMBER')->first();
        $WA_TEMPLATE = $configs->where('group', 'WA')->where('type', 'WA_TEMPLATE')->first();
        $COMPANY_ADDRESS = $configs->where('group', 'COMPANY')->where('type', 'COMPANY_ADDRESS')->first();
        $COMPANY_NAME = $configs->where('group', 'COMPANY')->where('type', 'COMPANY_NAME')->first();
        $COMPANY_EMAIL = $configs->where('group', 'COMPANY')->where('type', 'COMPANY_EMAIL')->first();
        $NOTIF = $configs->where('group', 'NOTIF')->where('type', 'NOTIF')->pluck('value');

        $this->siteConfig = [
            'WA_NUMBER' => $WA_NUMBER ? $WA_NUMBER->value : '',
            'WA_TEMPLATE' => $WA_TEMPLATE ? $WA_TEMPLATE->value : '',
            'COMPANY_ADDRESS' => $COMPANY_ADDRESS ? $COMPANY_ADDRESS->value : '',
            'COMPANY_NAME' => $COMPANY_NAME ? $COMPANY_NAME->value : '',
            'COMPANY_EMAIL' => $COMPANY_EMAIL ? $COMPANY_EMAIL->value : '',
            'NOTIF' => $NOTIF,
        ];
    }

    /**
     * Bind data to the view.
     *
     * @param  \Illuminate\View\View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $view->with('siteConfig', $this->siteConfig);
    }
}
