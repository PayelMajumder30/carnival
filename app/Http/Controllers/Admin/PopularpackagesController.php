<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Interfaces\PopularPackagesRepositoryInterface;
use Illuminate\Http\Request;
use App\Models\DestinationWisePopularPackages;
use App\Models\Destination;
use App\Models\ItenaryList;

class PopularpackagesController extends Controller
{

    private $PopularPackagesRepository;

    public function __construct(PopularPackagesRepositoryInterface $PopularPackagesRepository)
    {
        $this->PopularPackagesRepository = $PopularPackagesRepository;
    }

    public function index(Request $request)
    {

        $keyword = $request->keyword ?? '';
        $query = DestinationWisePopularPackages::query();

        $query->when($keyword, function($query) use ($keyword) {
            $query->where(function($q) use ($keyword) {
                $q->whereHas('destination', function ($q1) use ($keyword) {
                    $q1->where('destination_name', 'like', "%$keyword%");
                })
                ->orWhereHas('destination.country', function ($q2) use ($keyword) {
                    $q2->where('country_name', 'like', "%$keyword%");
                });
            });
        });
        $popularPackages = $query->get();
        // dd($popularPackages);
    
        $destinations = Destination::with(['country','popularItineraries.popularitinerary'])
                ->where('status',1)
                ->orderBy('destination_name', 'asc')
                ->get();

        return view('admin.popularpackages.index', compact('destinations','popularPackages','keyword'));
    }

    public function fetchItineraries($destinationId)
    {
        $existing_itinerary = DestinationWisePopularPackages::where('destination_id',$destinationId)->pluck('itinerary_id')->toArray();
        $itineraries = ItenaryList::where('status',1)->whereNotIn('id',$existing_itinerary)->get();

        return response()->json([
            'itineraries' => $itineraries,
        ]);
    }

    public function storeAssign(Request $request)
    {
        $request->validate([
            'destination_id' => 'required|exists:destinations,id',
            'itinerary_ids' => 'required|array',
            'itinerary_ids.*' => 'exists:itenary_list,id',
        ]);

        foreach ($request->itinerary_ids as $itineraryId) {
            DestinationWisePopularPackages::firstOrCreate([
                'destination_id' => $request->destination_id,
                'itinerary_id' => $itineraryId,
            ], [
                'status' => 1,
            ]);
        }

        return redirect()->back()->with('success', 'Itineraries assigned successfully!');
    }

    public function updateStatus(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:destination_wise_popular_packages,id',
            'status' => 'required|boolean',
        ]);

        $pckg = DestinationWisePopularPackages::find($request->id);
        $pckg->status = $request->status;
        $pckg->save();

        return response()->json(['success' => true]);
    }

    public function delete(Request $request)
    {
        $pckg = DestinationWisePopularPackages::find($request->id); 

        if (!$pckg) {
            return response()->json([
                'status'    => 404,
                'message'   => 'Itinerarywise package not found.',
            ]);
        }

        // Store related values before deletion
        $itineraryId    = $pckg->itinerary_id;
        $destinationId  = $pckg->destination_id;

        $pckg->delete();

        return response()->json([
            'status'             => 200,
            'message'            => 'Itinerarywise package deleted successfully.',
            'destination_id'     => $destinationId,
        ]);
    }

}
