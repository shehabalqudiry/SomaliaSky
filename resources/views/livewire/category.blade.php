<div class="row p-0">
    <div class="col-12 col-lg-6 my-2">
        <div class="col-12">
            {{ __('lang.Select Category') }}
        </div>
        <div class="col-12 pt-2">
            <select class="form-control rounded-3" wire:change="subCategory()" wire:model="category_id" name="category_id"
                required>
                <option value="" selected>{{ __('lang.Select Category') }}</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" @if (old('category_id') == $category->id) selected @endif>
                        {{ $category->title }}</option>
                @endforeach
            </select>
        </div>
    </div>
    @if ($subcategories)
        <div class="col-12 col-lg-6 my-2">
            <div class="col-12">
                {{ __('lang.Select Category') }}
            </div>
            <div class="col-12 pt-2">
                <select class="form-control rounded-3" wire:model="subcategory_id" name="Category" wire:change="att()"
                    required>
                    <option value="" selected>{{ __('lang.Select Category') }}</option>
                    @foreach ($subcategories as $subcategory)
                        <option value="{{ $subcategory->id }}" @if (old('Category') == $subcategory->id) selected @endif>
                            {{ $subcategory->title }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-12 col-lg-12 mt-sm-3 repeater">

            <div data-repeater-list="announcement_attributes" class="py-2 row">
                @forelse ($attributes as $attr)
                    <div data-repeater-item class="col-6">
                        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                            @foreach (config('laravellocalization.supportedLocales') as $key => $lang)
                                <li class="nav-item mx-auto" role="presentation">
                                    <a class="nav-link {{ $loop->first ? 'active' : '' }}"
                                        id="pills2-{{ $key }}-tab" data-bs-toggle="pill"
                                        href="#pills2-{{ $key }}" role="tab"
                                        aria-controls="pills2-{{ $key }}"
                                        aria-selected="true">{{ $lang['native'] }}</a>
                                </li>
                            @endforeach
                        </ul>
                        <div class="tab-content" id="pills2-tabContent">
                            @foreach (config('laravellocalization.supportedLocales') as $key2 => $lang)
                                <div class="tab-pane fade show {{ $loop->first ? 'active' : '' }}"
                                    id="pills2-{{ $key2 }}" role="tabpanel"
                                    aria-labelledby="pills2-{{ $key2 }}-tab">
                                    <label for="attrr">{{ $attr->getTranslation('name', $key2) }}</label>
                                    <input hidden type="hidden" name="attr" value="{{ $attr->id }}" />
                                    <input id="attrr" class="form-control mb-3" type="text"
                                        name="[{{ $key2 }}.value]"
                                        placeholder="{{ $attr->getTranslation('name', $key2) }}"
                                        value="{{ $catAttributes->AnnouncementAttribute[$loop->index]->value ?? '' }}" />


                                </div>
                            @endforeach
                        </div>
                    </div>
                @empty
                @endforelse
            </div>
        </div>
    @endif
</div>
