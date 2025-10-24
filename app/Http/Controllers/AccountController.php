<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;

class AccountController extends Controller
{
    public function profile(): View
    {
        $user = Auth::user();
        return view('account.profile', compact('user'));
    }

    /**
     * Profil bilgilerini güncelle
     */
    public function updateProfile(Request $request): RedirectResponse
    {
        $user = Auth::user();
        
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'national_id' => 'nullable|string|max:11|min:11',
            'gender' => 'nullable|in:male,female',
            'birth_date' => 'nullable|date|before:today',
            'address' => 'nullable|string|max:500',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:20',
            'notification_email' => 'nullable|boolean',
            'notification_sms' => 'nullable|boolean',
        ], [
            'first_name.required' => 'Ad alanı zorunludur.',
            'last_name.required' => 'Soyad alanı zorunludur.',
            'email.required' => 'E-posta alanı zorunludur.',
            'email.email' => 'Geçerli bir e-posta adresi girin.',
            'email.unique' => 'Bu e-posta adresi zaten kullanılıyor.',
            'phone.max' => 'Telefon numarası en fazla 20 karakter olabilir.',
            'national_id.max' => 'TC Kimlik No 11 haneli olmalıdır.',
            'national_id.min' => 'TC Kimlik No 11 haneli olmalıdır.',
            'gender.in' => 'Geçerli bir cinsiyet seçiniz.',
            'birth_date.date' => 'Geçerli bir doğum tarihi giriniz.',
            'birth_date.before' => 'Doğum tarihi bugünden önce olmalıdır.',
            'address.max' => 'Adres en fazla 500 karakter olabilir.',
            'emergency_contact_name.max' => 'Acil durum kişi adı en fazla 255 karakter olabilir.',
            'emergency_contact_phone.max' => 'Acil durum telefonu en fazla 20 karakter olabilir.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $user->update([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'national_id' => $request->national_id,
                'gender' => $request->gender,
                'birth_date' => $request->birth_date,
                'address' => $request->address,
                'emergency_contact_name' => $request->emergency_contact_name,
                'emergency_contact_phone' => $request->emergency_contact_phone,
                'notification_email' => $request->has('notification_email'),
                'notification_sms' => $request->has('notification_sms'),
            ]);

            return redirect()->route('account.profile')
                ->with('success', 'Profil bilgileriniz başarıyla güncellendi.');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Profil güncellenirken bir hata oluştu.')
                ->withInput();
        }
    }

    /**
     * Şifre değiştir
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $user = Auth::user();
        
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'current_password.required' => 'Mevcut şifre alanı zorunludur.',
            'password.required' => 'Yeni şifre alanı zorunludur.',
            'password.min' => 'Şifre en az 8 karakter olmalıdır.',
            'password.confirmed' => 'Şifre onayı eşleşmiyor.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator);
        }

        // Mevcut şifreyi kontrol et
        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()
                ->with('error', 'Mevcut şifre yanlış.');
        }

        try {
            $user->update([
                'password' => Hash::make($request->password),
            ]);

            return redirect()->route('account.profile')
                ->with('success', 'Şifreniz başarıyla güncellendi.');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Şifre güncellenirken bir hata oluştu.');
        }
    }

    /**
     * Profil fotoğrafı yükle
     */
    public function uploadPhoto(Request $request): RedirectResponse
    {
        $user = Auth::user();
        
        $validator = Validator::make($request->all(), [
            'profile_photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120', // 5MB
        ], [
            'profile_photo.required' => 'Lütfen bir fotoğraf seçin.',
            'profile_photo.image' => 'Seçilen dosya bir resim olmalıdır.',
            'profile_photo.mimes' => 'Fotoğraf JPG, PNG, JPEG veya GIF formatında olmalıdır.',
            'profile_photo.max' => 'Fotoğraf maksimum 5MB olmalıdır.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Eski fotoğrafı sil
            if ($user->profile_photo_path && Storage::exists($user->profile_photo_path)) {
                Storage::delete($user->profile_photo_path);
            }

            // Yeni fotoğrafı yükle
            $photo = $request->file('profile_photo');
            $filename = 'profile_photos/' . $user->id . '_' . time() . '.' . $photo->getClientOriginalExtension();
            
            $path = $photo->storeAs('public', $filename);
            $relativePath = str_replace('public/', '', $path);

            // Veritabanını güncelle
            $user->update([
                'profile_photo_path' => $relativePath,
            ]);

            return redirect()->route('account.profile')
                ->with('success', 'Profil fotoğrafınız başarıyla güncellendi.');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Fotoğraf yüklenirken bir hata oluştu.')
                ->withInput();
        }
    }

    /**
     * Profil fotoğrafı sil
     */
    public function deletePhoto(): JsonResponse
    {
        $user = Auth::user();
        
        try {
            if ($user->profile_photo_path && Storage::exists($user->profile_photo_path)) {
                Storage::delete($user->profile_photo_path);
            }

            $user->update([
                'profile_photo_path' => null,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Profil fotoğrafınız silindi.'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Fotoğraf silinirken bir hata oluştu.'
            ], 500);
        }
    }
}


