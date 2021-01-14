<!doctype html>
<html lang="en">
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <title>Quilt</title>
</head>
<body>
<div class="container my-4">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h5>Quilt</h5>
                </div>

                <div class="card-body">
                    <ul class="list-group list-unstyled">
                        <li class="list-group-item d-flex justify-content-between">
                            <h5>Total Products ({{ $total }})</h5>
                            <button type="button" class="btn btn-primary sync">Sync Products</button>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <h5>Duplicate Products ({{ $duplicate }})</h5>
                            <button type="button" class="btn btn-danger delete">Delete Duplicate Products</button>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script></body>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
        @if(Session::has('success'))
        toastr.success("{{ Session::get('success') }}") ;
        @endif

        @if(Session::has('error'))
        toastr.error("{{ Session::get('error') }}") ;
        @endif

        $(document).ready(function () {
            $('.sync').click(function() {
                $(this).html('Loading');
                window.location.href ='store/products';
            });

            $('.delete').click(function() {
                $(this).html('Loading');
                window.location.href ='/duplicate';
            });
        });
    </script>
</html>
