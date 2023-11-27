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
        try {
            $input = $request->all();
            $validator = Validator::make($input, [

                'title' => 'required',
                'subtitle' => 'required',
                'description' => 'required',
                'author' => 'required',
                'ubication' => 'required',
                'photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048'
            ]);
            if ($validator->fails()) {
                return $this->sendError($validator->errors());
            }
            // Subir la imagen al almacenamiento
            $photoPath = $request->file('photo')->store('imgs', 'public');

            // Actualizar la ruta de la imagen en el input
            $input['photo'] = $photoPath;

            $publicacion = Publicacion::create($input);

            return $this->sendResponse(new PublicacionResource($publicacion), 'Post Creado.');
        } catch (\Exception $e) {
            \Log::error('Error al procesar la solicitud', ['error' => $e->getMessage()]);
            return $this->sendError('Error al procesar la solicitud', ['error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $publicacion = Publicacion::find($id);
        if (is_null($publicacion)) {
            return $this->sendError('Post does not exist.');
        }
        return $this->sendResponse(new PublicacionResource($publicacion), 'Post encontrado.');
    }

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


    public function destroy($id)
    {
        try {
            $publicacion = Publicacion::findOrFail($id);
            $publicacion->delete();
    
            return $this->sendResponse([], 'Publicación eliminada correctamente.');
        } catch (\Exception $e) {
            \Log::error('Error al procesar la solicitud', ['error' => $e->getMessage()]);
            return $this->sendError('Error al procesar la solicitud', ['error' => $e->getMessage()], 500);
        }
    }
}
    