
 

    

    
    <!-- Sidenav (base) -->
    <aside class="aside aside-base sidenav-base">
      <nav class="navbar navbar-expand-xl navbar-vertical">
        <div class="container-lg">
          <!-- Brand -->
          <a class="navbar-brand d-flex align-items-center fs-5 fw-bold px-xl-3 mb-xl-4" href="./index.html">
            <i class="fs-4 text-secondary me-2" data-duoicon="box-2"></i> Dashbrd
          </a>
    
          <!-- User -->
          <div class="d-flex ms-auto d-xl-none">
            <div class="dropdown my-n2">
              <a class="btn btn-link d-inline-flex align-items-center dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <span class="avatar avatar-sm avatar-status avatar-status-success me-3">
                  <img class="avatar-img" src="./assets/img/photos/photo-6.jpg" alt="..." />
                </span>
                <span class="d-none d-xl-block">Nikki Williams</span>
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
                  href="#"
                  data-bs-toggle="collapse"
                  data-bs-target="#dashboards"
                  role="button"
                  aria-expanded="false"
                  aria-controls="dashboards"
                >
                  <span class="material-symbols-outlined me-3">space_dashboard</span> Dashboards
                </a>
                <div class="collapse show" id="dashboards">
                  <nav class="nav nav-pills">
                    <a class="nav-link active" href="../index.php">Default</a>
                    <a class="nav-link " href="../crypto.php">Crypto</a>
                    <a class="nav-link " href="../saas.php">SaaS</a>
                  </nav>
                </div>
              </div>
              <div class="nav-item">
                <a
                  class="nav-link "
                  href="#"
                  data-bs-toggle="collapse"
                  data-bs-target="#customers"
                  role="button"
                  aria-expanded="false"
                  aria-controls="customers"
                >
                  <span class="material-symbols-outlined me-3">group</span> Customers
                </a>
                <div class="collapse " id="customers">
                  <nav class="nav nav-pills">
                    <a class="nav-link " href="../customers/customers.php">Customers</a>
                    <a class="nav-link " href="../customers/customer-details.php">Customer details</a>
                    <a class="nav-link " href="../customers/customer-new.php">New customer</a>
                  </nav>
                </div>
              </div>
              <div class="nav-item">
                <a
                  class="nav-link "
                  href="#"
                  data-bs-toggle="collapse"
                  data-bs-target="#projects"
                  role="button"
                  aria-expanded="false"
                  aria-controls="projects"
                >
                  <span class="material-symbols-outlined me-3">list_alt</span> Projects
                </a>
                <div class="collapse " id="projects">
                  <nav class="nav nav-pills">
                    <a class="nav-link " href="../projects/projects.php">Projects</a>
                    <a class="nav-link " href="../projects/project-overview.php">Project overview</a>
                    <a class="nav-link " href="../projects/project-new.php">New project</a>
                  </nav>
                </div>
              </div>
              <div class="nav-item">
                <a
                  class="nav-link "
                  href="#"
                  data-bs-toggle="collapse"
                  data-bs-target="#account"
                  role="button"
                  aria-expanded="false"
                  aria-controls="account"
                >
                  <span class="material-symbols-outlined me-3">person</span> Account
                </a>
                <div class="collapse " id="account">
                  <nav class="nav nav-pills">
                    <a class="nav-link " href="account-overview.php">Account overview</a>
                    <a class="nav-link " href="account-settings.php">Account settings</a>
                  </nav>
                </div>
              </div>
              <div class="nav-item">
                <a
                  class="nav-link "
                  href="#"
                  data-bs-toggle="collapse"
                  data-bs-target="#ecommerce"
                  role="button"
                  aria-expanded="false"
                  aria-controls="ecommerce"
                >
                  <span class="material-symbols-outlined me-3">shopping_cart</span> E-commerce
                </a>
                <div class="collapse " id="ecommerce">
                  <nav class="nav nav-pills">
                    <a class="nav-link " href="../e-commerce/products.php">Products</a>
                    <a class="nav-link " href="../e-commerce/orders.php">Orders</a>
                    <a class="nav-link " href="../e-commerce/invoice.php">Invoice</a>
                    <a class="nav-link " href="../e-commerce/pricing.php">Pricing</a>
                  </nav>
                </div>
              </div>
              <div class="nav-item">
                <a
                  class="nav-link "
                  href="#"
                  data-bs-toggle="collapse"
                  data-bs-target="#posts"
                  role="button"
                  aria-expanded="false"
                  aria-controls="posts"
                >
                  <span class="material-symbols-outlined me-3">text_fields</span> Posts
                </a>
                <div class="collapse " id="posts">
                  <nav class="nav nav-pills">
                    <a class="nav-link " href="../posts/categories.php">Categories</a>
                    <a class="nav-link " href="../posts/posts.php">Posts</a>
                    <a class="nav-link " href="../posts/post-new.php">New post</a>
                  </nav>
                </div>
              </div>
              <div class="nav-item">
                <a
                  class="nav-link"
                  href="#"
                  data-bs-toggle="collapse"
                  data-bs-target="#authentication"
                  role="button"
                  aria-expanded="false"
                  aria-controls="authentication"
                >
                  <span class="material-symbols-outlined me-3">login</span> Authentication
                </a>
                <div class="collapse" id="authentication">
                  <nav class="nav nav-pills">
                    <a class="nav-link" href="../auth/sign-in.php" target="_blank">Sign in</a>
                    <a class="nav-link" href="../auth/sign-up.php" target="_blank">Sign up</a>
                    <a class="nav-link" href="../auth/password-reset.php" target="_blank">Password reset</a>
                    <a class="nav-link" href="../auth/verification-code.php" target="_blank">Verification code</a>
                    <a class="nav-link" href="../auth/error.php" target="_blank">Error</a>
                  </nav>
                </div>
              </div>
              <div class="nav-item">
                <a
                  class="nav-link "
                  href="#"
                  data-bs-toggle="collapse"
                  data-bs-target="#other"
                  role="button"
                  aria-expanded="false"
                  aria-controls="other"
                >
                  <span class="material-symbols-outlined me-3">category</span> Misc
                </a>
                <div class="collapse " id="other">
                  <nav class="nav nav-pills">
                    <a class="nav-link " href="../misc/calendar.php">Calendar</a>
                  </nav>
                </div>
              </div>
              <div class="nav-item">
                <a class="nav-link" href="#" data-bs-toggle="collapse" data-bs-target="#emails" role="button" aria-expanded="false" aria-controls="emails">
                  <span class="material-symbols-outlined me-3">mail</span> Emails
                </a>
                <div class="collapse" id="emails">
                  <nav class="nav nav-pills">
                    <a class="nav-link" href="../emails/account-confirmation.php" target="_blank">Account confirmation</a>
                    <a class="nav-link" href="../emails/new-post.php" target="_blank">New post</a>
                    <a class="nav-link" href="../emails/order-confirmation.php" target="_blank">Order confirmation</a>
                    <a class="nav-link" href="../emails/password-reset.php" target="_blank">Password reset</a>
                    <a class="nav-link" href="../emails/product-update.php" target="_blank">Product update</a>
                  </nav>
                </div>
              </div>
              <div class="nav-item">
                <a class="nav-link" href="#" data-bs-toggle="collapse" data-bs-target="#mdoals" role="button" aria-expanded="false" aria-controls="mdoals">
                  <span class="material-symbols-outlined me-3">tooltip</span> Modals
                </a>
                <div class="collapse" id="mdoals">
                  <nav class="nav nav-pills">
                    <a class="nav-link" href="#productModal" data-bs-toggle="offcanvas" aria-controls="productModal">Product</a>
                    <a class="nav-link" href="#orderModal" data-bs-toggle="offcanvas" aria-controls="orderModal">Order</a>
                    <a class="nav-link" href="#eventModal" data-bs-toggle="modal" aria-controls="eventModal">Event</a>
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


    
   