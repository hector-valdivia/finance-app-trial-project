<?php

namespace App\Http\Controllers;

use App\Http\Requests\EntryRequest;
use App\Models\Entry;
use Carbon\Carbon;
use Illuminate\Http\Request;

class EntryController extends Controller
{
    public function store(EntryRequest $request, Entry $entry){
        $entry->fill($request->validated());
        $entry->date    = Carbon::parse($request->date);
        $entry->user_id = $request->user()->id;
        $entry->save();

        return response()->json([
            'message' => 'Successfuly saved',
            'id' => $entry->id
        ]);
    }

    public function update(EntryRequest $request, Entry $entry){
        $entry->fill($request->validated());
        $entry->date = Carbon::parse($request->date);
        $entry->save();

        return response()->json([
            'message' => 'Successfuly saved'
        ]);
    }

    public function destroy(Entry $entry){
        $entry->delete();

        return response()->json([
            'message' => 'Successfuly deleted'
        ]);
    }

    public function totalBalance(Request $request){
        return Entry::select('amount')
            ->where('user_id', $request->user()->id)
            ->sum('amount');
    }
}
