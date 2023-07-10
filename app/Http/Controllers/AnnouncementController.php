<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Announcement;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\AnnouncementAttribute;

class AnnouncementController extends Controller
{
    use GeneralTrait;

    public function __construct()
    {
        $this->authorizeResource(Announcement::class, 'announcement');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $announcements=Announcement::where(function ($q) use ($request) {
            if ($request->id!=null) {
                $q->where('id', $request->id);
            }

            $q->where('title', 'LIKE', '%'.$request->key.'%');
        })->orderBy('id', 'DESC')->paginate();

        return view('admin.announcements.index', compact('announcements'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = User::where('power', 'USER')->latest()->get();
        return view('admin.announcements.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        // dd($request->all());

        $rules = [
            'user_id'=>"required|exists:users,id",
            'Category'=>"required|exists:categories,id",
            'City'=>"required|exists:cities,id",
            'price'=>"required",
        ];

        foreach (config("laravellocalization.supportedLocales") as $key => $lang) {
            // Rules
            // $rules["$key.title"] = "string|max:255";
            // Lang
            $langAttr["title"][$key] = $data[$key]['title'] ?? $data[app()->getLocale()]['title'];
            $langAttr["description"][$key] = $data[$key]['description'] ?? $data[app()->getLocale()]['description'];
        }
        $request->validate($rules);


        try {
            DB::beginTransaction();
            $user = User::find($request->user_id);
            $announcement = Announcement::create([
                "user_id"           => $request->user_id,
                "store_id"          => $user->store->id ?? 0,
                "category_id"       => $request->Category,
                "type"              =>$request->type,
                "city_id"           => $request->City,
                "title"             => $langAttr["title"],
                "number"            => $this->generateAnnouncementNumber(),
                "description"       => $langAttr["description"],
                "is_featured"       => $request->is_featured,
                "price"             => $request->price,
                "status"            => $request->status ?? 0,
            ]);

            if ($request->announcement_attributes) {
                foreach ($request->announcement_attributes as $value) {
                    $announcement_attr = AnnouncementAttribute::create([
                        "name" => $value['attr'],
                        "value" => $value['value'],
                        "announcement_id" => $announcement->id,
                    ]);
                }
            }

            if ($request["fileuploader-list-file"]) {
                $uploaded_files=json_decode($request["fileuploader-list-file"]);
                $attachments=[];
                foreach ($uploaded_files as $uploaded_file) {
                    array_push($attachments, $uploaded_file->file);
                }
                $attachments = implode(',', $attachments);
                $announcement->update(['images' => $attachments]);
            }
            DB::commit();
            flash()->success('تم الاضـافة بنجاح', 'عملية ناجحة');
            return redirect()->route('admin.announcements.index');
        } catch (\Exception $e) {
            DB::rollback();
            flash()->success($e->getMessage(), 'عملية فاشلة');
            return back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Announcement  $announcement
     * @return \Illuminate\Http\Response
     */
    public function show(Announcement $announcement)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Announcement  $announcement
     * @return \Illuminate\Http\Response
     */
    public function edit(Announcement $announcement)
    {
        $users = User::where('power', 'USER')->latest()->get();
        return view('admin.announcements.edit', compact('announcement', 'users'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Announcement  $announcement
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Announcement $announcement)
    {
        $data = $request->all();
        // dd($request->all());

        $rules = [
            // 'user_id'=>"required|exists:users,id",
            'Category'=>"required|exists:categories,id",
            'City'=>"required|exists:cities,id",
            'price'=>"required",
        ];

        foreach (config("laravellocalization.supportedLocales") as $key => $lang) {
            // Rules
            // $rules["$key.title"] = "string|max:255";
            // Lang
            $langAttr["title"][$key] = $data[$key]['title'] ?? $data[app()->getLocale()]['title'];
            $langAttr["description"][$key] = $data[$key]['description'] ?? $data[app()->getLocale()]['description'];
        }
        $request->validate($rules);

        try {
            DB::beginTransaction();
            $user = User::find($request->user_id);
            $announcement->update([
                // "user_id"=> $request->user_id,
                "store_id"=> $user->store->id ?? null,
                "type"=>$request->type,
                "category_id"=>$request->Category,
                "city_id"=>$request->City,
                "title"=>$langAttr["title"],
                "description"=>$langAttr["description"],
                "is_featured"=>$request->is_featured ?? 0,
                "price"=>$request->price,
                "status"=>$request->status ?? 0,
            ]);

            if ($request->announcement_attributes) {
                foreach ($request->announcement_attributes as $value) {
                    $announcement_attr = AnnouncementAttribute::where('name', $value['attr'])->first();
                    $announcement_attr->update([
                    "name" => $value['attr'],
                    "value" => $value['value'],
                    "announcement_id" => $announcement->id,
                    ]);
                }
            }

            $uploaded_files=json_decode($request["fileuploader-list-file"]);
            if (count($uploaded_files)) {
                $attachments=[];
                foreach ($uploaded_files as $uploaded_file) {
                    array_push($attachments, $uploaded_file->file);
                }
                $attachments = implode(',', $attachments);
                $announcement->update(['images' => $attachments]);
            }
            DB::commit();
            flash()->success('تم التعديل بنجاح', 'عملية ناجحة');
            return redirect()->route('admin.announcements.index');
        } catch (\Exception $e) {
            DB::rollback();
            flash()->success($e->getMessage(), 'عملية فاشلة');
            return back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Announcement  $announcement
     * @return \Illuminate\Http\Response
     */
    public function destroy(Announcement $announcement)
    {
        $announcement->delete();
        flash()->success('تم الحذف بنجاح');
        return redirect()->route('admin.announcements.index');
    }
}
