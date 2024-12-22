<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\BookingResource;
use App\Models\Api\Booking;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    use ApiResponseTrait;

    public function index()
    {
        return $this->success(
            BookingResource::collection(auth()->user()->bookings)
        );
    }

    public function show($id)
    {
        $booking = Booking::findOrFail($id);

        return $this->success(
            new BookingResource($booking)
        );
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'training_class_id' => 'required|exists:training_classes,id',
        ]);

        if (!auth()->user()->subscription) {
            return $this->error("", "User must have a subscription!", 403);
        }

        $alreadySubscribed =
            Booking::where('user_id', auth()->id())
            ->where('training_class_id', $request->training_class_id)
            ->where('status', 'completed')
            ->get();

        if (count($alreadySubscribed) > 0) {
            return $this->error("", "User already subscribed to this class!", 403);
        }

        $validatedData['user_id'] = auth()->id();
        $validatedData['booking_date'] = now();
        $validatedData['status'] = 'completed';

        $booking = Booking::create($validatedData);

        return $this->success(
            new BookingResource($booking),
            'Created Successfully!',
            201
        );
    }

    public function update(Request $request, $id)
    {
        $booking = auth()->user()->bookings()->findOrFail($id);

        $booking->update([
            'status' => 'canceled'
        ]);

        return $this->success(
            new BookingResource($booking),
            "Cancelled successfully!"
        );
    }
}
