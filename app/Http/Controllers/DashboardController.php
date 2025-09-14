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
                'totalEvents'     => Event::count(),
                'totalCustomers'  => Customer::count(),
                'paymentsThisMonth' => null,
                'pendingTasks'      => null,
            ];

            return view('dashboard', $metrics);
        }

        if ($user->user_type === 'customer') {
            $customerId = optional($user->customer)->id;

            $metrics = [
                'totalEvents' => Event::where('customer_id', $customerId)->count(),
                'upcoming'    => Event::where('customer_id', $customerId)
                    ->whereDate('event_date', '>=', Carbon::today())
                    ->count(),
            ];

            return view('dashboard', $metrics);
        }


        return view('dashboard');
    }
}
