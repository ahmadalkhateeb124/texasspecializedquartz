<nav class="nxl-navigation">
    <div class="navbar-wrapper">

        <div class="navbar-content">
            <ul class="nxl-navbar">
                <li class="nxl-item nxl-caption">
                    <label>Navigation</label>
                </li>
                <?php if ($user_id): ?>
                    <li class="nxl-item nxl-hasmenu">
                        <a href="javascript:void(0);" class="nxl-link">
                            <span class="nxl-micon"><i class="feather-airplay"></i></span>
                            <span class="nxl-mtext">Dashboards</span><span class="nxl-arrow"><i class="feather-chevron-right"></i></span>
                        </a>
                        <ul class="nxl-submenu">
                            <li class="nxl-item"><a class="nxl-link" href="index.php">Analytics</a></li>
                        </ul>
                    </li>
                <?php endif; ?>
                <!--                    <li class="nxl-item nxl-hasmenu">-->
                <!--                        <a href="javascript:void(0);" class="nxl-link">-->
                <!--                            <span class="nxl-micon"><i class="feather-cast"></i></span>-->
                <!--                            <span class="nxl-mtext">Reports</span><span class="nxl-arrow"><i class="feather-chevron-right"></i></span>-->
                <!--                        </a>-->
                <!--                        <ul class="nxl-submenu">-->
                <!--                            <li class="nxl-item"><a class="nxl-link" href="reports-sales.php">Sales Report</a></li>-->
                <!--                            <li class="nxl-item"><a class="nxl-link" href="reports-leads.php">Leads Report</a></li>-->
                <!--                            <li class="nxl-item"><a class="nxl-link" href="reports-project.php">Project Report</a></li>-->
                <!--                            <li class="nxl-item"><a class="nxl-link" href="reports-timesheets.php">Timesheets Report</a></li>-->
                <!--                        </ul>-->
                <!--                    </li>-->
                <!--                    <li class="nxl-item nxl-hasmenu">-->
                <!--                        <a href="javascript:void(0);" class="nxl-link">-->
                <!--                            <span class="nxl-micon"><i class="feather-send"></i></span>-->
                <!--                            <span class="nxl-mtext">Applications</span><span class="nxl-arrow"><i class="feather-chevron-right"></i></span>-->
                <!--                        </a>-->
                <!--                        <ul class="nxl-submenu">-->
                <!--                            <li class="nxl-item"><a class="nxl-link" href="apps-chat.php">Chat</a></li>-->
                <!--                            <li class="nxl-item"><a class="nxl-link" href="apps-email.php">Email</a></li>-->
                <!--                            <li class="nxl-item"><a class="nxl-link" href="apps-tasks.php">Tasks</a></li>-->
                <!--                            <li class="nxl-item"><a class="nxl-link" href="apps-notes.php">Notes</a></li>-->
                <!--                            <li class="nxl-item"><a class="nxl-link" href="apps-storage.php">Storage</a></li>-->
                <!--                            <li class="nxl-item"><a class="nxl-link" href="apps-calendar.php">Calendar</a></li>-->
                <!--                        </ul>-->
                <!--                    </li>-->


                <?php if ($user_id): ?>
                    <li class="nxl-item nxl-hasmenu">
                        <a href="javascript:void(0);" class="nxl-link">
                            <span class="nxl-micon"><i class='bx  bx-basket'></i> </span>
                            <span class="nxl-mtext">Shop </span><span class="nxl-arrow"><i class="feather-chevron-right"></i></span>
                        </a>
                        <ul class="nxl-submenu">
                            <li class="nxl-item"><a class="nxl-link" href="Products.php">Products</a></li>
                            <!--                            <li class="nxl-item"><a class="nxl-link" href="Ordar.php">Ordar</a></li>-->
                            <!-- <li class="nxl-item"><a class="nxl-link" href="Reservations.php">Reservations</a></li> -->
                            <!--                            <li class="nxl-item"><a class="nxl-link" href="proposal-view.php">Cars View</a></li>-->
                            <!--                            <li class="nxl-item"><a class="nxl-link" href="proposal-edit.php">Cars Edit</a></li>-->
                            <!--                            <li class="nxl-item"><a class="nxl-link" href="proposal-create.php">Cars Create</a></li>-->
                            <!--                            <li class="nxl-item"><a class="nxl-link" href="Category.php">Category</a></li>-->
                            <!-- <li class="nxl-item"><a class="nxl-link" href="Features.php">Features</a></li> -->
                        </ul>
                    </li>
                <?php endif; ?>

                <?php if ($user_id): ?>
                    <li class="nxl-item nxl-hasmenu">
                        <a href="javascript:void(0);" class="nxl-link">
                            <span class="nxl-micon"><i class='bxr bx-user'></i> </span>
                            <span class="nxl-mtext">Customers</span><span class="nxl-arrow"><i class="feather-chevron-right"></i></span>
                        </a>
                        <ul class="nxl-submenu">
                            <li class="nxl-item">
                                <a class="nxl-link" href="Customer.php">All Customers</a>
                            </li>
                        </ul>

                    </li>
                <?php endif; ?>
                <li class="nxl-item nxl-hasmenu">
                    <a href="javascript:void(0);" class="nxl-link">
                        <span class="nxl-micon">
                            <i class='bx bx-file'></i>
                        </span>
                        <span class="nxl-mtext">Fabrication Center</span><span class="nxl-arrow"><i class="feather-chevron-right"></i></span>
                    </a>
                    <ul class="nxl-submenu">
                        <li class="nxl-item">
                            <a class="nxl-link" href="FabricationOrders.php">Fabrication Orders</a>
                        </li>
                    </ul>

                </li>
                <li class="nxl-item nxl-hasmenu">
                    <!-- <a href="javascript:void(0);" class="nxl-link">
                            <span class="nxl-micon"><i class='bxr bx-tickets'></i> </span>
                            <span class="nxl-mtext">Movements</span><span class="nxl-arrow"><i class="feather-chevron-right"></i></span>
                        </a> -->
                    <!-- <ul class="nxl-submenu"> -->
                    <!--                            <li class="nxl-item"><a class="nxl-link" href="Booking.php">Booking</a></li>-->
                    <!--                            <li class="nxl-item"><a class="nxl-link" href="Reservations-view.php">Reservations</a></li>-->
                    <!-- <li class="nxl-item"><a class="nxl-link" href="Typescontracts.php">Types of contracts</a></li> -->
                    <!--                            <li class="nxl-item"><a class="nxl-link" href="proposal-view.php">Cars View</a></li>-->
                    <!--                            <li class="nxl-item"><a class="nxl-link" href="proposal-edit.php">Cars Edit</a></li>-->
                    <!--                            <li class="nxl-item"><a class="nxl-link" href="proposal-create.php">Cars Create</a></li>-->
                    <!-- <li class="nxl-item"><a class="nxl-link" href="Features">Features</a></li> -->
                    <!-- </ul> -->
                </li>
                <!-- <li class="nxl-item nxl-hasmenu">
                        <a href="javascript:void(0);" class="nxl-link">
                            <span class="nxl-micon"><i class='bxr bx-user'></i> </span>
                            <span class="nxl-mtext">Customers</span><span class="nxl-arrow"><i class="feather-chevron-right"></i></span>
                        </a>
                        <ul class="nxl-submenu">
                            <li class="nxl-item">
                                <a class="nxl-link" href="Customer.php">All Customers</a>
                            </li>
                        </ul>

                    </li> -->
                <!--                    <li class="nxl-item nxl-hasmenu">-->
                <!--                        <a href="javascript:void(0);" class="nxl-link">-->
                <!--                            <span class="nxl-micon"><i class="feather-dollar-sign"></i></span>-->
                <!--                            <span class="nxl-mtext">Payment</span><span class="nxl-arrow"><i class="feather-chevron-right"></i></span>-->
                <!--                        </a>-->
                <!--                        <ul class="nxl-submenu">-->
                <!--                            <li class="nxl-item"><a class="nxl-link" href="payment.php">Payment</a></li>-->
                <!--                            <li class="nxl-item"><a class="nxl-link" href="invoice-view.php">Invoice View</a></li>-->
                <!--                            <li class="nxl-item"><a class="nxl-link" href="invoice-create.php">Invoice Create</a></li>-->
                <!--                        </ul>-->
                <!--                    </li>-->
                <!--                    <li class="nxl-item nxl-hasmenu">-->
                <!--                        <a href="javascript:void(0);" class="nxl-link">-->
                <!--                            <span class="nxl-micon"><i class="feather-users"></i></span>-->
                <!--                            <span class="nxl-mtext">Customers</span><span class="nxl-arrow"><i class="feather-chevron-right"></i></span>-->
                <!--                        </a>-->
                <!--                        <ul class="nxl-submenu">-->
                <!--                            <li class="nxl-item"><a class="nxl-link" href="customers.php">Customers</a></li>-->
                <!--                            <li class="nxl-item"><a class="nxl-link" href="customers-view.php">Customers View</a></li>-->
                <!--                            <li class="nxl-item"><a class="nxl-link" href="customers-create.php">Customers Create</a></li>-->
                <!--                        </ul>-->
                <!--                    </li>-->
                <!--                    <li class="nxl-item nxl-hasmenu">-->
                <!--                        <a href="javascript:void(0);" class="nxl-link">-->
                <!--                            <span class="nxl-micon"><i class="feather-alert-circle"></i></span>-->
                <!--                            <span class="nxl-mtext">Leads</span><span class="nxl-arrow"><i class="feather-chevron-right"></i></span>-->
                <!--                        </a>-->
                <!--                        <ul class="nxl-submenu">-->
                <!--                            <li class="nxl-item"><a class="nxl-link" href="leads.php">Leads</a></li>-->
                <!--                            <li class="nxl-item"><a class="nxl-link" href="leads-view.php">Leads View</a></li>-->
                <!--                            <li class="nxl-item"><a class="nxl-link" href="leads-create.php">Leads Create</a></li>-->
                <!--                        </ul>-->
                <!--                    </li>-->
                <!--                    <li class="nxl-item nxl-hasmenu">-->
                <!--                        <a href="javascript:void(0);" class="nxl-link">-->
                <!--                            <span class="nxl-micon"><i class="feather-briefcase"></i></span>-->
                <!--                            <span class="nxl-mtext">Projects</span><span class="nxl-arrow"><i class="feather-chevron-right"></i></span>-->
                <!--                        </a>-->
                <!--                        <ul class="nxl-submenu">-->
                <!--                            <li class="nxl-item"><a class="nxl-link" href="projects.php">Projects</a></li>-->
                <!--                            <li class="nxl-item"><a class="nxl-link" href="projects-view.php">Projects View</a></li>-->
                <!--                            <li class="nxl-item"><a class="nxl-link" href="projects-create.php">Projects Create</a></li>-->
                <!--                        </ul>-->
                <!--                    </li>-->
                <!--                    <li class="nxl-item nxl-hasmenu">-->
                <!--                        <a href="javascript:void(0);" class="nxl-link">-->
                <!--                            <span class="nxl-micon"><i class="feather-layout"></i></span>-->
                <!--                            <span class="nxl-mtext">Widgets</span><span class="nxl-arrow"><i class="feather-chevron-right"></i></span>-->
                <!--                        </a>-->
                <!--                        <ul class="nxl-submenu">-->
                <!--                            <li class="nxl-item"><a class="nxl-link" href="widgets-lists.php">Lists</a></li>-->
                <!--                            <li class="nxl-item"><a class="nxl-link" href="widgets-tables.php">Tables</a></li>-->
                <!--                            <li class="nxl-item"><a class="nxl-link" href="widgets-charts.php">Charts</a></li>-->
                <!--                            <li class="nxl-item"><a class="nxl-link" href="widgets-statistics.php">Statistics</a></li>-->
                <!--                            <li class="nxl-item"><a class="nxl-link" href="widgets-miscellaneous.php">Miscellaneous</a></li>-->
                <!--                        </ul>-->
                <!--                    </li>-->
                <!--                    <li class="nxl-item nxl-hasmenu">-->
                <!--                        <a href="javascript:void(0);" class="nxl-link">-->
                <!--                            <span class="nxl-micon"><i class="feather-settings"></i></span>-->
                <!--                            <span class="nxl-mtext">Settings</span><span class="nxl-arrow"><i class="feather-chevron-right"></i></span>-->
                <!--                        </a>-->
                <!--                        <ul class="nxl-submenu">-->
                <!--                            <li class="nxl-item"><a class="nxl-link" href="settings-general.php">General</a></li>-->
                <!--                            <li class="nxl-item"><a class="nxl-link" href="settings-seo.php">SEO</a></li>-->
                <!--                            <li class="nxl-item"><a class="nxl-link" href="settings-tags.php">Tags</a></li>-->
                <!--                            <li class="nxl-item"><a class="nxl-link" href="settings-email.php">Email</a></li>-->
                <!--                            <li class="nxl-item"><a class="nxl-link" href="settings-tasks.php">Tasks</a></li>-->
                <!--                            <li class="nxl-item"><a class="nxl-link" href="settings-leads.php">Leads</a></li>-->
                <!--                            <li class="nxl-item"><a class="nxl-link" href="settings-support.php">Support</a></li>-->
                <!--                            <li class="nxl-item"><a class="nxl-link" href="settings-finance.php">Finance</a></li>-->
                <!--                            <li class="nxl-item"><a class="nxl-link" href="settings-gateways.php">Gateways</a></li>-->
                <!--                            <li class="nxl-item"><a class="nxl-link" href="settings-customers.php">Customers</a></li>-->
                <!--                            <li class="nxl-item"><a class="nxl-link" href="settings-localization.php">Localization</a></li>-->
                <!--                            <li class="nxl-item"><a class="nxl-link" href="settings-recaptcha.php">reCAPTCHA</a></li>-->
                <!--                            <li class="nxl-item"><a class="nxl-link" href="settings-miscellaneous.php">Miscellaneous</a></li>-->
                <!--                        </ul>-->
                <!--                    </li>-->
                <!--                    <li class="nxl-item nxl-hasmenu">-->
                <!--                        <a href="javascript:void(0);" class="nxl-link">-->
                <!--                            <span class="nxl-micon"><i class="feather-power"></i></span>-->
                <!--                            <span class="nxl-mtext">Authentication</span><span class="nxl-arrow"><i class="feather-chevron-right"></i></span>-->
                <!--                        </a>-->
                <!--                        <ul class="nxl-submenu">-->
                <!--                            <li class="nxl-item nxl-hasmenu">-->
                <!--                                <a href="javascript:void(0);" class="nxl-link">-->
                <!--                                    <span class="nxl-mtext">Login</span><span class="nxl-arrow"><i class="feather-chevron-right"></i></span>-->
                <!--                                </a>-->
                <!--                                <ul class="nxl-submenu">-->
                <!--                                    <li class="nxl-item"><a class="nxl-link" href="./auth-login-cover.php">Cover</a></li>-->
                <!--                                    <li class="nxl-item"><a class="nxl-link" href="./auth-login-minimal.php">Minimal</a></li>-->
                <!--                                    <li class="nxl-item"><a class="nxl-link" href="./auth-login-creative.php">Creative</a></li>-->
                <!--                                </ul>-->
                <!--                            </li>-->
                <!--                            <li class="nxl-item nxl-hasmenu">-->
                <!--                                <a href="javascript:void(0);" class="nxl-link">-->
                <!--                                    <span class="nxl-mtext">Register</span><span class="nxl-arrow"><i class="feather-chevron-right"></i></span>-->
                <!--                                </a>-->
                <!--                                <ul class="nxl-submenu">-->
                <!--                                    <li class="nxl-item"><a class="nxl-link" href="./auth-register-cover.php">Cover</a></li>-->
                <!--                                    <li class="nxl-item"><a class="nxl-link" href="./auth-register-minimal.php">Minimal</a></li>-->
                <!--                                    <li class="nxl-item"><a class="nxl-link" href="./auth-register-creative.php">Creative</a></li>-->
                <!--                                </ul>-->
                <!--                            </li>-->
                <!--                            <li class="nxl-item nxl-hasmenu">-->
                <!--                                <a href="javascript:void(0);" class="nxl-link">-->
                <!--                                    <span class="nxl-mtext">Error-404</span><span class="nxl-arrow"><i class="feather-chevron-right"></i></span>-->
                <!--                                </a>-->
                <!--                                <ul class="nxl-submenu">-->
                <!--                                    <li class="nxl-item"><a class="nxl-link" href="./auth-404-cover.php">Cover</a></li>-->
                <!--                                    <li class="nxl-item"><a class="nxl-link" href="./auth-404-minimal.php">Minimal</a></li>-->
                <!--                                    <li class="nxl-item"><a class="nxl-link" href="./auth-404-creative.php">Creative</a></li>-->
                <!--                                </ul>-->
                <!--                            </li>-->
                <!--                            <li class="nxl-item nxl-hasmenu">-->
                <!--                                <a href="javascript:void(0);" class="nxl-link">-->
                <!--                                    <span class="nxl-mtext">Reset Pass</span><span class="nxl-arrow"><i class="feather-chevron-right"></i></span>-->
                <!--                                </a>-->
                <!--                                <ul class="nxl-submenu">-->
                <!--                                    <li class="nxl-item"><a class="nxl-link" href="./auth-reset-cover.php">Cover</a></li>-->
                <!--                                    <li class="nxl-item"><a class="nxl-link" href="./auth-reset-minimal.php">Minimal</a></li>-->
                <!--                                    <li class="nxl-item"><a class="nxl-link" href="./auth-reset-creative.php">Creative</a></li>-->
                <!--                                </ul>-->
                <!--                            </li>-->
                <!--                            <li class="nxl-item nxl-hasmenu">-->
                <!--                                <a href="javascript:void(0);" class="nxl-link">-->
                <!--                                    <span class="nxl-mtext">Verify OTP</span><span class="nxl-arrow"><i class="feather-chevron-right"></i></span>-->
                <!--                                </a>-->
                <!--                                <ul class="nxl-submenu">-->
                <!--                                    <li class="nxl-item"><a class="nxl-link" href="./auth-verify-cover.php">Cover</a></li>-->
                <!--                                    <li class="nxl-item"><a class="nxl-link" href="./auth-verify-minimal.php">Minimal</a></li>-->
                <!--                                    <li class="nxl-item"><a class="nxl-link" href="./auth-verify-creative.php">Creative</a></li>-->
                <!--                                </ul>-->
                <!--                            </li>-->
                <!--                            <li class="nxl-item nxl-hasmenu">-->
                <!--                                <a href="javascript:void(0);" class="nxl-link">-->
                <!--                                    <span class="nxl-mtext">Maintenance</span><span class="nxl-arrow"><i class="feather-chevron-right"></i></span>-->
                <!--                                </a>-->
                <!--                                <ul class="nxl-submenu">-->
                <!--                                    <li class="nxl-item"><a class="nxl-link" href="./auth-maintenance-cover.php">Cover</a></li>-->
                <!--                                    <li class="nxl-item"><a class="nxl-link" href="./auth-maintenance-minimal.php">Minimal</a></li>-->
                <!--                                    <li class="nxl-item"><a class="nxl-link" href="./auth-maintenance-creative.php">Creative</a></li>-->
                <!--                                </ul>-->
                <!--                            </li>-->
                <!--                        </ul>-->
                <!--                    </li>-->
                <li class="nxl-item nxl-hasmenu">
                    <a href="javascript:void(0);" class="nxl-link">
                        <span class="nxl-micon"><i class="feather-life-buoy"></i></span>
                        <span class="nxl-mtext">Help Center</span>
                        <span class="nxl-arrow"><i class="feather-chevron-right"></i></span>
                    </a>
                    <ul class="nxl-submenu">
                        <li class="nxl-item">
                            <a class="nxl-link" href="https://mail.google.com/mail/?view=cm&to=Cs@TexasSpecializedQuartz.com" target="_blank">
                                Support
                            </a>
                        </li>

                    </ul>
                </li>
            </ul>
            <!-- <div class="card text-center">
                    <div class="card-body">
                        <i class="feather-sunrise fs-4 text-dark"></i>
                        <h6 class="mt-4 text-dark fw-bolder">Downloading Center</h6>
                        <p class="fs-11 my-3 text-dark">Duralux is a production ready CRM to get started up and running easily.</p>
                        <a href="javascript:void(0);" class="btn btn-primary text-dark w-100">Download Now</a>
                    </div>
                </div> -->
        </div>
    </div>
</nav>
<!--! ================================================================ !-->
<!--! [End]  Navigation Manu !-->
<!--! ================================================================ !-->