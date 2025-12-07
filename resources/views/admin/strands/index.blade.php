@extends('layouts.app')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Strands</h1>
        <a href="{{ route('admin.strands.create') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition">
            + New Strand
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($strands as $strand)
        <div class="bg-white rounded-xl shadow-sm p-6 hover:shadow-md transition">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <h3 class="text-lg font-bold text-gray-900">{{ $strand->code }}</h3>
                    <p class="text-sm text-gray-600">{{ $strand->name }}</p>
                </div>
                <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $strand->track->code === 'ACAD' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }}">
                    {{ $strand->track->code }}
                </span>
            </div>

            @if($strand->description)
            <p class="text-sm text-gray-600 mb-4">{{ $strand->description }}</p>
            @endif

            <div class="flex space-x-2">
                <a href="{{ route('admin.strands.edit', $strand) }}" class="flex-1 text-center bg-blue-50 text-blue-600 px-3 py-2 rounded-lg text-sm hover:bg-blue-100 transition">
                    Edit
                </a>
                <form action="{{ route('admin.strands.destroy', $strand) }}" method="POST" class="flex-1">
                    @csrf
                    @method('DELETE')
                    <button type="submit" onclick="return confirm('Are you sure?')" class="w-full bg-red-50 text-red-600 px-3 py-2 rounded-lg text-sm hover:bg-red-100 transition">
                        Delete
                    </button>
                </form>
            </div>
        </div>
        @empty
        <div class="col-span-full text-center py-12 text-gray-500">
            No strands found. Create one to get started.
        </div>
        @endforelse
    </div>

    @if($strands->hasPages())
    <div class="mt-6">
        {{ $strands->links() }}
    </div>
    @endif
</div>
@endsection
