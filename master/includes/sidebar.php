<!-- Sidenav (base) -->
    <aside class="aside aside-base sidenav-base">
      <nav class="navbar navbar-expand-xl navbar-vertical">
        <div class="container-lg">
          <!-- Brand -->
          <a class="navbar-brand d-flex align-items-center fs-5 fw-bold px-xl-3 mb-xl-4" href="./index.php">
            <i class="fs-4 text-secondary me-2" data-duoicon="box-2"></i> Bodhitree Group
          </a>
    
          <!-- User -->
          <div class="d-flex ms-auto d-xl-none">
            <div class="dropdown my-n2">
              <a class="btn btn-link d-inline-flex align-items-center dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <span class="avatar avatar-sm avatar-status avatar-status-success me-3">
                  <img class="avatar-img" src="./assets/img/photos/photo-6.jpg" alt="..." />
                </span>
                <span class="d-none d-xl-block"><?php echo $_SESSION['username'] ?></span>
              </a>
              <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="./account/account.html">Account</a></li>
                <li><a class="dropdown-item" href="./auth/password-reset.html" target="_blank">Change password</a></li>
                <li><hr class="dropdown-divider" /></li>
                <li><a class="dropdown-item" href="#">Sign out</a></li>
              </ul>
            </div>
    
            <!-- Divider -->
            <div class="vr align-self-center bg-dark mx-2"></div>
    
            <!-- Notifications -->
            <div class="dropdown ">
              <button class="btn btn-link" type="button" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                <span class="material-symbols-outlined scale-125">notifications</span>
                <span class="position-absolute top-0 end-0 m-3 p-1 bg-warning rounded-circle">
                  <span class="visually-hidden">New notifications</span>
                </span>
              </button>
              <div class="dropdown-menu dropdown-menu-end" style="width: 350px">
                <!-- Header -->
                <div class="row">
                  <div class="col">
                    <h6 class="dropdown-header me-auto">Notifications</h6>
                  </div>
                  <div class="col-auto">
                    <button class="btn btn-sm btn-link" type="button"><span class="material-symbols-outlined me-1">done_all</span> Mark all as read</button>
                    <button class="btn btn-sm btn-link" type="button"><span class="material-symbols-outlined">settings</span></button>
                  </div>
                </div>
            
                <!-- Items -->
                <div class="list-group list-group-flush px-4">
                  <div class="list-group-item border-style-dashed px-0">
                    <div class="row gx-3">
                      <div class="col-auto">
                        <div class="avatar avatar-sm">
                          <img class="avatar-img" src="./assets/img/photos/photo-1.jpg" alt="..." />
                        </div>
                      </div>
                      <div class="col">
                        <p class="text-body mb-2">
                          <span class="fw-semibold">Emily T.</span> commented on your post <br /><small class="text-body-secondary">5 minutes ago</small>
                        </p>
                        <div class="card">
                          <div class="card-body p-3">Love the new dashboard layout! Super clean and easy to navigate 🔥</div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="list-group-item border-style-dashed px-0">
                    <div class="row gx-3">
                      <div class="col-auto">
                        <div class="avatar avatar-sm">
                          <img class="avatar-img" src="./assets/img/photos/photo-2.jpg" alt="..." />
                        </div>
                      </div>
                      <div class="col">
                        <p class="text-body mb-2">
                          <span class="fw-semibold">Michael J.</span> requested changes on your post <br />
                          <small class="text-body-secondary">10 minutes ago</small>
                        </p>
                        <div class="card">
                          <div class="card-body p-3">
                            <p class="mb-2">Could you update the revenue chart with the latest data? Thanks!</p>
                            <p class="mb-0">
                              <button class="btn btn-sm btn-light" type="button">Update now</button>
                              <button class="btn btn-sm btn-link">Dismiss</button>
                            </p>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="list-group-item border-style-dashed px-0">
                    <div class="row gx-3 align-items-center">
                      <div class="col-auto">
                        <div class="avatar">
                          <span class="material-symbols-outlined">error</span>
                        </div>
                      </div>
                      <div class="col">
                        <p class="text-body mb-0">
                          <span class="fw-semibold">System alert</span> - Build failed <br />
                          <small class="text-body-secondary">1 hour ago</small>
                        </p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
    
          <!-- Toggler -->
          <button
            class="navbar-toggler ms-3"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#sidenavBaseCollapse"
            aria-controls="sidenavBaseCollapse"
            aria-expanded="false"
            aria-label="Toggle navigation"
          >
            <span class="navbar-toggler-icon"></span>
          </button>
    
          <!-- Collapse -->
          <div class="collapse navbar-collapse" id="sidenavBaseCollapse">
            <!-- Search -->
            <div class="input-group d-xl-none my-4 my-xl-0">
              <input class="form-control" type="search" placeholder="Search" aria-label="Search" aria-describedby="sidenavBaseSearchMobile" />
              <span class="input-group-text" id="sidenavBaseSearchMobile">
                <span class="material-symbols-outlined">search</span>
              </span>
            </div>
    
            <!-- Nav -->
            <nav class="navbar-nav nav-pills mb-7">
              <div class="nav-item">
                <a
                  class="nav-link active"
                  href="index.php"
                  data-bs-target="#dashboards"
                  role="button"
                  aria-expanded="false"
                  aria-controls="dashboards"
                >
                  <span class="material-symbols-outlined me-3">space_dashboard</span> Dashboards
                </a>
              </div>


              <div class="nav-item">
                <a
                  class="nav-link "
                  href="#"
                  data-bs-toggle="collapse"
                  data-bs-target="#pending_leave_applications"
              
                  aria-expanded="false"
                  aria-controls="pending_leave_applications"
                >
                <span class="material-symbols-outlined me-3">description</span>Leave Applications
                </a>
                <div class="collapse " id="pending_leave_applications">
                  <nav class="nav nav-pills">
                    <a class="nav-link " href="pending_leave_application_list.php">Pending Leave Application List</a>
                    <a class="nav-link " href="approved_list.php">Approved List</a>
                    <a class="nav-link " href="denied_list.php">Denied List </a>
                    <a class="nav-link " href="all_approved_list.php">All Approved Leaves </a>
                  </nav>
                </div>
              </div>


              
              <div class="nav-item">
                <a
                  class="nav-link "
                  href="#"
                  data-bs-toggle="collapse"
                  data-bs-target="#customers"
              
                  aria-expanded="false"
                  aria-controls="customers"
                >
                  <span class="material-symbols-outlined me-3">group</span> Manage Employee
                </a>
                <div class="collapse " id="customers">
                  <nav class="nav nav-pills">
                    <a class="nav-link " href="employee.php">Employee</a>
                    <a class="nav-link " href="assign_approver.php">Assign Approver</a>
                  </nav>
                </div>
              </div>
              
            </nav>
    
          
    
            <!-- Divider -->
            <hr class="my-4 d-xl-none" />
    
            <!-- Nav -->
            <nav class="navbar-nav nav-pills d-xl-none mb-7">
              <div class="nav-item">
                <a class="nav-link" href="https://themes.getbootstrap.com/product/dashbrd/" target="_blank">
                  <span class="material-symbols-outlined me-3">local_mall</span> Go to product page
                </a>
              </div>
              <div class="nav-item">
                <a class="nav-link" href="mailto:yevgenysim+simpleqode@gmail.com">
                  <span class="material-symbols-outlined me-3">alternate_email</span> Contact us
                </a>
              </div>
              <div class="nav-item dropdown">
                <a class="nav-link" href="#" role="button" data-bs-toggle="dropdown" data-bs-settings-switcher aria-expanded="false">
                  <span class="material-symbols-outlined me-3"> settings </span> Settings
                </a>
                <div class="dropdown-menu ">
                  <!-- Color mode -->
                  <h6 class="dropdown-header">Color mode</h6>
                  <a class="dropdown-item d-flex" data-bs-theme-value="light" href="#" role="button"> <span class="material-symbols-outlined me-2">light_mode</span> Light </a>
                  <a class="dropdown-item d-flex" data-bs-theme-value="dark" href="#" role="button"> <span class="material-symbols-outlined me-2">dark_mode</span> Dark </a>
                  <a class="dropdown-item d-flex" data-bs-theme-value="auto" href="#" role="button"> <span class="material-symbols-outlined me-2">contrast</span> Auto </a>
                
                  <!-- Navigation position -->
                  <hr class="dropdown-divider" />
                  <h6 class="dropdown-header">Navigation position</h6>
                  <a class="dropdown-item d-flex" data-bs-navigation-position-value="sidenav" href="#" role="button">
                    <span class="material-symbols-outlined me-2">keyboard_tab_rtl</span> Sidenav
                  </a>
                  <a class="dropdown-item d-flex" data-bs-navigation-position-value="topnav" href="#" role="button">
                    <span class="material-symbols-outlined me-2">vertical_align_top</span> Topnav
                  </a>
                
                  <!-- Sidenav sizing -->
                  <div class="sidenav-sizing">
                    <hr class="dropdown-divider" />
                    <h6 class="dropdown-header">Sidenav sizing</h6>
                    <a class="dropdown-item d-flex" data-bs-sidenav-sizing-value="base" href="#" role="button">
                      <span class="material-symbols-outlined me-2">density_large</span> Base
                    </a>
                    <a class="dropdown-item d-flex" data-bs-sidenav-sizing-value="md" href="#" role="button">
                      <span class="material-symbols-outlined me-2">density_medium</span> Medium
                    </a>
                    <a class="dropdown-item d-flex" data-bs-sidenav-sizing-value="sm" href="#" role="button">
                      <span class="material-symbols-outlined me-2">density_small</span> Small
                    </a>
                  </div>
                </div>
              </div>
            </nav>
    
            <!-- Card -->
            <div class="card mt-auto">
              <div class="card-body">
                <!-- Heading -->
                <h6>Need help?</h6>
    
                <!-- Text -->
                <p class="text-body-secondary mb-0">Feel free to reach out to us should you have any questions or suggestions.</p>
              </div>
            </div>
          </div>
        </div>
      </nav>
    </aside>


    
   