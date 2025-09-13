<!doctype html>
<html lang="en">

<head> <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" initial-scale=1"> <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
        integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <title>Admin Panel</title>
</head>

<body>
    <style>
        /* Ensure the sidebar and content area stack on smaller screens */
        @media (max-width: 768px) {
            .sidebar {
                height: auto;
                /* Remove fixed height */
            }

            .sidebar ul {
                padding: 0;
                margin: 0;
            }

            .list {
                padding: 10px 0;
            }

            .list-text {
                display: block;
                margin: 5px 0;
                text-align: center;
            }

            .col-md-2,
            .col-md-10 {
                width: 100%;
                padding: 0;
            }
        }

        /* Style adjustments for smaller screens */
        .list-text {
            display: block;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 10px;
            text-align: center;
            color: #fff;
            background-color: #343a40;
            transition: background-color 0.3s;
        }

        .list-text:hover {
            background-color: #495057;
        }

        /* Adjustments for the logout section */
        .text-end {
            text-align: right;
        }

        @media (max-width: 768px) {
            .text-end {
                text-align: center;
                margin-bottom: 10px;
            }
        }

        /* Ensure the app content is responsive */
        .app {
            padding: 15px;
        }
    </style> <!-- <div class="col-md-2"> <div class="container mt-4"> <div class="row"> <!- Menu goes here -->
    <!-- <ul class="list-group"> <li class="list-group-item d-flex justify-content-between align-items-center"> {{-- <a href="{{ route('brand.index') }}"> Brand</a> --}} <span class="badge bg-primary rounded-pill">14</span> </li> <li class="list-group-item d-flex justify-content-between align-items-center"> Category <span class="badge bg-primary rounded-pill">2</span> </li> <li class="list-group-item d-flex justify-content-between align-items-center"> Product <span class="badge bg-primary rounded-pill">1</span> </li> </ul> </div> <div class="col-md-10"> <div class="app"> @yield('content') </div> </div> </div> </div> -->
    <!--Main Navigation-->
    <header class="mb-10">
        <style>
            .list {
                padding: 5px 0px;
            }

            .list-text {
                color: bisque;
                text-decoration: none;
                background-color: rgb(26, 22, 22);
                border-radius: 10px;
            }

            .text-decoration {
                text-decoration: none;
                color: antiquewhite;
            }
        </style> <!-- Section: Split screen -->
        <section>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-2 bg-dark" style="height: 100vmax;">
                        <div class="Dashboard text-center mt-3 mb-4 text-white ">
                            <h4><a class="text-decoration" href="{{ url('/admin') }}"> <i class="fa fa-user-circle"
                                        aria-hidden="true"></i> <strong> EG Tech</strong></a></h4>
                        </div>
                        <div class="sidebar ">
                            <ul class="list-group text-center text-white ">
                                <li class="list ">
                                    <h5> <a class="list-text card card-body " href="{{ route('brand.index') }}">
                                            Brand</a></h5> {{-- <span class="badge bg-primary rounded-pill">14</span> --}}
                                </li>
                                <li class="list">
                                    <h5> <a class="list-text card card-body " href="{{ route('category.index') }}">
                                            Category</a></h5> {{-- <!-- <span class="badge bg-primary rounded-pill">2</span> --}}
                                </li>
                                <li class="list">
                                    <h5> <a class="list-text card card-body " href="{{ route('product.index') }}">
                                            Product</a></h5>
                                    <h5> <a class="list-text card card-body " href="{{ route('banner.index') }}">
                                            Banner</a></h5> {{-- <!-- <span class="badge bg-primary rounded-pill">1</span> --}}
                                </li>
                                <li class="list"> <a class="list-text card card-body "
                                        href="{{ route('admin.orders') }}"> Order</a> </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-10">
                        <div class="col-md-11 mt-2 text-end"> Welcome, {{ Auth::guard('admin')->user()->name }}! <a
                                href="{{ url('admin/logout') }}"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                Logout </a>
                            <form id="logout-form" action="{{ url('admin/logout') }}" method="POST" class="d-none">
                                @csrf </form>
                        </div>
                        <div class="app mt-3"> @yield('content') </div>
                    </div>
                </div>
            </div>
        </section> <!-- Section: Split screen -->
    </header> <!--Main Navigation--> <!-- Optional JavaScript; choose one of the two! -->
    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script> <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"
        integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous">
    </script> <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"
        integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous">
    </script> -->
</body>

</html>
