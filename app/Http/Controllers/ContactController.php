<?php

namespace App\Http\Controllers;

use App\Models\GithubProfile;
use App\Mail\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function show($username)
    {
        $profileInfo = GithubProfile::where('username', $username)->firstOrFail();

        return view('contact', compact('username', 'profileInfo'));
    }

    public function send(Request $request, $username)
    {
        $validated = $request->validate([
            'name'    => ['required', 'string', 'max:255'],
            'email'   => ['required', 'email', 'max:255'],
            'message' => ['required', 'string', 'min:10'],
        ]);

        $profileInfo = GithubProfile::where('username', $username)->firstOrFail();

        $destinationEmail = $profileInfo->email;

        if (!$destinationEmail) {
            return back()->withErrors([
                'error' => 'Este usuario no tiene configurado un correo electrónico público en su perfil de GitHub.'
            ])->withInput();
        }

        Mail::to($destinationEmail)->send(new ContactMessage($validated));

        return back()->with('success', '¡Tu mensaje ha sido enviado con éxito al desarrollador!');
    }
}
