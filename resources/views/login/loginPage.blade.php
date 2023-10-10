@extends('schools.layout.loginlayout')

@section('title', 'Login Page')

@section('content')

<div id="loginContainer">

    <div class="loginFrame row center">
        <div class="col s12 offset-m2 m8 center" id="">
            <div class="">
                <h4 class="center">Login</h4>
                <form>
                    <div class="input-container">
                        <input type="text" name="username" id="username" placeholder="USERNAME" />
                    </div>

                    <div class="input-container">
                        <input type="password" name="password" id="password" placeholder="PASSWORD" />
                    </div>

                    <div class="center">
                        <button class="btn btn-large blue accent-3" type="submit" name="submit" id="loginSubmit">LOG IN</button>
                    </div>
                    <div class="progress hide colCode">
                        <div class="indeterminate"></div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<script type="module" src="{{asset('assets/js/loginManager.js')}}"></script>
@endsection