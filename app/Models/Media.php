<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Media extends Model
{
    use HasFactory, SoftDeletes;

    const DEFAULT_NAME_FILE = "panenqu";

    const BUCKET = "website";
    const PRODUCT_PATH = 'product';
    const PRODUCT_CATEGORY_PATH = 'product-category';
    const ARTICLE_PATH = 'article';
    const STORE_PATH = 'store';
    const CUSTOMER_PATH = 'customer';
    const EVENT_GALERY_PATH = 'event-galery';
    const BANNER_PATH = 'banner';
    const PAGE_PATH = 'PAGE';

    const EVENT_GALERY_MODEL = 'EVENT_GALERY';

    const DESKTOP = 'DESKTOP';
    const MOBILE = 'MOBILE';

    protected $guarded = ['id'];
}
