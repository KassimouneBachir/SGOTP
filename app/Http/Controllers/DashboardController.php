<?php

namespace App\Http\Controllers;

use App\Models\Objet;
use App\Models\User;
use App\Models\Claim;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // Récupérer les derniers objets ajoutés (limité à 6)
        $recentObjects = Objet::with('user')
            ->latest()
            ->take(6)
            ->get()
            ->groupBy('statut');

        // Récupérer les réclamations en attente pour l'utilisateur
        $pendingClaims = Claim::with(['objet', 'user'])
            ->whereHas('objet', function($query) {
                $query->where('user_id', auth()->id());
            })
            ->where('status', 'pending')
            ->latest()
            ->get();

        // Récupérer les objets de l'utilisateur
        $userObjects = Objet::where('user_id', auth()->id())
            ->with(['claims' => function($query) {
                $query->where('status', 'pending');
            }])
            ->latest()
            ->get();

        // Récupérer les notifications non lues
        $unreadNotifications = auth()->user()->unreadNotifications()->get();

        // Récupérer toutes les notifications
        $notifications = auth()->user()->notifications()->latest()->take(5)->get();

        return view('dashboard', compact(
            'recentObjects',
            'pendingClaims',
            'userObjects',
            'unreadNotifications',
            'notifications'
        ));
    }

    protected function getDashboardStats()
    {
        return [
            'perdus' => Objet::where('statut', 'perdu')->whereDate('created_at', today())->count(),
            'trouves' => Objet::where('statut', 'trouvé')->whereDate('created_at', today())->count(),
            'rendus' => Objet::where('statut', 'rendu')->whereDate('created_at', today())->count(),
            'total_objects' => Objet::count(),
            'total_users' => User::count()
        ];
    }

    protected function getRecentObjects()
    {
        return Objet::with(['user' => function($query) {
                $query->select('id', 'name');
            }])
            ->select('id', 'nom', 'statut', 'photo_url', 'user_id', 'created_at')
            ->latest()
            ->take(5)
            ->get();
    }

    protected function getUnprocessedClaimsCount()
    {
        if (!auth()->check()) return 0;

        return Claim::where('status', 'pending')
            ->whereHas('objet', function($query) {
                $query->where('user_id', auth()->id());
            })
            ->count();
    }

    protected function getPhotoUrl($photoPath)
    {
        if (!$photoPath) return null;

        // Vérifie si le fichier existe dans le stockage
        if (Storage::disk('public')->exists($photoPath)) {
            return Storage::url($photoPath);
        }

        return null;
    }
}