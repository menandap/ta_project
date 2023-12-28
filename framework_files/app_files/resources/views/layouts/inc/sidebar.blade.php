<div class="sidenav-header">
  <i class="fas fa-times p-3 cursor-pointer text-dark opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
  <a class="navbar-brand m-0 d-flex justify-content-center" href="">
    <img src="/dashboard/images/deploytools.svg" class="navbar-brand-img h-100" alt="main_logo">
  </a>
</div>
<hr class="horizontal light pt-0">
  <div class="w-auto " id="sidenav-collapse-main">
    <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link text-dark {{ Request::is('mydashboard*') ? 'active bg-gradient-info' : '' }} " href="/mydashboard">
        <div class="text-dark text-center me-2 d-flex align-items-center justify-content-center">
          <i class="material-icons opacity-10">dehaze</i>
        </div>
        <span class="nav-link-text ms-1">The Dashboard</span>
      </a>
    </li>
    </ul>
  </div>
</hr>
<hr class="horizontal light mt-0">
  <div class="w-auto " id="sidenav-collapse-main">
    <ul class="navbar-nav">
    <a class="pt-4 d-flex justify-content-center" href="">
      <span class="nav-link-text">Master Data</span>
    </a>
    <li class="nav-item">
      <a class="nav-link text-dark {{ Request::is('jenkins*') ? 'active bg-gradient-info' : '' }} " href="/jenkins">
        <div class="text-dark text-center me-2 d-flex align-items-center justify-content-center">
          <i class="material-icons opacity-10">dashboard</i>
        </div>
        <span class="nav-link-text ms-1">Jenkins</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link text-dark {{ Request::is('docker*') ? 'bg-gradient-info' : '' }} " href="/docker">
        <div class="text-dark text-center me-2 d-flex align-items-center justify-content-center">
          <i class="material-icons opacity-10">cloud</i>
        </div>
        <span class="nav-link-text ms-1">Docker</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link text-dark {{ Request::is('server*') ? 'active bg-gradient-info' : '' }} " href="/server">
        <div class="text-dark text-center me-2 d-flex align-items-center justify-content-center">
          <i class="material-icons opacity-10">computer</i>
        </div>
        <span class="nav-link-text ms-1">Server</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link text-dark {{ Request::is('template*') ? 'active bg-gradient-info' : '' }} " href="/template">
        <div class="text-dark text-center me-2 d-flex align-items-center justify-content-center">
          <i class="material-icons opacity-10">event_note</i>
        </div>
        <span class="nav-link-text ms-1">Project Template</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link text-dark {{ Request::is('masterjobs*') ? 'active bg-gradient-info' : '' }} " href="/masterjobs">
        <div class="text-dark text-center me-2 d-flex align-items-center justify-content-center">
          <i class="material-icons opacity-10">history</i>
        </div>
        <span class="nav-link-text ms-1">Master Jobs</span>
      </a>
    </li>
    </ul>
  </div>
</hr>
<hr class="horizontal light pt-0">
  <div class="w-auto " id="sidenav-collapse-main">
    <ul class="navbar-nav">
    <a class="d-flex justify-content-center" href="">
      <span class="nav-link-text">Data Project</span>
    </a>
    <li class="nav-item">
      <a class="nav-link text-dark {{ Request::is('project*') ? 'active bg-gradient-info' : '' }} " href="/project">
        <div class="text-dark text-center me-2 d-flex align-items-center justify-content-center">
          <i class="material-icons opacity-10">storage</i>
        </div>
        <span class="nav-link-text ms-1">My Project</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link text-dark {{ Request::is('jobst*') ? 'active bg-gradient-info' : '' }} " href="/jobs">
        <div class="text-dark text-center me-2 d-flex align-items-center justify-content-center">
          <i class="material-icons opacity-10">timer</i>
        </div>
        <span class="nav-link-text ms-1">Project Jobs</span>
      </a>
    </li>
    </ul>
  </div>
</hr>
    
    