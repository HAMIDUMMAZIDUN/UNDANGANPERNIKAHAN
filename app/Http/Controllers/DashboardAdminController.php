<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\User; 

class DashboardAdminController extends Controller
{
    /**
     * Menampilkan halaman dashboard admin.
     *
     * @return View
     */
    public function index(): View
    {

        $data = [
            'serverUptime' => '35,3%',
            'totalUsers' => User::where('role', 'user')->count(),
            'booking' => '+1,234',
            'totalRequest' => '1,234',
            'requestFromUsers' => '123',
            'activitiesUsers' => '342',
            'activitiesPercentage' => 65,
            'activities' => [
                [
                    'user' => 'Silvia agustin',
                    'action' => 'Login App',
                    'time' => '0 12:00 AM',
                    'details' => null,
                ],
                [
                    'user' => 'Silvia agustin',
                    'action' => 'Add Picture in gal..',
                    'time' => '0 12:05 AM',
                    'details' => [
                        'text' => 'Detail:',
                        'images' => [
                            'https://placehold.co/100x100/d1d5db/374151?text=Img1',
                            'https://placehold.co/100x100/d1d5db/374151?text=Img2',
                        ]
                    ],
                ],
                 [
                    'user' => 'Silvia agustin',
                    'action' => 'Login App',
                    'time' => '0 12:07 AM',
                    'details' => null,
                ],
            ]
        ];

        return view('admin.dashboardadmin.index', $data);
    }
}
