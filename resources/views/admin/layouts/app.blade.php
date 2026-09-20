<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('role') | Mudhyaf</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&family=Playfair+Display:wght@700&family=Noto+Sans+Arabic:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

    <link rel="stylesheet" href="{{asset('css/admin.css')}}">
</head>

<body class="bg-[#F4F7F6]">

<div class="flex min-h-screen">
    <!-- role Sidebar -->
   @include('admin.layouts.sidebar')

    <!-- Main Dashboard Area -->
    <main class="flex-1 lg:ml-72 min-h-screen">
        <!-- Top Header -->
       @include('admin.layouts.header')

        <div class="p-8">
         @yield('content')
        </div>
    </main>
</div>

</body>
{{-- jQuery --}}
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

{{-- DataTables Core --}}
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

{{-- SweetAlert --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $.fn.dataTable.ext.errMode = 'none';
</script>
<script>

    // ===============================
    // GLOBAL DATATABLE INITIALIZATION
    // ===============================
    $(document).ready(function () {

            $('table').DataTable({
                responsive: true,
                pageLength: 10,
                lengthChange: false,
                ordering: true,
                language: {
                    search: "Search:",
                    paginate: {
                        next: "Next",
                        previous: "Prev"
                    }
                }
            });

    });


    // ===============================
    // CONFIRM DELETE
    // ===============================
    document.addEventListener('click', function (e) {

        if (e.target.closest('.delete-btn')) {

            e.preventDefault();

            let form = e.target.closest('form');

            Swal.fire({
                title: 'Are you sure?',
                text: "This action cannot be undone!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#14532d',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }

    });


    // ===============================
    // GLOBAL SUCCESS & ERROR ALERTS
    // ===============================

    @if(session('success'))
    Swal.fire({
        icon: 'success',
        title: 'Success',
        text: "{{ session('success') }}",
        timer: 3000,
        showConfirmButton: false
    });
    @endif

    @if(session('error'))
    Swal.fire({
        icon: 'error',
        title: 'Error',
        text: "{{ session('error') }}",
    });
    @endif

    @if($errors->any())
    Swal.fire({
        icon: 'error',
        title: 'Validation Error',
        html: `{!! implode('<br>', $errors->all()) !!}`
    });
    @endif

</script>
</html>
