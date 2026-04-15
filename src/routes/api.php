<?php
// use App\Http\Controllers\ConductorVehiculoController;
// use App\Http\Controllers\PropietarioVehiculoController;
// use App\Http\Controllers\AuthController;
// use App\Http\Controllers\PropietarioController;
// use App\Http\Controllers\VehiculoController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\BeneficiarioController;
use App\Http\Controllers\ColegioController;
use App\Http\Controllers\SubscripcionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
// Route::post('login', [AuthController::class, 'login']);

// Route::middleware('auth:sanctum')->group(function () {

//     Route::post('logout', [AuthController::class, 'logout']);

//     Route::apiResource('vehiculos', VehiculoController::class);
//     Route::apiResource('propietarios', PropietarioController::class);
//     Route::apiResource('conductores', ConductorController::class);
//     Route::apiResource('propietario-vehiculo', PropietarioVehiculoController::class);
//     Route::apiResource('conductor-vehiculo', ConductorVehiculoController::class);

//     Route::get(
//     'vehiculos/{vehiculo}/conductores-activos',
//     [ConductorVehiculoController::class, 'conductoresActivos']
// );

// });
Route::middleware('auth:sanctum')->get('/mis-beneficiarios', function (Request $request) {

    $tutorId = $request->tutor_id;

    return \App\Models\Beneficiario::whereHas('beneficiariostutors', function ($q) use ($tutorId) {
        $q->where('tutor_id', $tutorId)
          ->where('activo', true);
    })
    ->with(['colegioActivo', 'tutorActivo'])
    ->get();
});
Route::middleware('auth:sanctum')->post('/beneficiarios', [BeneficiarioController::class, 'store']);
Route::middleware('auth:sanctum')->get('/colegios',[ColegioController::class, 'index']);
Route::get('/cursos', fn () => \App\Models\Curso::all());

Route::middleware('auth:sanctum')->get('/menus-activos', function () {
    return \App\Models\Menu::where('activo', true)->get();
});

Route::middleware('auth:sanctum')->post('/subscripciones', [SubscripcionController::class, 'store']);
