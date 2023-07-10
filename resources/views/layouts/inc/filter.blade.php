<div class="container mt-2">
    <div class="card rad14 border-0 py-2 bg-transparent">
        <div class="row py-2 align-items-center">
            <form class="row" style="overflow: hidden;width:max-content;" action="" method="get">
                <div class="col-12 col-lg-5 py-2 mx-1 rounded-3 mb-2 mb-lg-0 border bg-white ">
                    <ul class="nav rad14 border-0 py-2 bg-transparent">
                        <li class="form-check col">
                            <input class="form-check-input" {{ request()->is_featured ? 'checked' : '' }} name="is_featured"
                                type="checkbox" value="true" id="is_featured">
                            <label class="form-check-label" for="is_featured">
                                {{ __('lang.is_featured') }}
                            </label>
                        </li>
                        <li class="form-check col">
                            <input class="form-check-input" {{ request()->with_photos ? 'checked' : '' }} name="with_photos"
                            type="checkbox" value="true" id="with_photos">
                        <label class="form-check-label" for="with_photos">
                            {{ __('lang.photos') }}
                        </label>
                        </li>
                        <li class="form-check col">
                            <input class="form-check-input" {{ request()->with_price ? 'checked' : '' }} name="with_price"
                            type="checkbox" value="true" id="with_price">
                        <label class="form-check-label" for="with_price">
                            {{ __('lang.price') }}
                        </label>
                        </li>
                    </ul>
                </div>

                <div class="col-12 col-lg-5 py-2 mx-1 rounded-3 border bg-white">
                    <h6 class="d-inline-block">{{ __('lang.price') }} </h6>
                    <label for="from" class="w-25">
                        <input class="text-start rounded-2 py-1 px-3 form-control" placeholder="{{ __('lang.from') }}" type="number" name="from" value="{{ request()->from }}">
                    </label>

                    <label for="to" class="w-25">
                        <input class="text-start rounded-2 py-1 px-3 form-control" placeholder="{{ __('lang.to') }}" type="number" name="to" value="{{ request()->to }}">
                    </label>
                    <label>
                        <button
                            class="btn btn-sm badge d-inline-block py-2 px-4 font-2 mainBgColor">{{ __('lang.Search') }}</button>
                    </label>
                </div>
                {{-- <label>
                    <input class="btn badge d-inline-block py-2 px-4 font-2 mainBgColor" type="reset" value="{{ __('lang.Reset Filters') }}">
                </label> --}}

            </form>
        </div>
    </div>
</div>
