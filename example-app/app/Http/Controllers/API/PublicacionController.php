<?php
   
namespace App\Http\Controllers\API;
   
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use Validator;
use App\Models\Publicacion;
use App\Http\Resources\Publicacion as PublicacionResource;
   
class PublicacionController extends BaseController
{
    public function index()
    {
        $publicaciones = Publicacion::all();
        return $this->sendResponse(PublicacionResource::collection($publicaciones), 'Posts encontrados.');
    }
    
    public function store(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [

            'title' => 'required',
            'subtitle'=> 'required',
            'description'=> 'required',
            'author'=> 'required',
            'ubication'=> 'required',
            'photo'=> 'required'
        ]);
        if($validator->fails()){
            return $this->sendError($validator->errors());       
        }
        $publicacion = Publicacion::create($input);
        return $this->sendResponse(new PublicacionResource($publicacion), 'Post Creado.');
    }
   
    public function show($id)
    {
        $publicacion = Publicacion::find($id);
        if (is_null($publicacion)) {
            return $this->sendError('Post does not exist.');
        }
        return $this->sendResponse(new PublicacionResource($publicacion), 'Post encontrado.');
    }
    
   /* public function update(Request $request, Publicacion $publicacion)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'title' => 'required',
            'subtitle'=> 'required',
            'description'=> 'required',
            'author'=> 'required',
            'ubication'=> 'required',
            'photo'=> 'required'
        ]);
        if($validator->fails()){
            return $this->sendError($validator->errors());       
        }
        $publicacion->title = $input['title'];
        $publicacion->subtitle= $input['subtitle'];
        $publicacion->description = $input['description'];
        $publicacion->author = $input['author'];
        $publicacion->ubication = $input['ubication'];
        $publicacion->photo = $input['photo'];
        $publicacion->save();
        
        return $this->sendResponse(new PublicacionResource($publicacion), 'Post updated.');
    }*/

    public function update(Request $request, $id)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'title' => 'required',
            'subtitle' => 'required',
            'description' => 'required',
            'author' => 'required',
            'ubication' => 'required',
            'photo' => 'required'
        ]);
    
        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }
    
        // Buscar la publicación por ID
        $publicacion = Publicacion::find($id);
    
        if (empty($publicacion)) {
            return $this->sendError('Publicación no encontrada');
        }
    
        // Actualizar la publicación con los nuevos datos
        $publicacion->update($input);
    
        return $this->sendResponse(new PublicacionResource($publicacion), 'Post updated.');
    }

   
    public function destroy(Publicacion $publicacion)
    {
        $publicacion->delete();
        return $this->sendResponse([], 'Registro Borrado.');
    }
}