<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PublicResource extends JsonResource
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
            "id" => $this->id,
            "titleEn" => $this->titleEn,
            "descriptionEn" => $this->descriptionEn,
            "titleBn" => $this->titleBn,
            "descriptionBn" => $this->descriptionBn,
            "imageLocation" => $this->imageLocation,
            "catNameEn" => $this->catNameEn,
            "catNameBn" => $this->catNameBn,
            "tagNameEn" => $this->tagNameEn,
            "tagNameBn" => $this->tagNameBn,
        ];
    }
}
