<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth; // Anda mungkin tidak butuh ini untuk admin
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Booking; 
use App\Models\Request as UserRequest; 
use Illuminate\Support\Facades\Cache;

class DashboardAdminController extends Controller
{
    /**
     * Menampilkan halaman dashboard utama untuk Admin.
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        try {
        
            $isOpenForOrder = Cache::get('open_for_order', true);

            if ($isOpenForOrder) {
                $serverStatus = 'Online';
                $serverStatusColor = 'teal'; 
            } else {
                $serverStatus = 'Maintenance';
                $serverStatusColor = 'red';
            }
            $totalUsers = User::count();
            $booking = 0;
            $totalRequest = 0; 
            $requestFromUsers = 0;   
            $activitiesUsers = User::where('updated_at', '>', now()->subDay())->count();
            $activitiesPercentage = ($totalUsers > 0) ? round(($activitiesUsers / $totalUsers) * 100) : 0;

            // 3. Ambil data aktivitas (contoh data dummy)
            $activities = [
                ['user' => 'John Doe', 'action' => 'updated his profile.', 'time' => '5 minutes ago'],
                ['user' => 'Jane Smith', 'action' => 'created a new event.', 'time' => '1 hour ago'],
                ['user' => 'Admin', 'action' => 'changed server status to Online.', 'time' => '3 hours ago'],
            ];

            // 4. Kirim semua data yang dibutuhkan ke view
            return view('admin.dashboardadmin.index', [
                'serverStatus' => $serverStatus,
                'serverStatusColor' => $serverStatusColor,
                'totalUsers' => $totalUsers,
                'booking' => $booking,
                'totalRequest' => $totalRequest,
                'requestFromUsers' => $requestFromUsers,
                'activitiesUsers' => $activitiesUsers,
                'activitiesPercentage' => $activitiesPercentage,
                'activities' => $activities,
            ]);

        } catch (\Exception $e) {
    Log::error('Admin Dashboard error: ' . $e->getMessage());
    
    // Gunakan helper abort() untuk menampilkan halaman error default Laravel
    abort(500, 'Terjadi kesalahan pada sistem dashboard.');
}
    }
}