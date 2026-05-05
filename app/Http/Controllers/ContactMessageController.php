<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\Request;

class ContactMessageController extends Controller
{
    public function index()
    {
        return ContactMessage::all();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => ['required', 'exists:users'],
            'name' => ['required'],
            'company' => ['required'],
            'email' => ['required', 'email', 'max:254'],
            'message' => ['required'],
            'status' => ['required'],
        ]);

        return ContactMessage::create($data);
    }

    public function show(ContactMessage $contactMessage)
    {
        return $contactMessage;
    }

    public function update(Request $request, ContactMessage $contactMessage)
    {
        $data = $request->validate([
            'user_id' => ['required', 'exists:users'],
            'name' => ['required'],
            'company' => ['required'],
            'email' => ['required', 'email', 'max:254'],
            'message' => ['required'],
            'status' => ['required'],
        ]);

        $contactMessage->update($data);

        return $contactMessage;
    }

    public function destroy(ContactMessage $contactMessage)
    {
        $contactMessage->delete();

        return response()->json();
    }
}
