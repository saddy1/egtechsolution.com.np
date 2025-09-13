<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;
use App\Models\User;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\DB;


class UserController extends Controller
{
    // Show the form to request a password reset link
    public function showLinkRequestForm()
    {
        return view('frontend.customer.reset_password');
    }

    // Send the password reset link to the user's email
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        // Check if the email exists in the users table
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'The provided email does not exist.']);
        }

        // Create and send the password reset notification
        $token = Str::random(60);
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            ['token' => $token, 'created_at' => now()]
        );

        // Send the notification
        Notification::route('mail', $request->email)
                    ->notify(new ResetPasswordNotification($token));

        return back()->with('status', 'We have emailed your password reset link!');
    }

    // Show the form to reset the password
    public function showResetForm($token)
{
    // Retrieve the reset token record from the database
    $resetToken = DB::table('password_reset_tokens')
                    ->where('token', $token)
                    ->first();

    // Check if the token exists and is valid
    if (!$resetToken) {
        return redirect()->route('password.request')->withErrors(['token' => 'Invalid or expired reset token.']);
    }

    // Retrieve the associated email address
    $email = $resetToken->email;

    // Return the view with the token and email address
    return view('frontend.customer.create_password', [
        'token' => $token,
        'email' => $email,
    ]);
}


    // Handle the password reset
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
            'token' => 'required'
        ]);

        // Check if the email exists in the users table
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'The provided email does not exist.']);
        }

        // Check if the reset token is valid
        $resetToken = DB::table('password_reset_tokens')
                        ->where('email', $request->email)
                        ->where('token', $request->token)
                        ->first();

        if (!$resetToken) {
            return back()->withErrors(['token' => 'Invalid or expired reset token.']);
        }

        // Update the user's password
        $user->password = Hash::make($request->password);
        $user->setRememberToken(Str::random(60));
        $user->save();

        // Remove the reset token from the database after successful password reset
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        // Trigger the password reset event (optional)
        event(new PasswordReset($user));

        // Redirect the user to the login page with a success message
        return redirect()->route('login')->with('status', 'Password has been reset successfully.');
    }
}
