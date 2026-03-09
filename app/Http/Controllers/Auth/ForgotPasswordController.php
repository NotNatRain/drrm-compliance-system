<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    protected function validateEmail(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'ends_with:gmail.com'],
        ], [
            'email.ends_with' => 'Password reset verification is only available for registered Gmail accounts.',
        ]);
    }

    /**
     * Get the response for a successful password reset link.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $response
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function sendResetLinkResponse(\Illuminate\Http\Request $request, $response)
    {
        return redirect()->route('password.verify-form', ['email' => $request->email])
                         ->with('status', 'We have emailed your 6-digit verification code.');
    }
}
