<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use Validator;
use App\Models\Blog;
use App\Http\Resources\Blog as BlogResource;

class BlogController extends BaseController
{
    public function index()
    {
        $blog = Blog::all();
        return $this->sendResponse(BlogResource::collection($blog), 'Posts blog encontrados');
    }

    public function show($id)
    {
        $blog = Blog::find($id);
        if (is_null($blog)) {
            return $this->sendError('Post does not exist.');
        }
        return $this->sendResponse(new BlogResource($blog), 'Post encontrado.');
    }

    public function store(Request $request)
    {
        try {
            $input = $request->all();
            $validator = Validator::make($input, [
                'title' => 'required',
                'photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg, webp|max:2048',
                'date' => 'required',
                'description' => 'required',
                'author' => 'required'
            ]);

            if ($validator->fails()) {
                return $this->sendError($validator->errors());
            }

            // Subir la imagen al almacenamiento
            $photoPath = $request->file('photo')->store('imgs', 'public');

            // Actualizar la ruta de la imagen en el input
            $input['photo'] = $photoPath;

            // Crear el post
            $blog = Blog::create($input);

            return $this->sendResponse(new BlogResource($blog), 'Post Creado.');
        } catch (\Exception $e) {
            \Log::error('Error al procesar la solicitud', ['error' => $e->getMessage()]);
            return $this->sendError('Error al procesar la solicitud', ['error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $blog = Blog::find($id);

        if (!$blog) {
            return $this->sendError('Blog no encontrado.', [], 404);
        }
        try {
            $blog = Blog::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'title' => 'required',
                'description' => 'required',
                'author' => 'required',
                'date' => 'required',
                'photo' => 'image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            ]);

            if ($validator->fails()) {
                return $this->sendError($validator->errors());
            }

            $input = $request->all();

            // Si hay una nueva imagen, actualiza la ruta de la foto
            if ($request->hasFile('photo')) {
                $photoPath = $request->file('photo')->store('imgs', 'public');
                $input['photo'] = $photoPath;
            }

            $blog->update($input);

            return $this->sendResponse(new BlogResource($blog), 'Blog actualizado.');
        } catch (\Exception $e) {
            \Log::error('Error al procesar la solicitud', ['error' => $e->getMessage()]);
            return $this->sendError('Error al procesar la solicitud', ['error' => $e->getMessage()], 500);
        }
    }

}
