<?php
   
namespace App\Http\Controllers\API;
   
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Models\User;
use App\Models\Publicacion;
use Illuminate\Support\Facades\Hash;

   
class AuthController extends BaseController
{
    public function signin(Request $request)
    {
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){ 
            $authUser = Auth::user(); 
            $success['token'] =  $authUser->createToken('MyAuthApp')->plainTextToken; 
            $success['name'] =  $authUser->name;
            $success['type'] =  $authUser->type;
            $success['email'] =  $authUser->email;
            $success['created_at'] =  $authUser->created_at;
   
            return $this->sendResponse($success, 'User signed in');
        } 
        else{ 
            return $this->sendError('Unauthorised.', ['error'=>'Unauthorised']);
        } 
    }
    public function signup(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'confirm_password' => 'required|same:password',
        ]);
   
        if($validator->fails()){
            return $this->sendError('Error validation', $validator->errors());       
        }
   
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['token'] =  $user->createToken('MyAuthApp')->plainTextToken;
        $success['name'] =  $user->name;
   
        return $this->sendResponse($success, 'User created successfully.');
    }

    private function generatePassword()
    {
        $input = '!#$%&?0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $strength = 10;
        $input_length = strlen($input);
        $random_string = '';
        for($i = 0; $i < $strength; $i++) {
            $random_character = $input[mt_rand(0, $input_length - 1)];
            $random_string .= $random_character;
        }
        return $random_string;
    }

    public function recoverPassword(Request $request)
    {
        $user = User::select('email')->where('email', $request->email)->get();

        if(count($user) > 0){
            $newPassword = $this->generatePassword();
            $data = User::where('email', $user[0]->email)->firstOrFail();
            $token = $data->createToken('auth_token')->plainTextToken;
            User::where('email', $user[0]->email)->update(['password' => Hash::make($newPassword)]);
            return response()->json([
                'code' => 200,
                'message' => 'New password generated and updated successfully',
                'password' => $newPassword
            ]);
        }else{
            return response()->json([
                'code' => 400,
                'message' => 'Unable to get new password'
            ]);
        }
    }



    public function logout(Request $request)
    {
        auth()->user()->tokens()->delete();
        return [
            'message' => 'user logged out'
        ];


    }

    public function searchByKeyword(Request $request)
    {
        $keyword = $request->input('keyword');
    
        $publicaciones = Publicacion::where('title', 'LIKE', "%$keyword%")
            ->orWhere('subtitle', 'LIKE', "%$keyword%")
            ->orWhere('description', 'LIKE', "%$keyword%")
            ->get();
    
        if (count($publicaciones) > 0) {
            return response()->json([
                'data' => $publicaciones,
                'message' => 'Resultados de la búsqueda por palabra clave.',
            ]);
        } else {
            return response()->json([
                'message' => 'No se encontraron resultados para la palabra clave proporcionada.',
            ]);
        }
    }



   
}