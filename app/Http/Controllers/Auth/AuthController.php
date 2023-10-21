<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Mail\VerificationCodeMail;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function loginIndex()
    {
        return view('auth.login');
    }

    public function registerIndex()
    {
        return view('auth.register');
    }

    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => ['required', 'email'],
                'password' => ['required', 'string'],
            ]);
            $user = User::whereEmail($request->email)->first();
            if ($user) {
                if (Auth::attempt(['email' => $request->email, 'password' => $request->password], true)) {
                    $user->remember_token = Str::random(15);
                    $user->save();
                    $route = $user->type == 1 ? 'dashboard' : 'shopping.pay';
                    return redirect()->route($route);
                } else {
                    return back()->with('error', 'Credenciales incorrectas');
                }
            } else {
                return back()->with('error', 'Usuario no encontrado');
            }
        } catch (Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    public function verifyCode(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'verification_code' => 'required|string|min:6|max:6',
            ]);

            // Busca al usuario por su dirección de correo electrónico y el código de verificación
            $user = User::where('email', $request->email)
                ->where('verification_code', $request->verification_code)
                ->first();

            if (!$user) {
                return redirect()->route('verification.index')->with('error', 'Código de verificación incorrecto.')->withInput();
            }

            // Marcar la cuenta como verificada
            $user->email_verified_at = now();
            $user->verification_code = null; // Opcional: Eliminar el código de verificación después de la verificación
            $user->save();

            // Redirigir al usuario a la vista de inicio de sesión con un mensaje de éxito
            return redirect()->route('login.index')->with('success', 'Cuenta verificada con éxito. Ahora puedes iniciar sesión.');

        } catch (Exception $exception) {
            return redirect()->route('verification.index')->with('error', $exception->getMessage())->withInput();
        }
    }


    public function register(Request $request)
    {
        try {
            $validate = $request->validate([
                'name' => 'required|string',
                'email' => 'required|email|unique:users,email',
                'password' => 'required',
                'password_confirmation' => 'required|same:password',
                'address' => 'required|string',
                'phone' => 'required',
                'type' => 'required'
            ]);
            $validate['password'] = Hash::make($request->password);
            $user = User::create($validate);

            // Generar y enviar el código de verificación
            $verificationCode = Str::random(6);
            $user->verification_code = $verificationCode;
            $user->save();
            Mail::to($user->email)->send(new VerificationCodeMail($verificationCode));

            if ($user) {
                // Redirige al usuario a la vista de verificación
                return redirect()->route('verification.index')->with('email', $user->email)->with('success', 'Cuenta creada, verifica tu correo electrónico para continuar.');
            } else {
                return back()->with('error', 'No se pudo crear la cuenta');
            }
        } catch (Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    public function logout()
    {
        if (auth()->user()->type == 2) {
            $route = route('shopping.cart');
        } else {
            $route = route('login.index');
        }
        Auth::logout();
        return redirect($route);
    }
}
