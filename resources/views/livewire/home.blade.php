<div class="container-fluid">
    <div class="row my-3">
        <div class="col-9">
            @if($show_subCatrgories !== 1 && $show_Catrgories == 1 && $show_products !== 1 && $step == 1)
            <div class="w3l-gallery2">
                <div class="burger galleryks py-3">
                    <div class="container py-lg-3 py-md-3">
                        <div class="row no-gutters masonry swiper swiper_m">
                            <h3>القسم الرئيسي</h3>
                            {{-- <div class=""> --}}
                                <div class="swiper-wrapper" aria-live="polite">
                                    @foreach ($categories as $category)
                                    <div class="col-4 col-md-2 swiper-slide">
                                        <a wire:click="subCat({{ $category->id }})" class="js-img-viwer d-block text-center">
                                            <img src="{{ $category->image() }}" class="img-fluid radius-image-full" alt="burger gallery" />
                                            {{-- <div class="content-overlay"></div> --}}
                                            {{-- <div class="content-details"> --}}
                                                <h4>{{ $category->title }}</h4>
                                            {{-- </div> --}}
                                        </a>
                                    </div>
                                    @endforeach
                                </div>
                            {{-- </div> --}}
                        </div>
                    </div>
                </div>
            </div>
            @endif
            @if ($subCatrgories && $show_subCatrgories == 1 && $show_Catrgories !== 1 && $show_products !== 1 && $step == 2)
            <div class="container">
                <a wire:click="step(1)" class="btn btn-success text-light">< Back</a>
            </div>
            <div class="w3l-gallery2">
                <div class="burger galleryks py-3">
                    <div class="container py-lg-3 py-md-3">
                        <div class="row no-gutters masonry swiper swiper_m">
                            {{-- <div class=""> --}}
                                <h3>القسم الفرعي</h3>
                                <div class="swiper-wrapper" aria-live="polite">
                                    @foreach ($subCatrgories as $category)
                                    <div class="col-4 col-md-2 swiper-slide">
                                        <a wire:click="subCat({{ $category->id }})" class="js-img-viwer d-block text-center">
                                            <img src="{{ $category->image() }}" class="img-fluid radius-image-full" alt="burger gallery" />
                                            {{-- <div class="content-overlay"></div> --}}
                                            {{-- <div class="content-details fadeIn-top"> --}}
                                                <h4>{{ $category->title }}</h4>
                                            {{-- </div> --}}
                                        </a>
                                    </div>
                                    @endforeach
                                </div>
                            {{-- </div> --}}
                        </div>
                    </div>
                </div>
            </div>
            @endif

            {{-- Products --}}
            @if ($products && $show_subCatrgories !== 1 && $show_Catrgories !== 1 && $show_products == 1 && $step == 3)
            <div class="container">
                <a wire:click="step(2)" class="btn btn-success text-light">< Back</a>
            </div>
            <div class="w3l-gallery2">
                <div class="burger galleryks py-3">
                    <div class="container py-lg-3 py-md-3">
                        <div class="row no-gutters masonry swiper swiper_m_product">
                            {{-- <div class=""> --}}
                                <h3>المنتجات</h3>
                                <div class="swiper-wrapper" aria-live="polite">
                                    @php
                                    //  $products = [1,2,3,4,5,6,7,8,9,10]
                                    @endphp
                                    @foreach ($products as $product)
                                    {{-- <div class="col-lg-6">                                            <!-- Item starts --> --}}
                                        <div class="menu-item text-right swiper-slide">
                                            <div class="row border-dot no-gutters text-right">
                                                <div class="col-8 menu-item-name text-right">
                                                    <h6>{{ $product->title }}</h6>
                                                </div>
                                                <div class="col-4 menu-item-price">
                                                    <h6>{{ $product->price }}</h6>
                                                </div>
                                            </div>
                                            <div class="menu-item-description">
                                                @php echo $product->description; @endphp
                                            </div>
                                            <button wire:click="addTocart({{ $product }})" class="btn btn-success">اضافة</button>
                                        </div>
                                        <!-- Item ends -->
                                    {{-- </div> --}}
                                    @endforeach
                                </div>
                            {{-- </div> --}}
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
        <div class="col-3" id="print">
            <div class="container text-center card bg-dark py-5">
                <div class="row">
                    <div class="col-12">
                        <h2 class="mb-2">{{ $settings->website_name }}</h2>
                    </div>
                    <div class="col-6">
                        <h6 class="mb-2">الرقم الضريبى </h6>
                    </div>
                    <div class="col-6">
                        <span class="">123-456-789</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <h6 class="mb-2">رقـم الفــاتـورة </h6>
                    </div>
                    <div class="col-6">
                        <span class="">#123456</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <h6 class="mb-2">تاريخ الفاتوره </h6>
                    </div>
                    <div class="col-6">
                        <span class="mb-2 mx-2">{{ date('d/m/Y') }}</span><span class="mb-2 mx-2"> {{ date('h:i:s') }}</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <h6 class="mb-2">العـميل</h6>
                    </div>
                    <div class="col-6">
                        <span class="d-inline-block mb-2 mx-2">{{ $c_table }}</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-4">
                        <h6 class="mb-2">الكـاشـير</h6>
                    </div>
                    <div class="col-4">
                        <span>كـاشـير 1</span>
                    </div>
                    <div class="col-4">
                        <span class="d-inline-block mb-2 mx-2">سفرى</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <h3>رقم الطلب</h3>
                    </div>
                    <div class="col-12">
                        <h3 class="">55</h3>
                    </div>
                </div>

                <table class="table mt-3 table-dark" >
                    <thead>
                        <tr>
                            <th>الصنف</th>
                            <th>السعر</th>
                            <th>الكمية</th>
                            <th>الاجمالي</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cartProducts as $product)
                        <tr>
                            <td>
                                <div class="product-name">
                                    <a href="#">
                                        <h6>{{ $product['title'] }}</h6>
                                    </a>
                                </div>
                            </td>
                            <td>{{ $product['price'] }}</td>
                            <td>
                                {{-- <fieldset class="qty-box"> --}}
                                <div class="input-group">
                                    <input type="number" wire:change="qtChange({{ $product['id'] }}, $event.target.value)" value="{{ $product['qt'] }}" class="form-control">
                                </div>
                                {{-- </fieldset> --}}
                            </td>
                            <td>{{ $product['price'] * $product['qt'] }}</td>
                            <td><i data-feather="x-circle"></i></td>
                        <tr>
                            @php
                                $price[] = $product['price'] * $product['qt'];
                            @endphp
                        @endforeach
                    </tbody>
                </table>
                <div class="row mb-2">
                    <div class="col-8">
                        الاجمالى قبل الضريبه
                    </div>
                    <div class="col-4">
                        {{ $cartProducts ? array_sum($price) : 0 }} جنية
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col-8">
                        ضريبة القيمه المضافه 15%
                    </div>
                    <div class="col-4">
                        {{ ($cartProducts ? array_sum($price) : 0) * 0.15 }} جنية
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col-8">
                        <h3>الاجمالى</h3>
                    </div>
                    <div class="col-4">
                        <h5>{{ ($cartProducts ? array_sum($price) : 0) + (($cartProducts ? array_sum($price) : 0) * 0.15) }} جنية</h5>
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col-4">
                        مستلم : 40
                    </div>
                    <div class="col-4">
                        باقي : 7
                    </div>
                    <div class="col-4">
                        نقدي 40
                    </div>
                </div>
                <div class="row">
                    <div class="col-3">
                        <a class="btn btn-success cart-btn-transform" href="#">حفظ</a>
                    </div>
                    <div class="col-3">
                        <a class="btn btn-primary cart-btn-transform" onclick="print();" href="#">طباعة</a>
                    </div>
                    <div class="col-3">
                        <a class="btn btn-light cart-btn-transform" href="#">مسودة</a>
                    </div>
                    <div class="col-3">
                        <a class="btn btn-danger cart-btn-transform" href="#">حذف</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
