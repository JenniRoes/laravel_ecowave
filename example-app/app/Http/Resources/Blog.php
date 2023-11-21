<?php
  
namespace App\Http\Resources;
use Illuminate\Http\Resources\Json\JsonResource;


class Blog extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'date' => $this->date ,
            'description' => $this->description,
            'author'=> $this->author,
            'photo' => $this->photo,
            'created_at' => $this->created_at->format('m/d/Y'),
            'updated_at' => $this->updated_at->format('m/d/Y'),
       ];}
}