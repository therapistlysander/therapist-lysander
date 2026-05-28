<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AdminForgotPasswordController extends Controller
{
    public function showForm()
    {
        return view('admin.auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $request->email)->where('is_admin', true)->first();

        if (!$user) {
            return back()->with('success', 'If the email exists in our system, a reset link has been sent.');
        }

        // Generate token
        $token = Str::random(64);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $user->email],
            ['token' => Hash::make($token), 'created_at' => now()]
        );

        // Send reset email
        $resetUrl = route('admin.password.reset', ['token' => $token, 'email' => $user->email]);

        Mail::raw("You requested a password reset for your admin account.\n\nClick the link below to reset your password:\n{$resetUrl}\n\nThis link will expire in 60 minutes.\n\nIf you didn't request this, please ignore this email.", function ($message) use ($user) {
            $message->to($user->email)
                    ->subject('Password Reset - Therapist Lysander CMS');
        });

        return back()->with('success', 'If the email exists in our system, a reset link has been sent.');
    }

    public function showResetForm(Request $request)
    {
        return view('admin.auth.reset-password', [
            'token' => $request->query('token'),
            'email' => $request->query('email'),
        ]);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $record = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$record || !Hash::check($request->token, $record->token)) {
            return back()->withErrors(['email' => 'Invalid or expired reset token.']);
        }

        // Check if token is expired (60 minutes)
        if (now()->diffInMinutes($record->created_at) > 60) {
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();
            return back()->withErrors(['email' => 'This reset link has expired. Please request a new one.']);
        }

        // Update password
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return back()->withErrors(['email' => 'User not found.']);
        }

        $user->update(['password' => Hash::make($request->password)]);

        // Delete token
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect()->route('admin.login')
            ->with('success', 'Password reset successfully. You can now log in.');
    }
}
