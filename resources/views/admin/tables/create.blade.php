@extends('layouts.admin')
@section('content')
<div class="col-12 p-3">
	<div class="col-12 col-lg-12 p-0 ">


		<form id="validate-form" class="row" method="POST" action="{{route('admin.tables.store')}}">
		@csrf

		<div class="col-12 col-lg-8 p-0 main-box">
			<div class="col-12 px-0">
				<div class="col-12 px-3 py-3">
				 	<span class="fas fa-info-circle"></span>	إضافة جديد
				</div>
				<div class="col-12 divider" style="min-height: 2px;"></div>
			</div>
			<div class="col-12 p-3 row">
                <div class="col-12 col-lg-6 p-2">
                    <div class="col-12">
                        الحالة
                    </div>
                    <div class="col-12 pt-3">
                        <select class="form-control select2-select" name="status" required size="1" style="height:30px;opacity: 0;">
                            <option value="0" @if(old('status')==0) selected @endif>فارغ/متاح</option>
                            <option value="1" @if(old('status')==1) selected @endif>محجوز/ممتلئ</option>
                        </select>
                    </div>
                </div>
                <div class="col-12 col-lg-6 p-2">
                    <div class="col-12">
                        عدد المراد اضافته
                    </div>
                    <div class="col-12 pt-3">
                        <input type="number" class="form-control" name="table_number" required value="1">
                    </div>
                </div>
				<div class="col-12 col-lg-6 p-2">
					<div class="col-12">
						الاسم
					</div>
					<div class="col-12 pt-3">
						<input type="text" name="name" required   maxlength="190" class="form-control" value="{{old('title')}}" >
					</div>
				</div>
			</div>

		</div>

		<div class="col-12 p-3">
			<button class="btn btn-success" id="submitEvaluation">حفظ</button>
		</div>
		</form>
	</div>
</div>
@endsection
