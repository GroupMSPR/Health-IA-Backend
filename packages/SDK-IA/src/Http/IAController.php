<?php

namespace MSPR2\SdkIA\Http;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MSPR2\SdkIA\Facade\IAManager;

class IAController extends Controller
{
    public function analyzeMeal(Request $request)
    {
        $request->validate([
            'image' => 'required|image'
        ]);
        $imageBytes = file_get_contents($request->file('image')->getPathname());
        $imageBase64 = base64_encode($imageBytes);

        $result = IAManager::analyzeMeal($imageBase64);

        return response()->json($result);
    }

    public function recommendWorkout(Request $request) {
        $user = $request->user()->load([
            'goals',
            'constraints',
            'equipments',
            'healthMetrics'
        ]);

        $userProfile = [
            'user_id' => $user->getKey(),
            'goals' => $user->goals->pluck('name'),
            'equipments' => $user->equipments->pluck('name'),
            'physical_activity_level' => $user->physical_activity_level,
            'daily_activity_level' => $user->daily_activity_level,
        ];

        $result = IAManager::recommendWorkout($userProfile);
        return response()->json($result);
    }
}
