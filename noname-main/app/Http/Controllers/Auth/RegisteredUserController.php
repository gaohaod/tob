<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use App\Models\Bodycolors;
use Illuminate\Support\Str;
use App\Models\Invites;
use App\Models\Owned;
use Illuminate\Http\RedirectResponse;
use App\Http\Controllers\RenderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

use App\Jobs\ThumbnailJob;
use Carbon\Carbon;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        if (env('INVITES_DISABLED', false)) {
        $request->validate([
            'name' => ['required', 'string', 'max:20', 'unique:'.User::class, 'regex:/^[a-zA-Z0-9]+$/',], // for some reason laravel didn't make the name unique by default
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'invite_key' => ['required', 'string'],
            'gender' => ['required', 'in:m,f'],
        ]);

    } else {
        $request->validate([
            'name' => ['required', 'string', 'max:20', 'unique:'.User::class, 'regex:/^[a-zA-Z0-9]+$/',], // for some reason laravel didn't make the name unique by default
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'gender' => ['required', 'in:m,f'],
        ]);
    }

        if (env('INVITES_DISABLED', false)) {

            $invitationKey = Invites::where('key', $request->input('invite_key'))
                ->where('used', false)
                ->first();

            if (!$invitationKey) {
                return back()->withErrors(['invite_key' => 'Invalid or already used invite key.']);
            }

        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'authentication_ticket' => Str::uuid(),
            'gender' => $request->gender,
            'last_seen' => Carbon::now(),
        ]);

        if (env('INVITES_DISABLED', false)) {
        $invitationKey->update(['used' => true]);
}

        event(new Registered($user));

        Auth::login($user);
        
        $defaultBodyColors = [
            'userId' => Auth::id(),
            'head' => 1,
            'torso' => 37,
            'larm' => 1,
            'rarm' => 1,
            'lleg' => 42,
            'rleg' => 42,
        ];
        

        Bodycolors::create($defaultBodyColors);

        ThumbnailJob::dispatch('user', Auth::id());

        //create the roblosecurity
        Auth::user()->authentication_ticket = Str::uuid();
        Auth::user()->save();

        return redirect(route('app.home', absolute: false))->with('success', 'Successfully signed up. Welcome to NONAME!');
    }
}
