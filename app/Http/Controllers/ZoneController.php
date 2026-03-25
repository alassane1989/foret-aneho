<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Zone;

class ZoneController extends Controller
{
    /**
     * Afficher la liste des zones
     */
    public function index()
    {
        $zones = Zone::orderBy('ordre')->get();
        return view('zones.index', compact('zones'));
    }

    /**
     * Afficher le détail d'une zone
     */
    public function show($slug)
    {
        $zone = Zone::where('slug', $slug)->firstOrFail();
        $arbres = $zone->arbres()->with('espece')->get();
        
        return view('zones.show', compact('zone', 'arbres'));
    }
}