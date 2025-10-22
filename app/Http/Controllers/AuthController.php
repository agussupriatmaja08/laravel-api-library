<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http; // Tambahkan ini
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            if (!Auth::attempt($request->only('email', 'password'))) {
                throw ValidationException::withMessages([
                    'email' => ['Kredensial tidak valid.'],
                ]);
            }
            $client = [
                'client_id' => env('PASSPORT_PASSWORD_CLIENT_ID'),
                'client_secret' => env('PASSPORT_PASSWORD_CLIENT_SECRET'),
                // 'client_id' => '0199e06d-bdd2-71b4-9565-d12c051ec4e7',
                // 'client_secret' => '4nidsqES6LgYZDwNx6zJq0HxsJ9LbdNS4bcoDQyA',
            ];

            $response = Http::asForm()->post(config('app.url') . '/oauth/token', [
                'grant_type' => 'password',
                'client_id' => $client['client_id'],
                'client_secret' => $client['client_secret'],
                'username' => $request->email,
                'password' => $request->password,
                'scope' => '*',
            ]);

            if ($response->failed()) {
                // TANGKAP BODY RESPONS MENTAH (RAW BODY)
                $rawError = $response->body();

                return response()->json([
                    'message' => 'Gagal mendapatkan token dari Passport. (Cek error raw)',
                    'error_raw_body' => $rawError, // Menampilkan body error mentah
                    'error_json' => $response->json() // Tetap menampilkan json (yang nilainya null)
                ], 500);
            }

            $user = Auth::user();

            return response()->json([
                'user' => $user,
                'token_data' => $response->json(),
                'token_type' => 'Bearer',
            ], 200);
        } catch (ValidationException $e) {
            return response()->json(['message' => 'Gagal login', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan server', 'error' => $e->getMessage()], 500);
        }
    }
}
