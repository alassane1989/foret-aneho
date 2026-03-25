@extends('admin.layouts.admin')

@section('title', 'Mon Profil - Administration')
@section('page-title', 'Mon Profil')

@push('styles')
<style>
    .avatar-container {
        position: relative;
        display: inline-block;
    }
    .avatar-img {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid #2E7D32;
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }
    .avatar-upload {
        position: absolute;
        bottom: 0;
        right: 0;
        background: #2E7D32;
        color: white;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s;
        border: 2px solid white;
    }
    .avatar-upload:hover {
        background: #1B5E20;
        transform: scale(1.1);
    }
    .avatar-upload input {
        display: none;
    }
    .form-section {
        background: white;
        border-radius: 15px;
        padding: 25px;
        margin-bottom: 25px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }
    .section-title {
        color: #2E7D32;
        font-weight: 600;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid #e8f5e9;
    }
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-md-4">
        <!-- Avatar -->
        <!-- 
        <div class="form-section text-center">
            <div class="avatar-container">
                <img src="{{ Auth::user()->avatar_url }}" alt="{{ Auth::user()->name }}" class="avatar-img" id="avatarPreview">
                <form action="{{ route('admin.profile.update-avatar') }}" method="POST" enctype="multipart/form-data" id="avatarForm">
                    @csrf
                    <label class="avatar-upload">
                        <i class="fas fa-camera"></i>
                        <input type="file" name="avatar" accept="image/*" onchange="document.getElementById('avatarForm').submit()">
                    </label>
                </form>
            </div>
            <h4 class="mt-3">{{ Auth::user()->name }}</h4>
            <p class="text-muted">{{ Auth::user()->email }}</p>
            
            @if(Auth::user()->avatar)
            <form action="{{ route('admin.profile.delete-avatar') }}" method="POST" class="mt-2">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Supprimer votre avatar ?')">
                    <i class="fas fa-trash me-2"></i>Supprimer l'avatar
                </button>
            </form>
            @endif
        </div>
-->
        <!-- Informations -->
        <div class="form-section">
            <h5 class="section-title">
                <i class="fas fa-info-circle me-2"></i>Informations
            </h5>
            <ul class="list-unstyled">
                <li class="mb-3">
                    <i class="fas fa-calendar-alt text-success me-2"></i>
                    <strong>Membre depuis :</strong><br>
                    {{ Auth::user()->created_at->format('d F Y') }}
                </li>
                <li class="mb-3">
                    <i class="fas fa-clock text-success me-2"></i>
                    <strong>Dernière connexion :</strong><br>
                    {{ Auth::user()->updated_at->diffForHumans() }}
                </li>
                <li>
                    <i class="fas fa-shield-alt text-success me-2"></i>
                    <strong>Rôle :</strong><br>
                    <span class="badge bg-success">Administrateur</span>
                </li>
            </ul>
        </div>
    </div>

    <div class="col-md-8">
        <!-- Modifier le profil -->
        <div class="form-section">
            <h5 class="section-title">
                <i class="fas fa-user-edit me-2"></i>Modifier mes informations
            </h5>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <form action="{{ route('admin.profile.update') }}" method="POST">
                @csrf
                
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Nom complet</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', Auth::user()->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', Auth::user()->email) }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save me-2"></i>Mettre à jour
                    </button>
                </div>
            </form>
        </div>

        <!-- Changer le mot de passe -->
        <div class="form-section">
            <h5 class="section-title">
                <i class="fas fa-lock me-2"></i>Changer le mot de passe
            </h5>

            <form action="{{ route('admin.profile.change-password') }}" method="POST">
                @csrf
                
                <div class="mb-3">
                    <label class="form-label">Mot de passe actuel</label>
                    <input type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror" required>
                    @error('current_password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nouveau mot de passe</label>
                        <input type="password" name="new_password" class="form-control @error('new_password') is-invalid @enderror" required>
                        @error('new_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Confirmer le mot de passe</label>
                        <input type="password" name="new_password_confirmation" class="form-control" required>
                    </div>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-key me-2"></i>Changer le mot de passe
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection