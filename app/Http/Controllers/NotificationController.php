<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{

  //menandai notifikasi sudah dibaca satu satu
  public function markAsRead($id)
  {
      $user = Auth::user();
      // Ambil notifikasi dari koleksi notifikasi user
      $notification = $user->notifications->firstWhere('id', $id);
      
      if ($notification) {
          $notification->markAsRead(); // Tandai notifikasi ini saja sebagai sudah dibaca
      }
      
      return redirect()->back()->with('success', 'Notifikasi ditandai sebagai dibaca.');
  }




// menandai notifikasi sudah dibaca sesuai reminder_at hari ini atau yang sudah lewat sesuai pengaturan notif yang muncul di navbar
public function markAllAsRead(){
  $user = Auth::user();
  // Ambil notifikasi yang belum dibaca dan reminder_at adalah hari ini atau sebelumnya
  $notificationsToMark = $user->unreadNotifications->filter(function ($notification) {
    $reminderAt = isset($notification->data['reminder_at']) ? \Carbon\Carbon::parse($notification->data['reminder_at']) : null;
    
    // Memastikan reminder_at ada dan adalah hari ini atau sebelumnya
    return $reminderAt && ($reminderAt->isToday() || $reminderAt->isPast());
});

// Tandai notifikasi yang sesuai sebagai sudah dibaca
foreach ($notificationsToMark as $notification) {
    $notification->markAsRead();
}

   return redirect()->back()->with('success', 'Semua notifikasi telah ditandai sebagai dibaca.');
}






//menghapus notif sesuai yang sudah dibaca
public function destroyAllRead()
{
    $userId = Auth::id();
    // Menghapus semua notifikasi yang sudah dibaca
    $deletedCount = Notification::where('notifiable_id', $userId)
        ->whereNotNull('read_at')
        ->delete();

    if ($deletedCount > 0) {
        return redirect()->back()->with('success', "$deletedCount notifikasi telah dihapus.");
    }

    return redirect()->back()->with('info', 'Tidak ada notifikasi yang sudah dibaca untuk dihapus.');
}

}