<?php


// app/Http/Controllers/ClaimController.php

namespace App\Http\Controllers;

use App\Models\Claim;
use App\Models\Objet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ClaimController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request, Objet $objet)
    {
        // Vérifier si l'utilisateur peut réclamer l'objet
        $this->authorize('claim', $objet);

        // Valider les données
        $validated = $request->validate([
            'description' => 'required|string|min:20',
            'proof' => 'nullable|image|max:2048',
            'answers' => 'required|array|min:' . count($objet->details_specifiques ?? []),
            'answers.*' => 'required|string'
        ]);

        try {
            // Gérer la preuve si fournie
            $proofPath = null;
           // Dans la méthode store du ClaimController
            if ($request->hasFile('proof')) {
                $proof = $request->file('proof');
                $filename = time() . '_proof_' . Str::slug($objet->nom) . '.' . $proof->getClientOriginalExtension();
                $proof->storeAs('proofs', $filename, 'public');
                $proofPath = 'storage/proofs/' . $filename; // Ajout du chemin complet
            }

            // Créer la réclamation
            $claim = $objet->claims()->create([
                'user_id' => auth()->id(),
                'description' => $validated['description'],
                'proof_url' => $proofPath,
                'answers' => $validated['answers'],
                'status' => Claim::STATUS_PENDING
            ]);

            // Notifier le propriétaire
            $objet->user->notify(new \App\Notifications\ClaimNotification($claim));

            return redirect()
                ->route('claims.index', ['tab' => 'sent'])
                ->with('success', 'Votre réclamation a été soumise avec succès et est en attente de validation.');

        } catch (\Exception $e) {
            // Supprimer la preuve en cas d'erreur
            if (isset($proofPath)) {
                Storage::delete($proofPath);
            }

            return back()
                ->withInput()
                ->with('error', 'Une erreur est survenue lors de la soumission de votre réclamation.');
        }
    }

    public function approve(Claim $claim)
    {
        // Vérifier si l'utilisateur peut approuver la réclamation
        $this->authorize('validateClaim', $claim->objet);
    
        try {
            // Appeler la méthode approve du modèle Claim
            $claim->approve();
            
            return redirect()
                ->route('claims.index', ['tab' => 'received'])
                ->with('success', 'La réclamation a été approuvée avec succès.');
        } catch (\Exception $e) {
            \Log::error('Erreur lors de l\'approbation de la réclamation : ' . $e->getMessage());
            return back() ->with('success', 'La réclamation a été approuvée avec succès.');
        }
    }
    
    public function reject(Request $request, Claim $claim)
    {
        // Vérifier si l'utilisateur peut rejeter la réclamation
        $this->authorize('validateClaim', $claim->objet);
    
        try {
            $validated = $request->validate([
                'rejection_reason' => 'required|string|min:10'
            ]);
    
            // Appeler la méthode reject du modèle Claim
            $claim->reject($validated['rejection_reason']);
            
            return redirect()
                ->route('claims.index', ['tab' => 'received'])
                ->with('success', 'La réclamation a été rejetée avec succès.');
        } catch (\Exception $e) {
            \Log::error('Erreur lors du rejet de la réclamation : ' . $e->getMessage());
            return back() ->with('success', 'La réclamation a été rejetée avec succès.');
        }
    }
    public function index(Request $request)
    {
        $tab = $request->get('tab', 'received');
        $userId = auth()->id();

        $query = Claim::with(['objet.user', 'user']);

        if ($tab === 'received') {
            $query->whereHas('objet', function($q) use ($userId) {
                $q->where('user_id', $userId);
            });
        } else {
            $query->where('user_id', $userId);
        }

        $claims = $query->latest()->paginate(10);

        // Compter les réclamations en attente reçues
        $pendingReceivedCount = Claim::whereHas('objet', function($q) use ($userId) {
            $q->where('user_id', $userId);
        })->where('status', 'pending')->count();

        return view('claims.index', compact('claims', 'pendingReceivedCount'));
    }

    public function show(Claim $claim)
    {
        $this->authorize('view', $claim);
        return view('claims.show', compact('claim'));
    }
}