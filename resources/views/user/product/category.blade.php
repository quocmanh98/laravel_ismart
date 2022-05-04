@extends('layouts.user')
@section('title')
    {{ $catProduct->name }}
@endsection
@section('main_class', 'category-product-page')
@section('content')
    <div class="secion" id="breadcrumb-wp">
        <div class="secion-detail">
            <ul class="list-item clearfix">
                <li>
                    <a href="{{ route('user.index') }}" title="">Trang chủ</a>
                </li>
                @if ($catProduct->parent_id == 0)
                    <li>
                        <a href="{{ route('user.category', $catProduct->slug) }}" title="">{{ $catProduct->name }}</a>
                    </li>
                @else
                    <li>
                        <a href="{{ route('user.category', $catProduct->catProductParent->slug) }}"
                            title="">{{ $catProduct->catProductParent->name }}</a>
                    </li>
                    <li>
                        <a href="{{ route('user.category', $catProduct->slug) }}" title="">{{ $catProduct->name }}</a>
                    </li>
                @endif
            </ul>
        </div>
    </div>
    <div class="main-content fl-right">
        <div class="section" id="list-product-wp">
            <div class="section-head clearfix">
                <div class="fl-left">
                    <a href="{{ route('user.category', ['slugCategory' => $catProduct->slug]) }}"
                        class="section-title">{{ $catProduct->name }}</a>
                    @if (!empty($checkBrand) || !empty($checkPrice))
                        <div class="filter-product">
                            <span class="filter-title">Lọc theo: </span>
                            @if (!empty($checkBrand))
                                @foreach ($checkBrand as $item)
                                    <span class="filter-name">{{ $item }}</span>
                                @endforeach
                            @endif
                            @if (!empty($checkPrice))
                                <span class="filter-name">{{ $checkPrice }}</span>
                            @endif
                        </div>
                    @endif
                </div>
                <div class="filter-wp fl-right">
                    <div class="form-filter">
                        <form>
                            <select name="sort">
                                <option>Sắp xếp</option>
                                <option {{ request()->sort == 'a-z' ? 'selected' : '' }} value="a-z">Từ A-Z</option>
                                <option {{ request()->sort == 'z-a' ? 'selected' : '' }} value="z-a">Từ Z-A</option>
                                <option {{ request()->sort == 'high-to-low' ? 'selected' : '' }} value="high-to-low">Giá
                                    cao xuống thấp</option>
                                <option {{ request()->sort == 'low-to-high' ? 'selected' : '' }} value="low-to-high">Giá
                                    thấp lên cao</option>
                            </select>
                            <button type="submit">Lọc</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="section-detail">
                @if ($products->count() > 0)
                    <ul class="list-item clearfix">
                        @foreach ($products as $item)
                            <li class="product-laptop">
                                <a href="{{ route('product.detail', ['slugCategory' => $item->category->catProductParent->slug, 'slugProduct' => $item->slug]) }}"
                                    title="" class="thumb">
                                    <img src="{{ asset($item->feature_image) }}">
                                </a>
                                <a href="{{ route('product.detail', ['slugCategory' => $item->category->catProductParent->slug, 'slugProduct' => $item->slug]) }}"
                                    title="" class="product-name">{{ $item->name }}</a>
                                <div class="price">
                                    <span class="new">{{ number_format($item->price, 0, '', '.') }}đ</span>
                                </div>
                                <div class="action clearfix">
                                    <a href="{{ route('cart.addProduct', ['id' => $item->id]) }}" title="Thêm giỏ hàng"
                                        class="add-cart fl-left"
                                        data-url="{{ route('cart.add', ['id' => $item->id]) }}">Thêm giỏ hàng</a>
                                    <a href="?page=checkout" title="Mua ngay" class="buy-now fl-right">Mua ngay</a>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <div class="not-search clearfix">
                        <img src="{{ asset('public/users/images/noti-search.png') }}" alt="">
                        <p>Rất tiếc chúng tôi không tìm thấy kết quả theo yêu cầu của bạn</p>
                        <p>Vui lòng thử lại .</p>
                    </div>
                @endif
            </div>
        </div>
        <div class="section" id="paging-wp">
            <div class="section-detail">
                {{ $products->links() }}
            </div>
        </div>

        {{-- Modal Add Cart --}}
        @include('user.components.modalProductCart')
        {{-- End Model Add Cart --}}

        {{-- Modal Notitfication --}}
        @include('user.components.modalNotification')
        {{-- endModal Notitfication --}}
    </div>
    @include('user.product.componentSidebar')
@endsection
