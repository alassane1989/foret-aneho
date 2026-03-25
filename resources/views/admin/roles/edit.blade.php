@extends('admin.layouts.admin')

@section('title', 'Modifier le rôle')
@section('page-title', 'Modifier le rôle : ' . $role->nom)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Modifier le rôle</h5>
                </div>
                <form action="{{ route('admin.roles.update', $role) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="nom" class="form-label">Nom du rôle <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('nom') is-invalid @enderror" 
                                           id="nom" name="nom" value="{{ old('nom', $role->nom) }}" required>
                                    @error('nom')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="niveau" class="form-label">Niveau hiérarchique</label>
                                    <input type="number" class="form-control @error('niveau') is-invalid @enderror" 
                                           id="niveau" name="niveau" value="{{ old('niveau', $role->niveau) }}" min="0">
                                    <small class="text-muted">Plus le nombre est élevé, plus le rôle a de privilèges</small>
                                    @error('niveau')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3">{{ old('description', $role->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="est_defaut" name="est_defaut" value="1" 
                                       {{ old('est_defaut', $role->est_defaut) ? 'checked' : '' }}>
                                <label class="form-check-label" for="est_defaut">
                                    Rôle par défaut (attribué automatiquement aux nouveaux utilisateurs)
                                </label>
                            </div>
                        </div>

                        <hr>

                        <h5 class="mb-3">Permissions</h5>
                        
                        @foreach($permissions as $groupe => $groupePermissions)
                        <div class="card mb-3">
                            <div class="card-header bg-light">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input groupe-checkbox" 
                                           id="groupe_{{ Str::slug($groupe) }}" data-groupe="{{ $groupe }}">
                                    <label class="form-check-label fw-bold" for="groupe_{{ Str::slug($groupe) }}">
                                        {{ ucfirst($groupe) }}
                                    </label>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    @foreach($groupePermissions as $permission)
                                    <div class="col-md-4 mb-2">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input permission-checkbox" 
                                                   name="permissions[]" value="{{ $permission->id }}"
                                                   id="perm_{{ $permission->id }}"
                                                   data-groupe="{{ $groupe }}"
                                                   {{ in_array($permission->id, $rolePermissions) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="perm_{{ $permission->id }}">
                                                {{ $permission->nom }}
                                                <small class="text-muted d-block">{{ $permission->slug }}</small>
                                            </label>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Mettre à jour
                        </button>
                        <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Annuler
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Initialiser les états des groupes
    $('.groupe-checkbox').each(function() {
        const groupe = $(this).data('groupe');
        const totalPermissions = $(`.permission-checkbox[data-groupe="${groupe}"]`).length;
        const checkedPermissions = $(`.permission-checkbox[data-groupe="${groupe}"]:checked`).length;
        
        if (checkedPermissions === totalPermissions) {
            $(this).prop('checked', true);
            $(this).prop('indeterminate', false);
        } else if (checkedPermissions === 0) {
            $(this).prop('checked', false);
            $(this).prop('indeterminate', false);
        } else {
            $(this).prop('checked', false);
            $(this).prop('indeterminate', true);
        }
    });

    // Sélection/déselection par groupe
    $('.groupe-checkbox').change(function() {
        const groupe = $(this).data('groupe');
        const estCoche = $(this).prop('checked');
        $(`.permission-checkbox[data-groupe="${groupe}"]`).prop('checked', estCoche);
    });

    // Mettre à jour le checkbox de groupe quand des permissions sont cochées/décochées
    $('.permission-checkbox').change(function() {
        const groupe = $(this).data('groupe');
        const totalPermissions = $(`.permission-checkbox[data-groupe="${groupe}"]`).length;
        const checkedPermissions = $(`.permission-checkbox[data-groupe="${groupe}"]:checked`).length;
        
        if (checkedPermissions === totalPermissions) {
            $(`#groupe_${groupe}`).prop('checked', true);
            $(`#groupe_${groupe}`).prop('indeterminate', false);
        } else if (checkedPermissions === 0) {
            $(`#groupe_${groupe}`).prop('checked', false);
            $(`#groupe_${groupe}`).prop('indeterminate', false);
        } else {
            $(`#groupe_${groupe}`).prop('checked', false);
            $(`#groupe_${groupe}`).prop('indeterminate', true);
        }
    });
});
</script>
@endpush