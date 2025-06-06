<?php
// app/Http/Controllers/Admin/StatisticsController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Objet;
use App\Models\Claim;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class StatisticsController extends Controller
{
    public function dashboard()
    {
        // Statistiques globales
        $stats = [
            'total_objects' => Objet::count(),
            'lost_objects' => Objet::where('statut', 'perdu')->count(),
            'found_objects' => Objet::where('statut', 'trouvé')->count(),
            'returned_objects' => Objet::where('statut', 'rendu')->count(),
            'total_claims' => Claim::count(),
            'pending_claims' => Claim::where('status', 'pending')->count(),
            'total_users' => User::count(),
            'resolution_rate' => $this->calculateResolutionRate(),
        ];

        // Données pour le graphique d'évolution
        $monthlyStats = $this->getMonthlyStats();

        // Statistiques par catégorie
        $categoryStats = $this->getCategoryStats();

        // Activité récente
        $recentActivity = $this->getRecentActivity();

        return view('admin.statistics.dashboard', compact(
            'stats',
            'monthlyStats',
            'categoryStats',
            'recentActivity'
        ));
    }

    public function objectsReport(Request $request)
    {
        $query = Objet::with(['user', 'claims'])
            ->when($request->status, function($q, $status) {
                return $q->where('statut', $status);
            })
            ->when($request->date_from, function($q, $date) {
                return $q->where('created_at', '>=', $date);
            })
            ->when($request->date_to, function($q, $date) {
                return $q->where('created_at', '<=', $date);
            });

        $objects = $query->paginate(15);
        $stats = [
            'total' => $query->count(),
            'resolution_time_avg' => $this->calculateAverageResolutionTime(),
            'most_common_locations' => $this->getMostCommonLocations(),
        ];

        return view('admin.statistics.objects-report', compact('objects', 'stats'));
    }

    public function claimsReport(Request $request)
    {
        $query = Claim::with(['objet', 'user'])
            ->when($request->status, function($q, $status) {
                return $q->where('status', $status);
            });

        $claims = $query->paginate(15);
        $stats = [
            'total' => $query->count(),
            'approval_rate' => $this->calculateApprovalRate(),
            'average_response_time' => $this->calculateAverageResponseTime(),
        ];

        return view('admin.statistics.claims-report', compact('claims', 'stats'));
    }

    public function usersReport(Request $request)
    {
        $users = User::withCount(['objets', 'claims'])
            ->paginate(15);

        $stats = [
            'most_active_users' => $this->getMostActiveUsers(),
            'user_growth' => $this->getUserGrowthStats(),
        ];

        return view('admin.statistics.users-report', compact('users', 'stats'));
    }

    private function calculateResolutionRate()
    {
        $total = Objet::count();
        if ($total === 0) return 0;
        
        $resolved = Objet::where('statut', 'rendu')->count();
        return round(($resolved / $total) * 100, 2);
    }

    private function getMonthlyStats()
    {
        return Objet::select(
            DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
            DB::raw('COUNT(*) as total'),
            DB::raw('SUM(CASE WHEN statut = "perdu" THEN 1 ELSE 0 END) as lost'),
            DB::raw('SUM(CASE WHEN statut = "trouvé" THEN 1 ELSE 0 END) as found'),
            DB::raw('SUM(CASE WHEN statut = "rendu" THEN 1 ELSE 0 END) as returned')
        )
        ->where('created_at', '>=', now()->subMonths(12))
        ->groupBy('month')
        ->orderBy('month', 'ASC')
        ->get();
    }

    private function getCategoryStats()
    {
        // Vous pouvez adapter ceci selon vos catégories
        return Objet::select('statut', DB::raw('COUNT(*) as total'))
            ->groupBy('statut')
            ->get();
    }

    private function getRecentActivity()
    {
        return Objet::with(['user', 'claims'])
            ->latest()
            ->limit(10)
            ->get();
    }

    private function calculateAverageResolutionTime()
    {
        // Logique pour calculer le temps moyen de résolution
        return Objet::whereNotNull('resolved_at')
            ->select(DB::raw('AVG(TIMESTAMPDIFF(DAY, created_at, resolved_at)) as avg_days'))
            ->first()
            ->avg_days ?? 0;
    }

    private function getMostCommonLocations()
    {
        return Objet::select('lieu', DB::raw('COUNT(*) as total'))
            ->groupBy('lieu')
            ->orderByDesc('total')
            ->limit(5)
            ->get();
    }

    private function calculateApprovalRate()
    {
        $total = Claim::count();
        if ($total === 0) return 0;
        
        $approved = Claim::where('status', 'approved')->count();
        return round(($approved / $total) * 100, 2);
    }

    private function getMostActiveUsers()
    {
        return User::withCount(['objets', 'claims'])
            ->orderByDesc('objets_count')
            ->limit(5)
            ->get();
    }
}