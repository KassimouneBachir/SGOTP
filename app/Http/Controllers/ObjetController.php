<?php

namespace App\Http\Controllers;

use App\Models\Objet;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\NotificationController;
use App\Services\MatchingService;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ObjetController extends Controller
{
    protected $matchingService;

    public function __construct(MatchingService $matchingService)
    {
        $this->matchingService = $matchingService;
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $objets = Objet::query()
            ->when($request->filled('search'), function($query) use ($request) {
                $query->search($request->search);
            })
            ->when($request->filled('statut'), function($query) use ($request) {
                $query->status($request->statut);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('objets.index', [
            'objets' => $objets,
            'search' => $request->search,
            'currentStatus' => $request->statut
        ]);
    }

    public function create()
    {
        return view('objets.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'required|string|min:10',
            'statut' => 'required|in:perdu,trouvé',
            'lieu' => 'required|string|max:255',
            'date_perte' => 'required|date',
            'photo' => 'nullable|image|max:2048',
            'specific_details' => 'nullable|array',
            'specific_details.*' => 'string|max:255'
        ]);

        try {
            DB::beginTransaction();

            // Initialiser les données de l'objet
            $objetData = [
                'nom' => $validated['nom'],
                'description' => $validated['description'],
                'statut' => $validated['statut'],
                'lieu' => $validated['lieu'],
                'date_perte' => $validated['date_perte'],
                'user_id' => auth()->id(),
                'details_specifiques' => $request->specific_details
            ];

            // Gérer la photo si elle existe
            if ($request->hasFile('photo')) {
                try {
                    $photo = $request->file('photo');
                    $filename = time() . '_' . Str::slug($request->nom) . '.' . $photo->getClientOriginalExtension();
                    
                    // Vérifier si le dossier existe, sinon le créer
                    $photoPath = storage_path('app/public/photos');
                    if (!file_exists($photoPath)) {
                        mkdir($photoPath, 0755, true);
                    }
                    
                    // Stocker la photo
                    $photo->storeAs('photos', $filename, 'public');
                    $objetData['photo_url'] = 'storage/photos/' . $filename;
                } catch (\Exception $e) {
                    \Log::error('Erreur lors du stockage de la photo : ' . $e->getMessage());
                    throw new \Exception('Erreur lors du stockage de la photo. Veuillez réessayer.');
                }
            }

            // Créer l'objet
            $objet = Objet::create($objetData);

            // Vérifier les correspondances
            if ($this->matchingService) {
                $this->matchingService->checkMatches($objet);
            }

            DB::commit();

            return redirect()
                ->route('objets.show', $objet)
                ->with('success', 'Votre objet a été enregistré avec succès !');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Erreur lors de la création de l\'objet : ' . $e->getMessage());
            
            // En cas d'erreur, supprimer la photo si elle a été uploadée
            if (isset($filename)) {
                Storage::disk('public')->delete('photos/' . $filename);
            }
            
            return back()
                ->withInput()
                ->with('error', 'Une erreur est survenue lors de l\'enregistrement de l\'objet : ' . $e->getMessage());
        }
    }

    public function show(Objet $objet)
    {
        return view('objets.show', [
            'objet' => $objet->load(['user', 'claims.user'])
        ]);
    }

    public function edit(Objet $objet)
    {
        $this->authorize('update', $objet);
        return view('objets.edit', compact('objet'));
    }

    public function update(Request $request, Objet $objet)
    {
        $this->authorize('update', $objet);

        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'required|string|min:10',
            'statut' => 'required|in:perdu,trouvé,rendu',
            'lieu' => 'required|string|max:255',
            'date_perte' => 'required|date',
            'photo' => 'nullable|image|max:2048',
            'specific_details' => 'nullable|array',
            'specific_details.*' => 'string|max:255'
        ]);

        try {
            if ($request->hasFile('photo')) {
                // Supprimer l'ancienne photo
                if ($objet->photo_url) {
                    $oldPath = str_replace('storage/', '', $objet->photo_url);
                    Storage::disk('public')->delete($oldPath);
                }

                // Enregistrer la nouvelle photo
                $photo = $request->file('photo');
                $filename = time() . '_' . Str::slug($request->nom) . '.' . $photo->getClientOriginalExtension();
                $photo->storeAs('photos', $filename, 'public');
                $validated['photo_url'] = 'storage/photos/' . $filename;
            }

            $validated['details_specifiques'] = $request->specific_details;
            $objet->update($validated);

            // Si le statut a changé, vérifier les correspondances
            if ($objet->wasChanged('statut')) {
                $this->matchingService->checkMatches($objet);
            }

            return redirect()
                ->route('objets.show', $objet)
                ->with('success', 'L\'objet a été mis à jour avec succès !');

        } catch (\Exception $e) {
            if (isset($filename)) {
                Storage::disk('public')->delete('photos/' . $filename);
            }
            
            return back()
                ->withInput()
                ->with('error', 'Une erreur est survenue lors de la mise à jour de l\'objet : ' . $e->getMessage());
        }
    }

    public function destroy(Objet $objet)
    {
        $this->authorize('delete', $objet);
        
        try {
            if ($objet->photo_url) {
                $oldPath = str_replace('storage/', '', $objet->photo_url);
                Storage::disk('public')->delete($oldPath);
            }
            
            $objet->claims()->delete();
            $objet->delete();

            return redirect()
                ->route('objets.index')
                ->with('success', 'L\'objet a été supprimé avec succès !');

        } catch (\Exception $e) {
            return back()->with('error', 'Une erreur est survenue lors de la suppression de l\'objet.');
        }
    }

    public function claim(Request $request, Objet $objet)
    {
        // Vérifier que l'utilisateur ne réclame pas son propre objet
        if ($objet->user_id === auth()->id()) {
            return back()->with('error', 'Vous ne pouvez pas réclamer votre propre objet.');
        }

        // Vérifier que l'objet est toujours disponible
        if ($objet->statut !== 'trouvé') {
            return back()->with('error', 'Cet objet n\'est plus disponible pour réclamation.');
        }

        // Préparer les règles de validation
        $rules = [
            'description' => 'required|string|min:20',
            'proof' => 'nullable|image|max:2048'
        ];

        // Ajouter la validation des réponses si l'objet a des questions spécifiques
        if (!empty($objet->details_specifiques)) {
            $rules['answers'] = 'required|array|size:' . count($objet->details_specifiques);
            foreach(range(0, count($objet->details_specifiques) - 1) as $index) {
                $rules['answers.' . $index] = 'required|string|min:2';
            }
        }

        try {
            $validated = $request->validate($rules);

            // Gérer la preuve si elle est fournie
            $proofPath = null;
            if ($request->hasFile('proof')) {
                $proof = $request->file('proof');
                $filename = time() . '_proof_' . Str::slug($objet->nom) . '.' . $proof->getClientOriginalExtension();
                $proof->storeAs('public/proofs', $filename);
                $proofPath = Storage::url('proofs/' . $filename);
            }

            // Créer la réclamation
            $claim = $objet->claims()->create([
                'user_id' => auth()->id(),
                'description' => $validated['description'],
                'proof_url' => $proofPath,
                'answers' => $request->answers ?? [],
                'status' => 'pending'
            ]);

            // Notifier le propriétaire de l'objet
            $objet->user->notify(new \App\Notifications\ClaimNotification($claim));

            return redirect()
                ->route('claims.index', ['tab' => 'sent'])
                ->with('success', 'Votre réclamation a été soumise avec succès et est en attente de validation.');

        } catch (\Exception $e) {
            \Log::error('Erreur lors de la soumission de la réclamation : ' . $e->getMessage());
            
            // Supprimer la preuve si elle a été uploadée
            if (isset($filename)) {
                Storage::delete('public/proofs/' . $filename);
            }
            
            return back()
                ->withInput()
                ->with('error', 'Une erreur est survenue lors de la soumission de votre réclamation. Veuillez réessayer.');
        }
    }
}