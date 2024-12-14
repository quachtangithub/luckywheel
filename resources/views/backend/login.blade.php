<!DOCTYPE html>
<html lang="en" style="height: auto;">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="refresh" content="{{Cache::get('page_reloading_time')}}">
        <title>Bệnh viện SIS</title>
        <link rel="stylesheet" href="{{asset('public/css/backend.css')}}">
        <link href="{{asset('public/libs/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
        <script src="{{asset('public/libs/jquery/jquery.min.js')}}"></script>
        <script src="{{asset('public/libs/bootstrap/js/bootstrap.min.js')}}"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    </head>
    <body>
        <div class="container">
            <div class="row">
                @if(Session::has('status'))
                    <div class="col-md-12 text-center">
                        <div class="alert alert-danger text-danger">
                            {{ Session::get('status') }}
                            @php
                                Session::forget('status');
                            @endphp
                        </div>
                    </div>
                @endif
                <div class="col-md-6 offset-md-3">
                    <h2 class="text-center login_title">TRÒ ĐÙA CỦA TẠO HÓA</h2>
                    <div class="card my-5">
                        <form class="card-body cardbody-color p-lg-5" action="{{route('login')}}" method="POST">
                            {{ csrf_field() }}
                            <div class="text-center">
                                <img src='{{asset("public/images/login_image.png")}}' 
                                    class="img-fluid profile-image-pic img-thumbnail rounded-circle my-3"
                                    width="200px" alt="profile">
                            </div>
                            <input type="hidden" name="redirect_url" value="{{$redirect_url ?? ''}}" />
                            <div class="mb-3">
                                <input type="text" class="form-control" id="email" name="email" aria-describedby="emailHelp"
                                    placeholder="Tên đăng nhập ...">
                                @if ($errors->has('email'))
                                    <span class="text-danger">{{ $errors->first('email') }}</span>
                                @endif
                            </div>
                            <div class="mb-3">
                                <input type="password" class="form-control" id="password" name="password" placeholder="Mật khẩu ...">
                                @if ($errors->has('password'))
                                    <span class="text-danger">{{ $errors->first('password') }}</span>
                                @endif
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-warning btn-color">ĐĂNG NHẬP</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>