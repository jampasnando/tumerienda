<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Tutor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        // dd($request->all());
        // $request->validate([
        //     'name' => 'required',
        //     'email' => 'required|email|unique:tutors',
        //     'password' => 'required|min:6',
        // ]);
        // dd($request->all());
        // Log::info('Datos recibidos para registro: ', $request->all());
        $tutor = Tutor::create([
            'nombre' => $request->nombre,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'ci' => $request->ci,
            'direccion' => $request->direccion,
            'telefono' => $request->telefono,
            'celular' => $request->celular,
            'genero' => '',
            'comentarios' => '',
        ]);
        // dd($tutor);
        $token = $tutor->createToken('app')->plainTextToken;

        return response()->json([
            'tutor' => $tutor,
            'token' => $token
        ]);
    }

    public function login(Request $request)
    {
        // return 'login';
        Log::info('Datos recibidos para login: ', $request->all());
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $tutor = Tutor::where('email', $request->email)->first();

        if (! $tutor || ! Hash::check($request->password, $tutor->password)) {
            return response()->json(['message' => 'Credenciales inválidas'], 401);
        }

        $token = $tutor->createToken('api-token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'tutor' => $tutor,
        ]);
    }
}
