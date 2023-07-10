@extends('layouts.admin')
@section('content')
<div class="col-12 p-3">
	<div class="col-12 col-lg-12 p-0 ">


		<form id="validate-form" class="row" enctype="multipart/form-data" method="POST" action="{{route('admin.categories.update',$category)}}">
            @csrf
            @method("PUT")


            <div class="col-12 col-lg-6 p-0 main-box">
                <div class="col-12 px-0">
                    <div class="col-12 px-3 py-3">
                        <span class="fas fa-info-circle"></span>	إضافة جديد
                    </div>
                    <div class="col-12 divider" style="min-height: 2px;"></div>
                </div>
                <div class="col-12 p-3 row">
                    <div class="col-12 p-2">
                        <div class="col-12">
                            القسم الرئيسي
                        </div>
                        <div class="col-12 pt-3">
                            <select name="parent_id" class="form-control">
                                <option value="">هذا قسم رئيسي</option>
                                @foreach (\App\Models\Category::where('parent_id', null)->get() as $category)
                                    <option value="{{ $category->id }}">{{ $category->title }}</option>
                                @endforeach
                            </select>
                            <span>اختار هذا اذا كان القسم فرعي واتركه اذا كان رئيسي</span>
                        </div>
                    </div>
                    <div class="col-12 p-2">
                        <div class="col-12">
                            العنوان
                        </div>
                        <div class="col-12 pt-3">
                            <input type="text" name="title" required maxlength="190" class="form-control" value="{{$category->title}}" >
                        </div>
                    </div>
                    <div class="col-12  p-2">
                        <div class="col-12">
                            الوصف
                        </div>
                        <div class="col-12 pt-3">
                            <textarea name="description" class="form-control" style="min-height:150px">{{$category->description}}</textarea>
                        </div>
                    </div>
                </div>

            </div>
            <div class="col-12 col-lg-6 p-0 mt-sm-3">
                <div class="repeater text-center">
                    <h4>خصائص اخرى</h4>
                    <div data-repeater-list="category_attributes" class="px-2">
                        @foreach ($category->attributes as $attr)
                        <div data-repeater-item>
                            <input hidden type="hidden" name="id" value="{{ $attr->id }}" />
                            <input class="form-control mb-2 d-inline-block w-75" type="text" name="attr" placeholder="اسم الحقل المطلوب" value="{{ $attr->name }}" />
                            <input data-repeater-delete type="button" class="btn btn-danger mb-2" value="حذف"/>
                        </div>
                        @endforeach
                    </div>

                    <button class="btn btn-primary" type="button" data-repeater-create>اضافة</button>
                </div>
            </div>

            <div class="col-12 p-3 mt-5">
                <button class="btn btn-success btn-block" id="submitEvaluation">حفظ</button>
            </div>
		</form>
	</div>
</div>
@endsection
@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.repeater/1.2.1/jquery.repeater.min.js"></script>
<script>
    $('.repeater').repeater({
        initEmpty: false,
    });
</script>
@endsection
