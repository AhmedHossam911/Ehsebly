<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PublicProfileController extends Controller
{
    public function show(User $user)
    {
        // View someone's public profile
        return view('profile.show', compact('user'));
    }
}
