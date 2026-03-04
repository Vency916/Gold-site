<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\SentEmail;
use App\Mail\CustomUserEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class AdminEmailController extends Controller
{
    public function index()
    {
        $users = User::all();
        $history = SentEmail::with('user')->latest()->paginate(15);
        
        return view('admin.emails.index', compact('users', 'history'));
    }

    public function send(Request $request)
    {
        $request->validate([
            'recipient_type' => 'required|in:all,specific',
            'user_ids' => 'required_if:recipient_type,specific|array',
            'user_ids.*' => 'exists:users,id',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $subject = $request->subject;
        $messageContent = $request->message;

        if ($request->recipient_type === 'all') {
            $recipients = User::all();
        } else {
            $recipients = User::whereIn('id', $request->user_ids)->get();
        }

        foreach ($recipients as $user) {
            Mail::to($user->email)->queue(new CustomUserEmail($subject, $messageContent));

            SentEmail::create([
                'user_id' => $user->id,
                'recipient_email' => $user->email,
                'subject' => $subject,
                'message' => $messageContent,
            ]);
        }

        return redirect()->route('admin.emails.index')->with('success', 'Email(s) queued for delivery.');
    }
}
