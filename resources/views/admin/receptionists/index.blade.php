@extends('layouts.admin')

@section('title', 'Receptionnistes')

@section('content')
<div class="mb-8">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h2 class="text-4xl font-bold text-white mb-2">Receptionnistes</h2>
            <p class="text-gray-300">Gestion de tous les receptionnistes de la salle de sport</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.receptionists.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Ajouter un Receptionniste
            </a>
        </div>
    </div>
</div>

<div class="card p-6">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="h5 mb-0">Liste des Receptionnistes</h3>
        <span class="badge bg-info">{{ $receptionists->total() }} receptionnistes</span>
    </div>

    @if($receptionists->isEmpty())
        <div class="text-center py-12">
            <i class="fas fa-clipboard text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-600 mb-2">Aucun receptionniste</h3>
            <p class="text-gray-500">Aucun receptionniste n'a encore ete ajoute au systeme.</p>
            <div class="mt-4">
                <a href="{{ route('admin.receptionists.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Ajouter le premier receptionniste
                </a>
            </div>
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-striped table-hover compact-table" id="receptionistsTable">
                <thead class="table-dark">
                    <tr>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Téléphone</th>
                        <th>Paiements</th>
                        <th>Création</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($receptionists as $receptionist)
                        <tr>
                            <td>
                                <strong>{{ Str::limit($receptionist->name, 20) }}</strong>
                                @if($receptionist->email)
                                    <br><small class="text-muted">{{ Str::limit($receptionist->email, 25) }}</small>
                                @else
                                    <span class="badge bg-secondary">Non renseigné</span>
                                @endif
                            </td>
                            <td>{{ Str::limit($receptionist->phone ?? 'Non renseigné', 15) }}</td>
                            <td>{{ $receptionist->paymentsAsReceptionist->count() }}</td>
                            <td>{{ $receptionist->created_at->format('d/m/y') }}</td>
                            <td>
                                <div class="flex gap-3">
                                    <a href="{{ route('admin.receptionists.edit', $receptionist) }}" class="text-blue-600 hover:text-blue-800 font-medium">
                                        <i class="fas fa-edit mr-1"></i> Modifier
                                    </a>
                                    <form method="POST" action="{{ route('admin.receptionists.destroy', $receptionist) }}" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 font-medium">
                                            <i class="fas fa-trash mr-1"></i> Supprimer
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    <!-- Pagination -->
    @if($receptionists->hasPages())
        <div class="pagination mt-8">
            {{ $receptionists->links() }}
        </div>
    @endif
</div>
@endsection

