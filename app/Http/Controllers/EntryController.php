<?php

namespace App\Http\Controllers;

use App\Http\Requests\EntryRequest;
use App\Models\Entry;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class EntryController extends Controller
{
    public function find(){
        $data = Entry::where('user_id', auth()->id())
            ->orderBy('date', 'desc')
            ->paginate();

        return response()->json($data);
    }

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

    public function processCSV(Request $request){
        try {
            $request->validate([
                'csv' =>  ['required', 'mimes:csv,txt']
            ]);

            $handle = fopen($request->file('csv'), "r");
            $has_header = true; //Could be a variable to get from the user

            DB::beginTransaction();

            $total = 0;
            while ($csvLine = fgetcsv($handle, null, ",")) {
                if ($has_header) {
                    $has_header = false;
                } else {
                    $entries[] = Entry::create([
                        'label'   => $csvLine[0],
                        'amount'  => $csvLine[1],
                        'date'    => Carbon::parse($csvLine[2]),
                        'user_id' => $request->user()->id,
                    ]);

                    $total += $csvLine[1];
                }
            }

            DB::commit();

            return response()->json([
                'message' => 'Successfuly processed csv',
                'total'   => $total
            ]);
        }catch (\Exception $e){
            DB::rollback();

            throw $e;
        }
    }
}
