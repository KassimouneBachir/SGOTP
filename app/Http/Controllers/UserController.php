<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UsersExport;



class UserController extends Controller
{

    public function export() 
{
    return Excel::download(new UsersExport, 'users.xlsx');
}


/*public function index()
{
    $users = User::latest()->paginate(10);
    return view('admin.users.index', compact('users'));
}*/

public function destroy(User $user)
{
    $user->delete();
    return back()->with('success', 'Utilisateur supprimé');
}



public function index(Request $request)
{
    $users = User::when($request->search, function($query, $search) {
        return $query->where('name', 'like', "%$search%")
                   ->orWhere('email', 'like', "%$search%");
    })
    ->latest()
    ->paginate(10);

    return view('admin.users.index', compact('users'));
}

// app/Http/Controllers/UserController.php
public function changeRole(User $user)
{
    $user->update([
        'role' => $user->role === 'admin' ? 'etudiant' : 'admin'
    ]);
    return back()->with('success', 'Rôle mis à jour');
}

}
