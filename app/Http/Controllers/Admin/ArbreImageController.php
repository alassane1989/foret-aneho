<?php
// app/Http/Controllers/Admin/ArbreImageController.php

namespace App\Http\Controllers\Admin;

use App\Models\Arbre;
use App\Models\ArbreImage;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ArbreImageController extends Controller
{
    /**
     * Afficher la page de gestion des images
     */
    public function index(Arbre $arbre)
    {
        return view('admin.arbres.images', compact('arbre'));
    }

    /**
     * Uploader des images
     */
    public function upload(Request $request, Arbre $arbre)
    {
        // 🔍 DEBUG : Vérifier ce qui est reçu
        // dd($request->all(), $request->file('images'));
        
        // Validation
        $request->validate([
            'images' => 'required|array|min:1',
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120', // 5MB max
            'type' => 'required|in:' . implode(',', array_keys(ArbreImage::$types))
        ], [
            'images.required' => 'Veuillez sélectionner au moins une image',
            'images.*.image' => 'Le fichier doit être une image',
            'images.*.mimes' => 'Formats acceptés : jpeg, png, jpg, gif',
            'images.*.max' => 'L\'image ne doit pas dépasser 5MB'
        ]);
        
        // Récupérer l'ordre maximum existant
        $ordre = $arbre->images()->max('ordre') ?? 0;
        $ordre++;
        
        // Tableau pour stocker les fichiers uploadés
        $uploadedFiles = [];
        
        // ✅ CORRECTION : Vérifier que $request->file('images') n'est pas null
        $files = $request->file('images');
        
        if (empty($files)) {
            return back()->withErrors(['images' => 'Aucun fichier sélectionné'])->withInput();
        }
        
        foreach ($files as $file) {
            try {
                // Générer un nom unique
                $extension = $file->getClientOriginalExtension();
                $filename = 'arbres/' . uniqid() . '_' . time() . '.' . $extension;
                
                // Sauvegarder l'image originale
                $path = $file->storeAs('public', $filename);
                
                if (!$path) {
                    throw new \Exception("Erreur lors de l'upload du fichier");
                }
                
                // Créer l'entrée en base de données
                $image = ArbreImage::create([
                    'arbre_id' => $arbre->id,
                    'url' => $filename,
                    'thumbnail_url' => $filename, // À remplacer par une vraie miniature si besoin
                    'titre' => $request->input('titre') ?? $arbre->nom . ' - ' . ArbreImage::$types[$request->type],
                    'type' => $request->type,
                    'ordre' => $ordre++
                ]);
                
                $uploadedFiles[] = $filename;
                
            } catch (\Exception $e) {
                // En cas d'erreur, supprimer les fichiers déjà uploadés
                foreach ($uploadedFiles as $file) {
                    Storage::disk('public')->delete($file);
                }
                
                return back()->withErrors(['error' => 'Erreur lors de l\'upload : ' . $e->getMessage()])->withInput();
            }
        }
        
        $count = count($uploadedFiles);
        return back()->with('success', "$count image(s) ajoutée(s) avec succès");
    }

    /**
     * Supprimer une image
     */
    public function destroy(ArbreImage $image)
    {
        try {
            // Supprimer les fichiers
            Storage::disk('public')->delete($image->url);
            
            if ($image->thumbnail_url && $image->thumbnail_url != $image->url) {
                Storage::disk('public')->delete($image->thumbnail_url);
            }
            
            // Supprimer l'entrée en base
            $image->delete();
            
            return back()->with('success', 'Image supprimée avec succès');
            
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erreur lors de la suppression : ' . $e->getMessage()]);
        }
    }
}