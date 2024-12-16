<?php

namespace App\Http\Resources;

use App\Helpers\MediaStorage;
use Carbon\Carbon;
use Illuminate\Support\Str;

class ArticleResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'article_slug' => $this->article_slug,
            'article_title' => $this->article_title,
            'article_content' => $this->article_content,
            'article_content_preview' => Str::limit($this->article_content, 100),
            'article_category_slug' => $this->category->category_slug,
            'publish_date_timestamp' => $this->publish_date,
            'publish_date' => Carbon::parse($this->publish_date)->format('j F Y'),
            'images' => [
                'thumbnail' => $this->when($this->relationLoaded('thumbnail'), function () {
                    if ($this->thumbnail->count() == 1) {
                        return new MediaResource($this->thumbnail[0]);
                    } else {
                        return new MediaCollection($this->thumbnail);
                    }
                }),
                'banner' => $this->when($this->relationLoaded('banner'), function () {
                    if ($this->banner->count() == 1) {
                        return new MediaResource($this->banner[0]);
                    } else {
                        return new MediaCollection($this->banner);
                    }
                }),
            ],
            'related' => new ArticleCollection($this->whenLoaded('related')),
            'other' => $this->when($this->loadOther, function () {
                return new ArticleCollection($this->other);
            })
        ];
    }
}
