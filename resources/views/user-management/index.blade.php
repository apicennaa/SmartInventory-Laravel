@extends('layouts.app')

@section('title', 'User Management')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght@100..700" rel="stylesheet"/>

<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">User Management</h1>
            <p class="text-sm text-gray-500 mt-1">Manage system users and permissions</p>
        </div>
        <a href="{{ route('user-management.create') }}" class="flex items-center gap-2 bg-purple-600 hover:bg-purple-700 text-white px-4 py-2.5 rounded-lg font-medium transition-colors shadow-sm hover:shadow-md">
            <span class="material-symbols-outlined text-lg">person_add</span>
            Add User
        </a>
    </div>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-gray-500 uppercase mb-1">Total Users</p>
                <p class="text-2xl font-black text-gray-900">{{ number_format($users->total()) }}</p>
            </div>
            <div class="p-3 bg-purple-50 rounded-lg">
                <span class="material-symbols-outlined text-purple-600">people</span>
            </div>
        </div>
    </div>
    <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-gray-500 uppercase mb-1">Super Admin</p>
                <p class="text-2xl font-black text-gray-900">{{ number_format($users->where('role', 'super_adm')->count()) }}</p>
            </div>
            <div class="p-3 bg-red-50 rounded-lg">
                <span class="material-symbols-outlined text-red-600">admin_panel_settings</span>
            </div>
        </div>
    </div>
    <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-gray-500 uppercase mb-1">Admin</p>
                <p class="text-2xl font-black text-gray-900">{{ number_format($users->where('role', 'admin')->count()) }}</p>
            </div>
            <div class="p-3 bg-blue-50 rounded-lg">
                <span class="material-symbols-outlined text-blue-600">shield</span>
            </div>
        </div>
    </div>
    <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-gray-500 uppercase mb-1">Staff</p>
                <p class="text-2xl font-black text-gray-900">{{ number_format($users->where('role', 'staff')->count()) }}</p>
            </div>
            <div class="p-3 bg-green-50 rounded-lg">
                <span class="material-symbols-outlined text-green-600">person</span>
            </div>
        </div>
    </div>
</div>

<div class="bg-white rounded-xl border border-gray-200 shadow-sm">
    <div class="p-6 border-b border-gray-200">
        <div class="relative max-w-md">
            <span class="material-symbols-outlined absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-lg">search</span>
            <input type="text" placeholder="Search user..." class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 text-sm">
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">User</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Role</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Action</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @forelse($users as $user)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center mr-3">
                                <span class="text-purple-600 font-bold text-sm">{{ substr($user->name, 0, 1) }}</span>
                            </div>
                            <div>
                                <div class="text-sm font-bold text-gray-900">{{ $user->name }}</div>
                                <div class="text-xs text-gray-500">ID: {{ $user->id }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $user->email }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($user->role == 'super_adm')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-800">
                                Super Admin
                            </span>
                        @elseif($user->role == 'admin')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-blue-100 text-blue-800">
                                Admin
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-green-100 text-green-800">
                                Staff
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-green-100 text-green-800">
                            <span class="w-1.5 h-1.5 bg-green-500 rounded-full mr-1.5"></span>
                            Active
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('user-management.edit', $user->id) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Edit">
                                <span class="material-symbols-outlined text-lg">edit</span>
                            </a>
                            <form action="{{ route('user-management.destroy', $user->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors" onclick="return confirm('Are you sure you want to delete this user?')" title="Delete">
                                    <span class="material-symbols-outlined text-lg">delete</span>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center">
                            <span class="material-symbols-outlined text-gray-400 text-5xl mb-3">people</span>
                            <p class="text-gray-500 font-medium">No users found</p>
                            <p class="text-sm text-gray-400 mt-1">Get started by adding your first user</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($users->hasPages())
    <div class="p-6 border-t border-gray-200">
        <div class="flex items-center justify-between">
            <div class="text-sm text-gray-700">
                Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ $users->total() }} results
            </div>
            <div>
                {{ $users->links() }}
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
