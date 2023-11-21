<?php
  
namespace App\Http\Resources;
use Illuminate\Http\Resources\Json\JsonResource;
class Publicacion extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'subtitle' => $this->subtitle ,
            'description' => $this->description,
            'author'=> $this->author,
            'ubication' => $this->ubication,
            'photo' => $this->photo,
            'created_at' => $this->created_at->format('m/d/Y'),
            'updated_at' => $this->updated_at->format('m/d/Y'),
        ];
    }
    
}
