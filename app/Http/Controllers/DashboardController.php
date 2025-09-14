<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use App\Models\Event;
use App\Models\Customer;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (in_array($user->user_type, ['admin', 'staff'])) {
            $metrics = [
                'totalEvents'       => Event::count(),
                'totalCustomers'    => Customer::count(),
                'paymentsThisMonth' => null,
                'pendingTasks'      => null,
                'recentEvents'      => Event::latest('event_date')
                    ->take(5)
                    ->get(['id', 'name', 'event_date', 'status', 'venue']),
            ];

            return view('dashboard', $metrics);
        }


        if ($user->user_type === 'customer') {
            $customer = $user->customer;

            if (!$customer) {
                return view('dashboard', [
                    'totalEvents'  => 0,
                    'upcoming'     => 0,
                    'recentEvents' => collect(),
                ]);
            }

            $recentEvents = Event::where('customer_id', $customer->id)
                ->orderByDesc('event_date')
                ->limit(5)
                ->get(['id', 'name', 'event_date', 'status']);

            return view('dashboard', [
                'totalEvents'  => Event::where('customer_id', $customer->id)->count(),
                'upcoming'     => Event::where('customer_id', $customer->id)
                    ->whereDate('event_date', '>=', Carbon::today())->count(),
                'recentEvents' => $recentEvents,
            ]);
        }

        return view('dashboard');
    }
}
