
<!DOCTYPE html>



<html lang="en" class="light-style layout-navbar-fixed layout-menu-fixed layout-compact ">

  @include('admin.layouts.header');

<body>

  

  <!-- Layout wrapper -->
<div class="layout-wrapper layout-content-navbar">
  <div class="layout-container">


    <!-- Menu -->
    @include('admin.layouts.side-menu')
    <!-- / Menu -->

    <!-- Layout container -->
    <div class="layout-page">


      <!-- Navbar -->

      @include('admin.layouts.navbar')


       <!-- / Navbar -->

      <!-- Content wrapper -->
      <div class="content-wrapper">

        <!-- Content -->

        @yield('content')
        

        <!-- / Content -->

        <!-- Footer -->
        <footer class="content-footer footer bg-footer-theme">
        <div class="container-xxl">
            <div class="footer-container d-flex align-items-center justify-content-between py-2 flex-md-row flex-column">
            <div>
                © <script>
                document.write(new Date().getFullYear())

                </script>, made with ❤️ by <a href="https://pixinvent.com/" target="_blank" class="footer-link text-primary fw-medium">Pixinvent</a>
            </div>
            
            </div>
        </div>
        </footer>
        <!-- / Footer -->

          
          <div class="content-backdrop fade"></div>
        </div>
        <!-- Content wrapper -->
      </div>
      <!-- / Layout page -->
    </div>

    
    
    <!-- Overlay -->
    <div class="layout-overlay layout-menu-toggle"></div>
    
    
    <!-- Drag Target Area To SlideIn Menu On Small Screens -->
    <div class="drag-target"></div>
    
  </div>
  <!-- / Layout wrapper -->

  

  @include('admin.layouts.footer')
  
</body>


</html>

<!-- beautify ignore:end -->

