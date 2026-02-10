@extends('layouts.admin')

@section('title', 'Notifications')

@section('content')
<div class="mb-8">
    <div class="flex justify-between items-center">
        <h2 class="text-4xl font-bold text-white">Notifications</h2>
        @if(auth()->user()->unreadNotifications->count() > 0)
            <form method="POST" action="{{ route('admin.notifications.read-all') }}" class="inline">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-check-double me-2"></i> Marquer toutes comme lues
                </button>
            </form>
        @endif
    </div>
</div>

<div class="card p-6">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($notifications->isEmpty())
        <div class="text-center py-12">
            <i class="fas fa-bell-slash text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-600 mb-2">Aucune notification</h3>
            <p class="text-gray-500">Vous n'avez aucune notification pour le moment.</p>
        </div>
    @else
        <div class="space-y-3">
            @foreach($notifications as $notification)
                <div class="border rounded-lg p-4 hover:bg-gray-50 transition {{ $notification->read_at ? 'bg-gray-50 border-gray-200' : 'bg-blue-50 border-blue-200' }}">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            @if($notification->data['action_type'] === 'new_member')
                                <i class="fas fa-user-plus text-blue-500 text-xl"></i>
                            @elseif($notification->data['action_type'] === 'payment')
                                <i class="fas fa-credit-card text-green-500 text-xl"></i>
                            @elseif($notification->data['action_type'] === 'new_course')
                                <i class="fas fa-dumbbell text-orange-500 text-xl"></i>
                            @else
                                <i class="fas fa-info-circle text-gray-500 text-xl"></i>
                            @endif
                        </div>
                        <div class="ml-4 flex-1">
                            <div class="flex items-center justify-between">
                                <h4 class="text-lg font-semibold text-gray-900 {{ $notification->read_at ? 'font-normal' : 'font-bold' }}">
                                    {{ $notification->data['title'] }}
                                </h4>
                                <div class="flex items-center space-x-2">
                                    @if(!$notification->read_at)
                                        <span class="bg-blue-600 text-white text-xs px-2 py-1 rounded-full">Nouveau</span>
                                    @endif
                                    <span class="text-sm text-gray-500">
                                        {{ \Carbon\Carbon::parse($notification->created_at)->format('d/m/Y H:i') }}
                                    </span>
                                </div>
                            </div>
                            <p class="text-gray-700 mt-2">{{ $notification->data['message'] }}</p>
                            <div class="mt-3 flex items-center justify-between">
                                <div class="text-sm text-gray-500">
                                    <i class="fas fa-user me-1"></i>
                                    {{ $notification->data['performed_by'] }}
                                    <span class="mx-2">•</span>
                                    {{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}
                                </div>
                                @if(!$notification->read_at)
                                    <button onclick="markAsRead({{ $notification->id }})" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                        <i class="fas fa-check me-1"></i> Marquer comme lu
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($notifications->hasPages())
            <div class="mt-8">
                {{ $notifications->links() }}
            </div>
        @endif
    @endif
</div>
@endsection
