@extends('layouts.sidebar')

@section('content')
<div class="post_create_container d-flex">
  <div class="post_create_area border w-50 m-5 p-5">
    <div class="">
      @if($errors->first('post_category_id'))
      <span class="error_message">{{ $errors->first('post_category_id') }}</span>
      @endif
      <p class="mb-0">カテゴリー</p>
      <select class="w-100" form="postCreate" name="post_category_id">
        <!-- サブカテゴリー表示 -->
        <option selected disabled>----</option>
        @foreach($main_categories as $main_category)
        <optgroup label="{{ $main_category->main_category }}">
          @foreach($main_category->subCategories as $sub_category)
          <option value="{{ $sub_category->id }}">{{ $sub_category->sub_category }}</option>
          @endforeach
        </optgroup>
        @endforeach
      </select>
    </div>
    <div class="mt-3">
      @if($errors->first('post_title'))
      <span class="error_message">{{ $errors->first('post_title') }}</span>
      @endif
      <p class="mb-0">タイトル</p>
      <input type="text" class="w-100" form="postCreate" name="post_title" value="{{ old('post_title') }}">
    </div>
    <div class="mt-3">
      @if($errors->first('post_body'))
      <span class="error_message">{{ $errors->first('post_body') }}</span>
      @endif
      <p class="mb-0">投稿内容</p>
      <textarea class="w-100" form="postCreate" name="post_body">{{ old('post_body') }}</textarea>
    </div>
    <div class="mt-3 text-right">
      <input type="submit" class="btn btn-primary" value="投稿" form="postCreate">
    </div>
    <form action="{{ route('post.create') }}" method="post" id="postCreate">{{ csrf_field() }}</form>
  </div>
  @can('admin')
  <div class="w-25 ml-auto mr-auto">
    <div class="category_area mt-5 p-5">
      <div class="">
        <p class="m-0">メインカテゴリー</p>
        <input type="text" class="w-100" name="main_category_name" form="mainCategoryRequest">
        <input type="submit" value="追加" class="w-100 btn btn-primary p-0" form="mainCategoryRequest">
      </div>
      @if($errors->has('main_category_name'))
        <div class="alert alert-danger">
        <ul>
            @foreach ($errors->get('main_category_name') as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        </div>
      @endif
      <!-- サブカテゴリー追加 -->
      <label>サブカテゴリー</label>
      <div class="">
        <select name="main_category_id" form="subCategoryRequest" class="w-100">
          <option selected disabled>----</option>
          @foreach($main_categories as $main_category)
          <option value="{{ $main_category->id }}">{{ $main_category->main_category }}</option>
          @endforeach
        </select>
      </div>
      <div class="">
        <input type="text" class="w-100" name="sub_category_name" form="subCategoryRequest">
        <input type="submit" value="追加" class="w-100 btn btn-primary p-0" form="subCategoryRequest">
      </div>
      @if($errors->has('main_category_id') || $errors->has('sub_category_name'))
        <div class="alert alert-danger">
        <ul>
            @foreach ($errors->get('main_category_id') as $error)
                <li>{{ $error }}</li>
            @endforeach
            @foreach ($errors->get('sub_category_name') as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        </div>
      @endif
      <!-- ↓各フォームタグと紐付け -->
      <form action="{{ route('main.category.create') }}" method="post" id="mainCategoryRequest">{{ csrf_field() }}</form>
      <form action="{{ route('sub.category.create') }}" method="post" id="subCategoryRequest">{{ csrf_field() }}</form>
    </div>
  </div>
  @endcan
</div>
@endsection
