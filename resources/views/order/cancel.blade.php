@extends('layouts.fr7')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">取消订单</div>

                    <div class="card-body">

                        <div class="alert alert-{{ $type }}" role="alert">
                            <h4 class="alert-heading">{{ $title }}</h4>
                            <hr>
                            <p class="mb-0">{!! $msg !!}</p>
                        </div>


                        <hr />

                        <button type="button" class="btn btn-outline-primary" onclick="window.location.href=document.referrer;">返回</button>


                    </div>

                </div>
            </div>
        </div>
    </div>


@endsection
