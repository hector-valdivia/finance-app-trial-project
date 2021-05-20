<?php

namespace App\Http\Controllers;

use App\Models\Entry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index(Request $request){
        $user = Auth::user();

        return Inertia::render('Dashboard', [
            'paginated' => Entry::where('user_id', $user->id)->orderBy('date', 'desc')->paginate(),
            'total'     => Entry::select('amount')->where('user_id', $user->id)->sum('amount')
        ]);
    }
}
