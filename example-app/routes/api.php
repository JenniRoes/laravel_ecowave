<?php
  
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
  
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\PublicacionController;
use App\Http\Controllers\API\SaveController;
use App\Http\Controllers\API\BlogController;

  
//NO AUTENTICACIÓN
Route::post('login', [AuthController::class, 'signin']);
Route::post('register', [AuthController::class, 'signup']);
Route::get('publicacion/index', [PublicacionController::class, 'index']);//ver todas publicaciones sin tener usuario
Route::post('/recoverpassword', [AuthController::class, 'recoverPassword']);
Route::get('/publicacion/searchbykeyword', [AuthController::class, 'searchByKeyword']);
Route::get('publicacion/index/{postId}', [PublicacionController::class, 'show']);
Route::put('publicacion/update/{id}', [PublicacionController::class,'update']);
Route::get('blog/index/{postId}', [BlogController::class,'show']);
Route::get('blog/index', [BlogController::class,'index']);

Route::post('blog/store', [BlogController::class, 'store']);

//AUTENTICACIÓN
Route::middleware('auth:sanctum')->group( function () {
    Route::post('publicacion/store', [PublicacionController::class, 'store']);//crear publicacion
    //Route::resource('publicacion', PublicacionController::class);
    Route::post('publicacion/save/store', [SaveController::class, 'store']);//Guardar publicacion en perfil
    Route::get('publicacion/save/index', [SaveController::class, 'index']);//ver todos en guardados en perfil
    Route::post('/logout', [AuthController::class, 'logout']);//Hacer logout
   

    
    //https://olodocoder.hashnode.dev/laravel-api-series-laravel-sanctum-setup-sign-up-login-and-logout
});

