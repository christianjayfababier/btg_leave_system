   
 <!-- Topnav (toolbar) -->
 <nav class="navbar topnav-toolbar d-none d-xl-flex px-xl-6">
      <div class="container flex-column align-items-stretch">
        <div class="row">
          <div class="col">
            <!-- Search -->
            <div class="input-group" style="max-width: 400px">
              <input class="form-control" id="topnavSearchInput" type="search" placeholder="Search" aria-label="Search" aria-describedby="navbarSearch" />
              <span class="input-group-text" id="navbarSearch">
                <kbd class="badge bg-body-secondary text-body">⌘</kbd>
                <kbd class="badge bg-body-secondary text-body ms-1">K</kbd>
              </span>
            </div>
          </div>
          <div class="col-auto d-flex">
            <!-- User -->
            <div class="dropdown my-n2">
              <a class="btn btn-link d-inline-flex align-items-center dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <span class="avatar avatar-sm avatar-status avatar-status-success me-3">
                  <img class="avatar-img" src="./assets/img/photos/photo-6.jpg" alt="..." />
                </span>
                <span class="d-none d-xl-block">Nikki</span>
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
        </div>
      </div>
    </nav>