<?php
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\BeneficiarioController;
use App\Http\Controllers\ColegioController;
use App\Http\Controllers\OfertaController;
use App\Http\Controllers\PackController;
use App\Http\Controllers\SuscripcionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
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

// Route::middleware('auth:sanctum')->post('/subscripciones', [SubscripcionController::class, 'store']);
Route::middleware('auth:sanctum')->get('/ofertas/activas', [OfertaController::class, 'activas']);
Route::middleware('auth:sanctum')->group(function () {

    Route::post('/suscripciones', [SuscripcionController::class, 'store']);
    Route::get('/suscripciones', [SuscripcionController::class, 'index']);

});
// Route::middleware('auth:sanctum')->get('/packs/abiertos', [PackController::class, 'abiertos']);
Route::get('/packs/abiertos', [PackController::class, 'abiertos']);
