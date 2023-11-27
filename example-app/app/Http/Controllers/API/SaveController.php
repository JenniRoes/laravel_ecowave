<?php
   
namespace App\Http\Controllers\API;
   
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use Validator;
use App\Models\Save;
use App\Http\Resources\Save as SaveResource;
   
class SaveController extends BaseController
{
    public function index()
    {
        $saves = Save::all();
        return $this->sendResponse(SaveResource::collection($saves), 'Posts encontrados.');
    }

    public function store(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'users_id' => 'required',
            'publicacions_id' => 'required',
            'title' => 'required',
            'description' => 'required',
        ]);
    
        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }
    
        $save = Save::create($input);
    
        return $this->sendResponse(new SaveResource($save), 'Post Creado.');
    }
    
   
    public function show($id)
    {
        $save = Save::find($id);
        if (is_null($save)) {
            return $this->sendError('Post does not exist.');
        }
        return $this->sendResponse(new SaveResource($save), 'Post encontrado.');
    }
    
    public function update(Request $request, Save $save)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'users_id' => 'required',
            'publicacions_id' => 'required'
        ]);
        if($validator->fails()){
            return $this->sendError($validator->errors());       
        }
        $save->users_id = $input['users_id'];
        $save->publicacions_id = $input['publicacions_id'];
        $save->save();
        
        return $this->sendResponse(new SaveResource($save), 'Post updated.');
    }
   
    public function destroy(Save $save)
    {
        $save->delete();
        return $this->sendResponse([], 'Registro Borrado.');
    }
}