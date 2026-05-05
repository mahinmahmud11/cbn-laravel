<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\ShipmentPod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PodController extends Controller
{
    /**
     * Serve a protected POD image.
     */
    public function show(string $filename): StreamedResponse
    {
        $pod = ShipmentPod::where('file_path', 'pods/' . $filename)->firstOrFail();
        
        // Authorization Logic
        $user = auth()->user();
        if (!$user) {
            abort(403, 'Unauthorized access.');
        }

        $shipStatus = $pod->shipStatus;
        $shipItem = $shipStatus->shipItem;

        // Super Admin can see all
        if ($user->hasRole('super_admin')) {
            // Authorized
        } else {
            // Agency users can only see their own agency shipments
            // Relation: shipItem -> user -> agency
            if ($shipItem->user && $shipItem->user->store_area === $user->store_area) {
                // Authorized
            } else {
                abort(403, 'You are not authorized to view this proof of delivery.');
            }
        }
        
        if (!Storage::disk('local')->exists($pod->file_path)) {
            abort(404, 'File not found on storage.');
        }

        return Storage::disk('local')->response($pod->file_path);
    }
}
