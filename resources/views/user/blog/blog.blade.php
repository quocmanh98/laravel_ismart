@extends('layouts.user')
@section('title')
    @if (empty($slug))
        Tin mới
    @else
        {{ $catName->name }}
    @endif
@endsection
@section('main_class', 'blog-page')
@section('content')
<div class="secion" id="breadcrumb-wp">
    <div class="secion-detail">
        <ul class="list-item clearfix">
            <li>
                <a href="{{ route('user.index') }}" title="">Trang chủ</a>
            </li>
            <li>
                <a href="{{ route('user.blog') }}" title="">Blog</a>
            </li>
            @if (!empty($slug))
                <li>
                    <a href="{{ route('user.blog', $slug) }}" title="">{{ $catName->name }}</a>
                </li>
            @endif
        </ul>
    </div>
</div>
<div class="main-content fl-right">
    <div class="section" id="list-blog-wp">
        <div class="section-head clearfix">
            <h3 class="section-title">Blog</h3>
        </div>
        <div class="section-menu">
            <ul class="nav nav-tabs">
                @foreach ($catPosts as $key => $item)
                    <li class="nav-item">
                        <a class="nav-link {{ $item['slug'] == $slug ? 'active' : '' }}"
                            href="{{ route('user.blog', ['slug' => $item['slug']]) }}">{{ $item['name'] }}</a>
                    </li>
                @endforeach
            </ul>
        </div>
        <div class="section-detail">
            <ul class="list-item blog-main">
                @foreach ($posts as $item)
                    @php
                        $slugCate = $item->category->slug;
                        $param_url = [
                            'slugCate' => $slugCate,
                            'slugPost' => $item->slug,
                        ];
                    @endphp
                    <li class="clearfix">
                        <a href="{{ route('user.postDetail', $param_url) }}" title="" class="thumb fl-left">
                            <img src="{{ asset($item->image_post) }}" alt="{{ $item->title }}">
                        </a>
                        <div class="info fl-right">
                            <a href="{{ route('user.postDetail', $param_url) }}" title=""
                                class="title">{{ $item->title }}</a>
                            <span class="create-date">{{ date('d/m/Y', strtotime($item->created_at)) }}</span>
                            <p class="desc">Trong ngày hôm nay (11/11) đoàn kiều bào đã tổ chức thành 4 nhóm đi tham
                                quan
                                các điểm như huyện Cần Giờ, Đại học Quốc gia, Khu công nghệ cao TP.HCM, Công viên
                                phần mềm
                                Quang Trung, Khu Nông nghiệp Công nghệ cao, Khu Đô thị mới Thủ Thiêm, Cảng Cát
                                Lái... để
                                kiều bào hiểu thêm về tình hình phát [...]</p>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
    <div class="section" id="paging-wp">
        <div class="section-detail">
            {{-- <ul class="list-item clearfix">
            <li>
                <a href="" title="">1</a>
            </li>
            <li>
                <a href="" title="">2</a>
            </li>
            <li>
                <a href="" title="">3</a>
            </li>
        </ul> --}}
            @if (!empty($slug))
                {{ $posts->links() }}
            @endif
        </div>
    </div>
</div>
@include('user.components.sidebar')
@endsection
