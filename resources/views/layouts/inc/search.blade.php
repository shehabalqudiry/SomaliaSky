<div class="container">
    <form action="" method="get">
        <div class="card rad14 search bg-transparent">
            <div class="row py-1">
                <div class="col-12 col-lg-3 mb-3 mb-lg-0">
                    <div class="custom-select">
                        <span class='spanIcon fas fa-th-large font-2'></span>
                        <select name="Category">
                            <option value="">{{ __('lang.Select Category') }}</option>
                            <option value="">{{ __('lang.Select Category') }}</option>
                            @foreach ($categories->where('parent_id', null) as $category)
                                <option {{ request()->Category == $category->id ? 'selected' : '' }}
                                    value="{{ $category->id }}">{{ $category->title }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <livewire:country-city-select :country_id="request()->country" :city_id="request()->city" />
                <div class="col-12 col-lg-3 mb-3 mb-lg-0">
                    <div class="d-flex">
                        <input class="form-control" type="text" autocomplete="off"
                            placeholder="{{ __('lang.Search') }}" value="{{ request()->q }}" name="q">
                        <button class="btn mainBgColor"
                            style="border-radius: {{ app()->getLocale() == 'ar' ? '15px 0 0 15px' : '0 15px 15px 0' }}"
                            type="submit"><span class="fas fa-search"></span></button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
