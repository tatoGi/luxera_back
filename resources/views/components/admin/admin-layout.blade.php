
<!DOCTYPE html>
<html lang="{{ app()->getlocale() }}">
<head>
    <x-admin.admin-head />
</head>
<body class = "body bg-white dark:bg-[#0F172A]">
  
    <x-admin.admin-sidebar />
    <x-admin.admin-header />

    <div class = "content ml-12 bg-gray-100  transform ease-in-out duration-500 pt-20 px-2 md:px-5 pb-4 ">
       
        <x-admin.admin-navbar />
       
        {{$slot}}
        
    </div>
    
    <x-admin.admin-footer />
    <x-admin.admin-scripts />
</body>
</html>