@extends('layouts.admin')
@section('title', 'Danh sách màu của sản phẩm')
@section('content')
<div id="content" class="container-fluid">
    <div class="row">
        <div class="col-4">
            <div class="card">
                <div class="card-header font-weight-bold">
                    Thêm hoặc cập nhật màu
                </div>
                <div class="card-body">
                    <form action="" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="name_color">Màu sản phẩm</label>
                            <input type="text" class="form-control @error('name_color') is-invalid @enderror"
                                name="name_color" placeholder="Nhập màu của sản phẩm">
                            @error('name_color')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Ảnh về màu sản phẩm</label>
                            <input id="img" type="file" name="image_color_path" class="form-control d-none"
                                onchange="changeImg(this)">
                            <img id="avatar" class="img-thumbnail d-block" width="250px"
                                src="{{ asset('public/admins/img/upload_img.png') }}">
                            @error('image_color_path')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="">Trạng thái</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="status" value="1" checked>
                                <label class="form-check-label" for="exampleRadios1">
                                    Công khai
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="status" value="0">
                                <label class="form-check-label" for="exampleRadios2">
                                    Chờ duyệt
                                </label>
                            </div>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Thêm mới">
                    </form>
                </div>
            </div>
        </div>
        <div class="col-8">
            <div class="card">
                <div class="card-header font-weight-bold">
                    Danh sách màu và ảnh sản phẩm
                </div>
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif
                    <div class="analytic">
                        <a href="{{ request()->fullUrlWithQuery(['status' => 'all']) }}" class="text-primary">Tất cả
                            <span class="text-muted">({{ $count['all'] }})</span></a>
                        <a href="{{ request()->fullUrlWithQuery(['status' => 'public']) }}" class="text-primary">Công
                            khai<span class="text-muted">({{ $count['public'] }})</span></a>
                        <a href="{{ request()->fullUrlWithQuery(['status' => 'pending']) }}" class="text-primary">Chờ
                            duyệt<span class="text-muted">({{ $count['pending'] }})</span></a>
                        <a href="{{ request()->fullUrlWithQuery(['status' => 'trash']) }}" class="text-primary">Vô
                            hiệu hóa<span class="text-muted">({{ $count['trash'] }})</span></a>
                    </div>
                    <form action="{{ route('color.action', ['id' => $product_id]) }}">
                        <div class="form-action form-inline py-3">
                            <select class="form-control mr-1" id="" name="act">
                                <option value="">Chọn</option>
                                @foreach ($list_act as $key => $val)
                                    <option value="{{ $key }}">{{ $val }}</option>
                                @endforeach
                            </select>
                            <input type="submit" name="btn-search" value="Áp dụng" class="btn btn-primary">
                        </div>
                        <table class="table table-striped table-checkall">
                            <thead>
                                <tr>
                                    <th scope="col">
                                        <input name="checkall" type="checkbox">
                                    </th>
                                    <th scope="col">#</th>
                                    <th scope="col">Màu sản phẩm</th>
                                    <th scope="col" width="14%">Hình ảnh</th>
                                    <th scope="col" class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($productColors->count() > 0)
                                    @php
                                        $t = 1;
                                    @endphp
                                    @foreach ($productColors as $item)
                                        <tr>
                                            <td>
                                                <input type="checkbox" name="list_check[]" value="{{ $item->id }}">
                                            </td>
                                            <td scope="row">{{ $t++ }}</td>
                                            <td>{{ $item->color->name }}</td>
                                            <td><img class="img-thumbnail" src="{{ asset($item->image_color_path) }}"
                                                    alt=""></td>
                                            <td class="text-center">
                                                @if (request()->status != 'trash')
                                                    <a href="{{ route('color.delete', ['id' => $item->id]) }}"
                                                        onclick="return confirm('Bán có chắc chắn muốn xóa tạm bản ghi này')"
                                                        class="btn btn-danger btn-sm rounded-0" data-toggle="tooltip"
                                                        title="Delete"><i class="fa fa-trash"></i></a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="5">Không có bản ghi nào</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </form>
                    {{ $productColors->links() }}
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
