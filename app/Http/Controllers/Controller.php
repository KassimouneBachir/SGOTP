<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    // Dans app/Http/Controllers/Controller.php ou un middleware
public function shareUnprocessedClaimsCount()
{
    if (auth()->check()) {
        $count = Claim::whereHas('objet', function($query) {
            $query->where('user_id', auth()->id());
        })->where('status', 'pending')->count();
        
        View::share('unprocessedClaimsCount', $count);
    }
}
}
