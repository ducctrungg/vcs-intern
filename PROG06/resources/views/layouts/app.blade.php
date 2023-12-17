 <!DOCTYPE html>
 <html lang="en">

 <head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
     integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
     integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
   <script src="https://code.jquery.com/jquery-3.7.1.min.js"
     integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
     <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
   <title>PROG06</title>
 </head>

 <body class="text-bg-dark" style="height: 100vh">
   <div class="h-100">
     <div class="container d-flex w-100 h-100 mx-auto flex-column mb-4">
       <header class="d-flex flex-wrap align-items-center justify-content-between py-3 border-bottom text-center">
         <ul class="nav col-12 col-md-auto mb-2 justify-content-center mb-md-0">
           <li><a href="/" class="nav-link px-2">Trang chủ</a></li>
         </ul>
         @auth
           <div class="col-md-3 text-end">
             <span class="pe-3 fw-bold">
               {{ Auth::user()->fullname }}
             </span>
             <img
               src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : asset('storage/avatars/default.jpg') }}"
               class="rounded-circle object-fit-cover" style="width: 40px; height: 40px">
           </div>
         @endauth
       </header>
       @yield('content')
     </div>
   </div>
   <x-flash />
 </body>
 </html>
 <script>
   $().ready(() => {
     setTimeout(() => {
       $("#flashMessage").hide();
     }, 2000);
   });
 </script>
