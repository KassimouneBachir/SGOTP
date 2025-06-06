
@extends('layouts.app')

@section('page_title', 'Statistiques')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Tableau de bord des statistiques</h1>
        <p class="mt-1 text-sm text-gray-600">Vue d'ensemble des activités de la plateforme</p>
    </div>

    <!-- Statistiques globales -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <!-- Carte Objets -->
        <div class="bg-white overflow-hidden shadow-sm rounded-lg">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-blue-500 rounded-md p-3">
                        <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-lg font-medium text-gray-900">Objets totaux</h2>
                        <p class="mt-1 text-3xl font-semibold text-blue-600">{{ $stats['total_objects'] }}</p>
                        <div class="mt-2 flex space-x-4 text-sm">
                            <span class="text-red-600">Perdus: {{ $stats['lost_objects'] }}</span>
                            <span class="text-green-600">Trouvés: {{ $stats['found_objects'] }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Carte Taux de résolution -->
        <div class="bg-white overflow-hidden shadow-sm rounded-lg">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                        <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-lg font-medium text-gray-900">Taux de résolution</h2>
                        <p class="mt-1 text-3xl font-semibold text-green-600">{{ $stats['resolution_rate'] }}%</p>
                        <p class="mt-2 text-sm text-gray-500">Objets rendus: {{ $stats['returned_objects'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Carte Réclamations -->
        <div class="bg-white overflow-hidden shadow-sm rounded-lg">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-yellow-500 rounded-md p-3">
                        <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-lg font-medium text-gray-900">Réclamations</h2>
                        <p class="mt-1 text-3xl font-semibold text-yellow-600">{{ $stats['total_claims'] }}</p>
                        <p class="mt-2 text-sm text-gray-500">En attente: {{ $stats['pending_claims'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Carte Utilisateurs -->
        <div class="bg-white overflow-hidden shadow-sm rounded-lg">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-purple-500 rounded-md p-3">
                        <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-lg font-medium text-gray-900">Utilisateurs</h2>
                        <p class="mt-1 text-3xl font-semibold text-purple-600">{{ $stats['total_users'] }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

       <!-- Graphique d'évolution -->
       <div class="bg-white overflow-hidden shadow-sm rounded-lg mb-8">
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900 mb-4">Évolution mensuelle</h2>
            <div style="height: 400px;"> <!-- Hauteur fixe pour le conteneur -->
                <canvas id="evolutionChart"></canvas>
            </div>
        </div>
    </div>


    <!-- Activité récente et Statistiques détaillées -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Activité récente -->
        <div class="bg-white overflow-hidden shadow-sm rounded-lg">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900 mb-4">Activité récente</h2>
                <div class="space-y-4">
                    @foreach($recentActivity as $activity)
                    <div class="flex items-center justify-between border-b pb-4 last:border-0">
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0 h-10 w-10">
                                <img class="h-10 w-10 rounded-full object-cover" 
                                     src="{{ $activity->photo_url }}" 
                                     alt="{{ $activity->nom }}">
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $activity->nom }}</p>
                                <p class="text-xs text-gray-500">{{ $activity->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $activity->statut === 'perdu' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                            {{ ucfirst($activity->statut) }}
                        </span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Statistiques détaillées -->
        <div class="bg-white overflow-hidden shadow-sm rounded-lg">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900 mb-4">Lieux les plus fréquents</h2>
                <div class="space-y-4">
                    @foreach($stats['most_common_locations'] ?? [] as $location)
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">{{ $location->lieu }}</span>
                        <span class="text-sm font-medium text-gray-900">{{ $location->total }} objets</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('evolutionChart').getContext('2d');
    const monthlyData = @json($monthlyStats);
    
    // Inverser les données pour avoir les mois les plus récents à droite
    const data = monthlyData.reverse();
    
    // Formater les dates pour l'affichage
    const formatDate = (dateStr) => {
        const [year, month] = dateStr.split('-');
        const date = new Date(year, month - 1);
        return date.toLocaleDateString('fr-FR', { month: 'short', year: 'numeric' });
    };

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: data.map(item => formatDate(item.month)),
            datasets: [
                {
                    label: 'Objets perdus',
                    data: data.map(item => item.lost),
                    borderColor: 'rgb(239, 68, 68)',
                    backgroundColor: 'rgba(239, 68, 68, 0.1)',
                    tension: 0.1,
                    fill: true
                },
                {
                    label: 'Objets trouvés',
                    data: data.map(item => item.found),
                    borderColor: 'rgb(34, 197, 94)',
                    backgroundColor: 'rgba(34, 197, 94, 0.1)',
                    tension: 0.1,
                    fill: true
                },
                {
                    label: 'Objets rendus',
                    data: data.map(item => item.returned),
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.1,
                    fill: true
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        padding: 20,
                        font: {
                            size: 12
                        }
                    }
                },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                    padding: 10
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0,
                        font: {
                            size: 11
                        }
                    },
                    grid: {
                        drawBorder: false
                    }
                },
                x: {
                    ticks: {
                        font: {
                            size: 11
                        },
                        maxRotation: 45,
                        minRotation: 45
                    },
                    grid: {
                        drawBorder: false,
                        display: false
                    }
                }
            },
            interaction: {
                intersect: false,
                mode: 'index'
            },
            elements: {
                point: {
                    radius: 3,
                    hoverRadius: 5
                },
                line: {
                    borderWidth: 2
                }
            }
        }
    });
});
</script>
@endpush
@endsection