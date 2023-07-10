<?php
namespace App\Http\Controllers\Api\Users;

use App\Models\City;
use App\Models\User;
use App\Models\Store;
use App\Models\Package;
use App\Models\Category;
use App\Helpers\MainHelper;
use App\Models\Announcement;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\StoreResource;
use App\Models\AnnouncementAttribute;
use App\Http\Resources\PackageResource;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\AnnouncementResource;
use App\Http\Resources\SubscriptionResource;

class SubscriptionApiController extends Controller
{
    use GeneralTrait;

    public function subscription_submit(Request $request)
    {
        if ($request->package_id) {
            $package = Package::find($request->package_id);
        }
        (new MainHelper)->notify_user([
            'user_id' => User::where('power', 'ADMIN')->first()->id,
            'message'=> "طلب اشتراك في باقة : " . ($package->title ?? '') . " \n للمستخدم : " . $request->user()->name ,
            'url'=> route('admin.users.index', ['id' => $request->user()->id]),
            'methods'=>['database']
        ]);

        (new MainHelper)->notify_user([
            'user_id' => $request->user()->id,
            'message'=> "تم استلام طلب اشتراك في باقة : " . ($package->title ?? '') . " \n وسنقوم بالتواصل معك في اقرب وقت" ,
            'url'=> '',
            'methods'=>['database']
        ]);
        $msg = "تم استلام طلبك اشتراك في " . ($package->title ?? '') . " وسنقوم بالتواصل معك في اقرب وقت";
        return $this->returnSuccessMessage($msg);
    }


    public function packages(Request $request)
    {
        $packages = Package::whereNot('price', 0)->latest()->get();

        $packages = PackageResource::collection($packages);
        return $this->returnData('data', $packages, 'packages');
    }

    public function my_subscriptions(Request $request)
    {
        $subscriptions = $request->user()->subscription;

        $subscriptions = SubscriptionResource::collection($subscriptions);
        return $this->returnData('data', $subscriptions, 'subscriptions');
    }

}
