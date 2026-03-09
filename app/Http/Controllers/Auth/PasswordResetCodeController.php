<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;
use App\Models\User;

class PasswordResetCodeController extends Controller
{
    public function showVerifyForm(Request $request)
    {
        if (!$request->has('email')) {
            return redirect()->route('password.request');
        }
        return view('auth.passwords.code');
    }

    public function verifyCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'code' => 'required|string|size:6',
        ]);

        $record = DB::table('password_reset_tokens')
                    ->where('email', $request->email)
                    ->first();

        if (!$record || empty($record->created_at)) {
            return back()->with('error', 'The verification code is invalid or has expired.');
        }

        $expiresAt = Carbon::parse($record->created_at)->addMinutes(config('auth.passwords.users.expire', 60));
        if (now()->greaterThan($expiresAt)) {
            return back()->with('error', 'The verification code is invalid or has expired.');
        }

        if (!Hash::check($request->code, $record->token)) {
            return back()->with('error', 'The verification code is invalid or has expired.');
        }

        // Redirect to the standard reset form with the code as the token
        return redirect()->route('password.reset', [
            'token' => $request->code,
            'email' => $request->email
        ]);
    }
}
