<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = User::with('roles')->latest();

        // Arama filtreleri
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Rol filtresi
        if ($request->filled('role')) {
            $query->whereHas('roles', function ($q) use ($request) {
                $q->where('name', $request->get('role'));
            });
        }

        // Durum filtresi
        if ($request->filled('status')) {
            $query->where('is_active', $request->get('status') === 'active');
        }

        $users = $query->paginate(15)->withQueryString();
        $roles = Role::orderBy('name')->get(['id', 'name']);
        
        $statusOptions = [
            'active' => 'Aktif',
            'inactive' => 'Pasif'
        ];

        return view('users.index', compact('users', 'roles', 'statusOptions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $roles = Role::orderBy('name')->get(['id', 'name']);
        return view('users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserStoreRequest $request): RedirectResponse
    {
        $data = $request->validated();
        
        // Şifreyi hash'le
        $data['password'] = Hash::make($data['password']);
        
        // Varsayılan değerler
        $data['is_active'] = $request->has('is_active');
        $data['notification_email'] = $request->has('notification_email');
        $data['notification_sms'] = $request->has('notification_sms');

        $user = User::create($data);

        // Rol atama
        if ($request->filled('roles')) {
            $roleIds = $request->get('roles');
            $roles = Role::whereIn('id', $roleIds)->pluck('name');
            $user->assignRole($roles);
        }

        return redirect()->route('users.index')->with('success', 'Kullanıcı başarıyla oluşturuldu.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user): View
    {
        $user->load(['roles', 'flats.flat.apartment.block.site']);
        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user): View
    {
        $roles = Role::orderBy('name')->get(['id', 'name']);
        $userRoles = $user->roles->pluck('id')->toArray();
        
        return view('users.edit', compact('user', 'roles', 'userRoles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserUpdateRequest $request, User $user): RedirectResponse
    {
        $data = $request->validated();

        // Şifre güncelleniyorsa hash'le
        if ($request->filled('password')) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        // Boolean değerler
        $data['is_active'] = $request->has('is_active');
        $data['notification_email'] = $request->has('notification_email');
        $data['notification_sms'] = $request->has('notification_sms');

        $user->update($data);

        // Rolleri güncelle
        if ($request->filled('roles')) {
            $roleIds = $request->get('roles');
            $roles = Role::whereIn('id', $roleIds)->pluck('name');
            $user->syncRoles($roles);
        } else {
            $user->syncRoles([]);
        }

        return redirect()->route('users.index')->with('success', 'Kullanıcı başarıyla güncellendi.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user): RedirectResponse
    {
        // Kendi hesabını silmeye çalışıyorsa engelle
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Kendi hesabınızı silemezsiniz.');
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'Kullanıcı başarıyla silindi.');
    }

    /**
     * Reset user password
     */
    public function resetPassword(Request $request, User $user): RedirectResponse
    {
        $request->validate([
            'password' => 'required|string|min:8|confirmed'
        ]);

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return back()->with('success', 'Kullanıcı şifresi başarıyla sıfırlandı.');
    }

    /**
     * Toggle user active status
     */
    public function toggleStatus(User $user): RedirectResponse
    {
        // Kendi hesabını pasifleştirmeye çalışıyorsa engelle
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Kendi hesabınızı pasifleştiremezsiniz.');
        }

        $user->update([
            'is_active' => !$user->is_active
        ]);

        $status = $user->is_active ? 'aktif' : 'pasif';
        return back()->with('success', "Kullanıcı durumu {$status} olarak güncellendi.");
    }
}
