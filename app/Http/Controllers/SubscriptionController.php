<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Package;
use App\Helpers\MainHelper;
use App\Models\Subscription;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{

    public function __construct()
    {
        // $this->authorizeResource(Subscription::class, 'subscription');
    }

    public function index(Request $request)
    {

        $subscriptions =  Subscription::where(function($q)use($request){
            if($request->id!=null)
                $q->where('id',$request->id);
            if($request->q!=null)
                $q->where('name','LIKE','%'.$request->q.'%');
        })->orderBy('id','DESC')->paginate();

        return view('admin.subscriptions.index',compact('subscriptions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = User::where('power', 'USER')->latest()->get();
        $packages = Package::latest()->get();
        return view('admin.subscriptions.create', compact('users', 'packages'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id'=> "required|exists:users,id",
            'package_id'=> "required|exists:packages,id",
            'paid'=> "nullable|in:0,1",
            'start_date'=> "nullable|date",
            'end_date'=> "nullable|date",
            'status'=>"nullable|in:0,1",
        ]);
        $package = Package::find($request->package_id);
        $user = User::find($request->user_id);
        if ($request->status) {
            $user->subscription->each(function($sub){
                $sub->update(['status' => 0]);
            });

            (new MainHelper)->notify_user([
                'user_id' => $user->id,
                'message'=> "تم تفعيل باقة : $package->title \n على حسابك",
                'url'=> "",
                'methods'=>['database']
            ]);
        }
        Subscription::create([
            'user_id'           => $request->user_id,
            'package_id'        => $package->id,
            'price'             => $request->price ?? $package->price,
            'status'            => $request->status ?? 0,
            'paid'              => $request->paid ?? 0,
            'start_date'        => $request->start_date ?? now(),
            'end_date'          => $request->end_date ?? now()->addDays($package->time),
        ]);

        flash()->success('تم إضافة بنجاح','عملية ناجحة');
        return redirect()->route('admin.subscriptions.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Subscription  $subscription
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Subscription $subscription)
    {
        $subscription =  Subscription::where(function($q)use($request){
            if($request->id!=null)
                $q->where('id',$request->id);
            if($request->q!=null)
                $q->where('title','LIKE','%'.$request->q.'%')->orWhere('description','LIKE','%'.$request->q.'%');
        })->orderBy('id','DESC')->paginate();

        return view('admin.subscriptions.index',compact('subscription'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Subscription  $subscription
     * @return \Illuminate\Http\Response
     */
    public function edit(Subscription $subscription)
    {
        $users = User::where('power', 'USER')->latest()->get();
        $packages = Package::latest()->get();
        return view('admin.subscriptions.edit',compact('subscription', 'users', 'packages'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Subscription  $subscription
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Subscription $subscription)
    {
        $request->validate([
            'user_id'=> "required|exists:users,id",
            'package_id'=> "required|exists:packages,id",
            'paid'=> "nullable|in:0,1",
            'start_date'=> "nullable|date",
            'end_date'=> "nullable|date",
            'status'=>"nullable|in:0,1",
        ]);
        $package = Package::find($request->package_id);
        $user = User::find($request->user_id);

        if (!$request->status) {
            (new MainHelper)->notify_user([
                'user_id' => $user->id,
                'message'=> "تم إيقاف تفعيل باقة : $package->title \n على حسابك",
                'url'=> "",
                'methods'=>['database']
            ]);
        }else {
            (new MainHelper)->notify_user([
                'user_id' => $user->id,
                'message'=> "تم تحديث باقة : $package->title \n على حسابك",
                'url'=> "",
                'methods'=>['database']
            ]);
        }

        $subscription->update([
            'user_id'           => $request->user_id,
            'package_id'        => $package->id,
            'price'             => $request->price ?? $subscription->price,
            'status'            => $request->status ?? 0,
            'paid'              => $request->paid ?? 0,
            'start_date'        => $request->start_date ?? now(),
            'end_date'          => $request->end_date ?? now()->addDays($package->time),
        ]);


        flash()->success('تم تحديث بنجاح','عملية ناجحة');
        return redirect()->route('admin.subscriptions.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Subscription  $subscription
     * @return \Illuminate\Http\Response
     */
    public function destroy(Subscription $subscription)
    {
        $package = Package::find($subscription->package_id);
        $user = User::find($subscription->user_id);
        try {
            (new MainHelper)->notify_user([
                'user_id' => $user->id,
                'message'=> "تم إلغاء باقة : $package->title \n على حسابك",
                'url'=> "",
                'methods'=>['database']
            ]);

            $subscription->delete();
            flash()->success('تم حذف بنجاح','عملية ناجحة');
            return redirect()->route('admin.subscriptions.index');
        } catch (\Exception $ex) {
            flash()->error($ex->getMessage(),'عملية فاشلة');
            return redirect()->route('admin.subscriptions.index');
        }

    }
}
