{{-- resources/views/admin/arbres/images.blade.php --}}
@extends('admin.layouts.admin')

@section('title', 'Gérer les images - ' . $arbre->nom)
@section('page-title', 'Gestion des images')

@section('content')
<div class="container">
    <!-- En-tête -->
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h4 class="mb-0">
                <i class="fas fa-images text-success me-2"></i>
                Gérer les images - <span class="text-success">{{ $arbre->nom }}</span>
            </h4>
            <a href="{{ route('admin.arbres.show', $arbre->id) }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Retour à la fiche
            </a>
        </div>
    </div>
    
    <!-- Formulaire d'upload -->
    <form action="{{ route('admin.arbres.images.upload', $arbre) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fas fa-upload me-2"></i>Ajouter des images</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Images</label>
                            <input type="file" 
                                   name="images[]" 
                                   multiple 
                                   accept="image/*" 
                                   class="form-control @error('images.*') is-invalid @enderror">
                            @error('images.*')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Formats acceptés : JPEG, PNG, JPG, GIF (max 5MB)</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Type d'images</label>
                            <select name="type" class="form-control @error('type') is-invalid @enderror">
                                @foreach(App\Models\ArbreImage::$types as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="text-end">
                    <button type="submit" class="btn btn-success px-4">
                        <i class="fas fa-upload me-2"></i>Uploader
                    </button>
                </div>
            </div>
        </div>
    </form>
    
    <!-- Liste des images existantes -->
    <div class="card">
        <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-images me-2"></i>Images existantes</h5>
            <span class="badge bg-white text-success">{{ $arbre->images->count() }} image(s)</span>
        </div>
        <div class="card-body">
            @if($arbre->images->count() > 0)
                <div class="row g-3">
                    @foreach($arbre->images as $image)
                    <div class="col-md-3">
                        <div class="card h-100">
                            {{-- ✅ CORRECTION : Utiliser full_thumbnail_url (avec "thumbnail") --}}
                            <img src="{{ $image->full_thumbnail_url }}" 
                                 class="card-img-top" 
                                 alt="{{ $image->titre }}"
                                 style="height: 180px; object-fit: cover;">
                            <div class="card-body p-2">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <span class="badge bg-success">{{ $image->type_libelle }}</span>
                                    </div>
                                    <form action="{{ route('admin.arbres.images.delete', $image) }}" 
                                          method="POST" 
                                          onsubmit="return confirm('Supprimer cette image ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                                @if($image->titre)
                                    <p class="mt-2 mb-0 small text-muted">{{ $image->titre }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-5">
                    <div class="text-success mb-3">
                        <i class="fas fa-images fa-4x"></i>
                    </div>
                    <h5 class="text-muted">Aucune image</h5>
                    <p class="mb-3">Commencez par ajouter des images via le formulaire ci-dessus.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection