<?php
namespace App\Http\Controllers\Api\Users;

use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class NotificationApiController extends Controller
{
    use GeneralTrait;


    public function my_notifications(Request $request)
    {
        $user = $request->user();
        $notifications = $user->notifications;

        foreach ($notifications as $notification) {
            $data[] = [
                'ID'            => $notification->id,
                'Message'       => preg_replace(["#<a href=\".*\">#", "#</a>#", "#: #", "#\n #"], '', $notification->data['message']),
                'ReadAt'        => $notification->read_at
            ];
        }
        return $this->returnData('data', $data ?? $notifications, 'notifications');
    }

    public function notification(Request $request)
    {
        $user = $request->user();
        $notification = $user->notifications->where('id', $request->notification_id)->first();

        $data = [
            'ID'            => $notification->id,
            'Message'       => preg_replace(["#<a href=\".*\">#", "#</a>#", "#: #", "#\n #"], '', $notification->data['message']),
            'ReadAt'        => $notification->read_at,
            // 'Action'        => [
            //     'Code' => "R50",
            //     "DescriptionAction" => "يمكنك اسنخدام الكود في ارسال اليوزر الي صفحة الباقات",
            //     "ForDeveloper" => "This Code For Developer Only الكود للموبايل ديفيلوبر وليس للعرض",
            //     'Codes' => "You Can Get Other Codes From : /developer_codes ",
            //     ]
        ];
        $notification->markAsRead();
        return $this->returnData('data', $data, 'notifications');
    }

    public function all_notification_read(Request $request)
    {
        $user = $request->user();
        $notification = $user->notifications;

        $notification->markAsRead();
        return $this->returnSuccessMessage('تم');
    }
}
