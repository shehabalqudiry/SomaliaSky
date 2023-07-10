<div class="container mt-2">
    <ul class="nav p-2 border rad14 search bg-transparent">
        @php
            $cats = isset($category)
                ? $subcats
                : App\Models\Category::where('parent_id', request()->Category ?? null)
                    ->latest()
                    ->get();
        @endphp
        <li class="nav-item">
            <a href="#" class="nav-link font-2">{{ __('lang.Categories') }}</a>
        </li>
        @foreach ($cats as $category2)
            <li class="nav-item">
                <form class="nav-link" style="overflow: hidden;width:max-content;" action="" method="get"
                    id="form_{{ $category2->id }}">
                    <label for="Category_{{ $category2->id }}">
                        <span class="btn badge text-dark bg-transparent border p-3"
                            style="{{ request()->Category == $category2->id ? 'border:2px solid ' . $settings->main_color . ' !important;' : '' }}">
                            <img src="{{ $category2->image_path }}" width="30" height="20" alt="">
                            {{ $category2->title }}</span>

                        <input hidden id="Category_{{ $category2->id }}"
                            onclick="document.getElementById('form_{{ $category2->id }}').submit();" type="text"
                            readonly name="Category" value="{{ $category2->id }}">
                    </label>
                </form>
            </li>
        @endforeach
    </ul>
</div>
