<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="{{ asset('assets/EG .png') }}" type="image/x-icon">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <title>Login</title>


    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
        integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

    <style>
        .nav-small {
            padding: 5px 0px;
            background-color: #418165;
            color: #fff;
        }

        .nav-edit {
            background-color: #418165;

        }

        .btn-theme {
            background-color: #418165;
        }

        .footer {
            padding: 20px 0px;
            background-color: #418165;
        }

        .list {
            list-style-type: none;
            margin-left: 90px;
            font-size: 20px;
            text-decoration: none;
        }

        .list-ref {
            text-decoration: none;
            color: #fff
        }
    </style>


</head>

<body>
    <div class="nav-small">
        <div class="container">
            <div class="row ">
                <div class="col-md-6">
                    <a class="text-white" style="text-decoration: none" href="call:9843521965"><i class="fa fa-phone">
                        </i> 9843521965</a> |
                    <a class="text-white" style="text-decoration: none" href="mailto:egtechsolution1@gmail.com">
                        <i class="fa fa-envelope "></i>
                        egtechsolution1@gmail.com
                    </a>
                </div>
            </div>
        </div>

    </div>
    <div class="container mt-3 mb-3">
        <div class="row">

                <div class="col-md-3 animate_animated animate__heartBeat">
                    <a href="/">
                    <img src="{{ asset('assets/eg tech logo updated.png') }}" alt="Logo" class="img-fluid "
                        style="max-width: 100%">
                </div>
            </a>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-6">

            </div>

        </div>
    </div>

  <div class="container">
    <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-6 text-Success ">
            <h2>Account Created Successfully !!</h2>
            <div class="col-md-6  text-end mb-5">
                <a href="{{url('/login')}}" class="btn btn-danger btn-theme " style="border-radius: 20px">
                    <i class="fa fa-home" aria-hidden="true"></i> Login
                </a>
            </div>
        </div>

        <div class="col-md-3"></div>
    </div>
  </div>


    <div class="copyright" style="background-color: #418165;">
        <div class="row ">
            <div class="col-md-12 text-center text-white animate_animated animate__heartBeat"
                style="margin-bottom: 10px">
                <strong>
                    EG Tech Solution
                    <i class="fa fa-copyright" aria-hidden="true"></i>
                    2023.All Right Reserved.
                </strong>
            </div>
        </div>
    </div>
    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>

    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"
        integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"
        integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous">
    </script>
    -->
</body>

</html>
