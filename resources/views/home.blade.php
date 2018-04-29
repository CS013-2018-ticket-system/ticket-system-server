@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-11">
            <div class="card">
                <div class="card-header">欢迎</div>

                <div class="card-body">
                    欢迎 {{ Auth::user()->name }} @ {{ Auth::user()->college }} ! <br />
                    请在菜单选择需要的操作。
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
