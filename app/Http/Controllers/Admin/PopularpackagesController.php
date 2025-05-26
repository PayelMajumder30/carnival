<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Interfaces\PopularPackagesRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\DestinationWisePopularPackages;
use App\Models\Destination;
use App\Models\ItenaryList;
use App\Models\TagList;
use App\Models\DestinationWisePopularPackageTag;


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

        $tags = TagList::where('status',1)->get();

        return view('admin.popularpackages.index', compact('destinations','popularPackages','keyword','tags'));
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

        return redirect()->back()->with('success', 'Packages and tags assigned successfully!');
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

        
        $itineraryId    = $pckg->itinerary_id;
        $destinationId  = $pckg->destination_id;

        $pckg->delete();

        return response()->json([
            'status'             => 200,
            'message'            => 'Package deleted successfully.',
            'destination_id'     => $destinationId,
        ]);
    }

    public function assignTags(Request $request)
    {
        $request->validate([
            'popular_package_id' => 'required|exists:destination_wise_popular_packages,id',
            'tag_ids' => 'required|array|min:1',
            'tag_ids.*' => 'exists:tag_list,id',
        ]);

        $packageId = $request->popular_package_id;
        $existingTagIds = DestinationWisePopularPackageTag::where('popular_package_id', $packageId)
                            ->pluck('tag_id')
                            ->toArray();

        foreach ($request->tag_ids as $tagId) {
            if (!in_array($tagId, $existingTagIds)) {
                DestinationWisePopularPackageTag::create([
                    'popular_package_id' => $packageId,
                    'tag_id' => $tagId,
                ]);
            }
        }

        return redirect()->back()->with('success', 'Tags assigned successfully to the package.');
    }


}
