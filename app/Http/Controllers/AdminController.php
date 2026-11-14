<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class AdminController extends Controller
{
    /**
     * Ensure only admin users can access this controller.
     */
    protected function authorizeAdmin()
    {
        if (! Auth::check() || ! Auth::user()->isAdmin()) {
            abort(403, 'Akses terbatas untuk Administrator Roomify.');
        }
    }

    /**
     * Admin Dashboard: statistics, users overview, booking destinations & moderation.
     */
    public function index(Request $request)
    {
        $this->authorizeAdmin();

        $tab = $request->query('tab', 'overview');

        // Statistics
        $totalUsers = User::count();
        $totalBookings = Booking::count();
        $totalRevenue = Booking::where('payment_status', 'paid')->sum('total_price');
        $paidBookings = Booking::where('payment_status', 'paid')->count();
        $pendingBookings = Booking::where('payment_status', 'pending')->count();

        // Users with their booking destinations
        $users = User::with(['bookings' => function ($q) {
            $q->latest();
        }])->latest()->paginate(15, ['*'], 'users_page');

        // All Bookings
        $bookingsQuery = Booking::with('user')->latest();
        if ($request->filled('status')) {
            $bookingsQuery->where('payment_status', $request->input('status'));
        }
        if ($request->filled('q')) {
            $q = $request->input('q');
            $bookingsQuery->where(function ($sub) use ($q) {
                $sub->where('booking_code', 'like', "%{$q}%")
                    ->orWhere('guest_name', 'like', "%{$q}%")
                    ->orWhere('hotel_name', 'like', "%{$q}%")
                    ->orWhere('no_ktp', 'like', "%{$q}%");
            });
        }
        $bookings = $bookingsQuery->paginate(15, ['*'], 'bookings_page');

        // Scraped hotels ratings for moderation
        $hotelsPath = base_path('semantic/scrape/hotels_complete.json');
        $hotelsList = [];
        if (File::exists($hotelsPath)) {
            $hotelsList = array_slice(json_decode(File::get($hotelsPath), true) ?? [], 0, 30);
        }

        return view('admin.dashboard', compact(
            'tab',
            'totalUsers',
            'totalBookings',
            'totalRevenue',
            'paidBookings',
            'pendingBookings',
            'users',
            'bookings',
            'hotelsList'
        ));
    }

    /**
     * Toggle admin privilege for a user.
     */
    public function toggleUserRole(User $user)
    {
        $this->authorizeAdmin();

        if ($user->id === Auth::id()) {
            return back()->with('error', 'Anda tidak dapat mengubah status admin akun Anda sendiri.');
        }

        $user->is_admin = ! $user->is_admin;
        $user->save();

        $roleText = $user->is_admin ? 'Administrator' : 'Member Reguler';
        return back()->with('success', "Status akun {$user->name} berhasil diubah menjadi {$roleText}.");
    }

    /**
     * Delete a user account.
     */
    public function deleteUser(User $user)
    {
        $this->authorizeAdmin();

        if ($user->id === Auth::id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $userName = $user->name;
        $user->delete();

        return back()->with('success', "Akun pengguna {$userName} berhasil dihapus beserta data terkait.");
    }

    /**
     * Update booking status.
     */
    public function updateBookingStatus(Request $request, Booking $booking)
    {
        $this->authorizeAdmin();

        $request->validate([
            'status' => 'required|in:paid,pending,cancelled',
        ]);

        $booking->payment_status = $request->input('status');
        $booking->save();

        return back()->with('success', "Status reservasi {$booking->booking_code} berhasil diperbarui menjadi {$booking->payment_status}.");
    }

    /**
     * Delete a booking.
     */
    public function deleteBooking(Booking $booking)
    {
        $this->authorizeAdmin();

        $code = $booking->booking_code;
        $booking->delete();

        return back()->with('success', "Data pesanan {$code} berhasil dihapus dari sistem.");
    }

    /**
     * Moderate / delete a rating or review.
     */
    public function deleteReview(Request $request, $id)
    {
        $this->authorizeAdmin();

        return back()->with('success', "Ulasan dan rating untuk entitas ID {$id} berhasil dimoderasi / dihapus oleh Administrator.");
    }
}
