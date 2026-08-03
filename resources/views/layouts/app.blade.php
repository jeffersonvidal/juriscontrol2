<!DOCTYPE html>
<html lang="en" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — JurisControl</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <link rel="icon" type="image/svg+xml" href="{{ asset('LogoJurisControl.svg') }}">
    <!-- Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>

    <!-- NOTIFICATIONS BACKDROP -->
    <div class="notifications-backdrop" id="notifBackdrop"></div>

    <!-- NOTIFICATIONS PANEL -->
    @include('layouts.notifications')


    <!-- SIDEBAR - Starts expanded on desktop -->
    @include('layouts.sidebar')


    <!-- MAIN -->
    <div class="main">

        <!-- TOP HEADER -->
        <div class="top-header">
            <div class="search-box">
                <i class="bi bi-search text-muted"></i>
                <input type="text" placeholder="Search campaigns, posts...">
            </div>

            <!-- NOTIFICAÇÕES -->
            <div class="header-actions">
                <button class="btn-icon" id="notifBtn" title="Notifications">
                    <i class="bi bi-bell"></i>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                        style="font-size:0.6rem;">3</span>
                </button>

                <!-- TEMA CLARO/ESCURO -->
                <button class="theme-toggle" id="themeToggle" title="Toggle theme">
                    <i class="bi bi-moon-fill"></i>
                    <i class="bi bi-sun-fill"></i>
                </button>

                <!-- BOTÃO CRIAR -->
                <button class="btn-create" data-bs-toggle="modal" data-bs-target="#createCampaignModal">
                    <i class="bi bi-plus-lg"></i>
                    <span>Create campaign</span>
                </button>
            </div>
        </div>

        <!-- CONTENT -->
        <div class="content">

            @yield('content')

        </div> <!-- CONTENT -->
    </div>

    <!-- FAB -->
    <button class="fab" id="fabBtn" title="Open menu"><i class="bi bi-list"></i></button>

    <!-- CREATE CAMPAIGN MODAL -->
    <div class="modal fade" id="createCampaignModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-megaphone-fill text-primary me-2"></i>Create new campaign
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label-custom">Campaign name</label>
                        <input type="text" class="form-control form-control-custom" placeholder="e.g. Summer Sale 2026">
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label-custom">Channel</label>
                            <select class="form-select form-select-custom">
                                <option>Instagram</option>
                                <option>Facebook</option>
                                <option>TikTok</option>
                                <option>Google Ads</option>
                                <option>LinkedIn</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-custom">Goal</label>
                            <select class="form-select form-select-custom">
                                <option>Brand Awareness</option>
                                <option>Lead Generation</option>
                                <option>Conversions</option>
                                <option>Engagement</option>
                            </select>
                        </div>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label-custom">Budget</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" class="form-control form-control-custom" placeholder="1000">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-custom">Duration</label>
                            <select class="form-select form-select-custom">
                                <option>7 days</option>
                                <option>14 days</option>
                                <option>30 days</option>
                                <option>90 days</option>
                                <option>Custom</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label-custom">Description</label>
                        <textarea class="form-control form-control-custom" rows="3"
                            placeholder="Describe your campaign objectives..."></textarea>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="activateCampaign" checked>
                        <label class="form-check-label" for="activateCampaign" style="font-size:0.9rem;">Activate
                            campaign immediately</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-cancel" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn-submit"><i class="bi bi-rocket-takeoff me-1"></i> Create
                        campaign</button>
                </div>
            </div>
        </div>
    </div>

    <!-- CREATE POST MODAL -->
    <div class="modal fade" id="createPostModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-file-earmark-post-fill text-primary me-2"></i>Create new
                        post</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label-custom">Post title</label>
                        <input type="text" class="form-control form-control-custom"
                            placeholder="e.g. New Product Launch">
                    </div>
                    <div class="mb-3">
                        <label class="form-label-custom">Content</label>
                        <textarea class="form-control form-control-custom" rows="4"
                            placeholder="Write your post content..."></textarea>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label-custom">Channel</label>
                            <select class="form-select form-select-custom">
                                <option>Instagram</option>
                                <option>Facebook</option>
                                <option>TikTok</option>
                                <option>LinkedIn</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-custom">Campaign</label>
                            <select class="form-select form-select-custom">
                                <option>Marketing strategy</option>
                                <option>Summer Sale 2026</option>
                                <option>Brand Awareness Q3</option>
                            </select>
                        </div>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label-custom">Daily budget</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" class="form-control form-control-custom" placeholder="100">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-custom">Schedule</label>
                            <input type="datetime-local" class="form-control form-control-custom">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label-custom">Media</label>
                        <div style="border:2px dashed var(--border); border-radius:12px; padding:2rem; text-align:center; color:var(--text-secondary); cursor:pointer; transition:all 0.2s;"
                            onmouseover="this.style.borderColor='var(--primary)'"
                            onmouseout="this.style.borderColor='var(--border)'">
                            <i class="bi bi-cloud-arrow-up" style="font-size:2rem; color:var(--primary);"></i>
                            <div class="mt-2" style="font-size:0.9rem;">Click or drag files to upload</div>
                            <div style="font-size:0.75rem; color:var(--text-muted);">PNG, JPG, MP4 up to 50MB</div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-cancel" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn-cancel"
                        style="color:var(--primary); border-color:var(--primary);"><i class="bi bi-clock me-1"></i>
                        Schedule</button>
                    <button type="button" class="btn-submit"><i class="bi bi-send me-1"></i> Publish now</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Axios -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script></script>
    <script>
        // SIDEBAR TOGGLE - Desktop: starts expanded, toggle adds/removes 'collapsed'
        const sidebar = document.getElementById('sidebar');
        const sidebarToggle = document.getElementById('sidebarToggle');
        const fabBtn = document.getElementById('fabBtn');

        function isMobile() { return window.innerWidth <= 992; }

        function toggleSidebar() {
            if (isMobile()) {
                sidebar.classList.toggle('mobile-open');
            } else {
                sidebar.classList.toggle('collapsed');
            }
        }

        sidebarToggle.addEventListener('click', (e) => {
            e.stopPropagation();
            toggleSidebar();
        });

        fabBtn.addEventListener('click', () => {
            sidebar.classList.add('mobile-open');
        });

        document.addEventListener('click', (e) => {
            if (isMobile() && sidebar.classList.contains('mobile-open') && !sidebar.contains(e.target) && e.target !== fabBtn) {
                sidebar.classList.remove('mobile-open');
            }
        });

        // SUBMENUS
        document.querySelectorAll('[data-submenu]').forEach(item => {
            item.addEventListener('click', (e) => {
                e.preventDefault();
                const submenuId = item.getAttribute('data-submenu');
                const submenu = document.getElementById(submenuId);
                const arrow = item.querySelector('.sidebar-item-arrow');

                document.querySelectorAll('.sidebar-submenu').forEach(sm => {
                    if (sm.id !== submenuId) sm.classList.remove('open');
                });
                document.querySelectorAll('.sidebar-item-arrow').forEach(ar => {
                    if (ar !== arrow) ar.classList.remove('rotated');
                });

                submenu.classList.toggle('open');
                arrow.classList.toggle('rotated');
            });
        });

        // FILTER DROPDOWNS
        document.querySelectorAll('.filter-btn[data-filter]').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.stopPropagation();
                const filterId = btn.getAttribute('data-filter');
                const menu = document.getElementById('filter-' + filterId);

                document.querySelectorAll('.filter-menu').forEach(m => {
                    if (m.id !== 'filter-' + filterId) m.classList.remove('show');
                });

                menu.classList.toggle('show');
            });
        });

        document.querySelectorAll('.filter-menu-item').forEach(item => {
            item.addEventListener('click', (e) => {
                e.stopPropagation();
                const menu = item.closest('.filter-menu');
                const btn = menu.previousElementSibling;

                menu.querySelectorAll('.filter-menu-item').forEach(i => i.classList.remove('active'));
                item.classList.add('active');

                const icon = item.querySelector('i');
                const text = item.textContent.trim();

                if (btn.getAttribute('data-filter') === 'channel') {
                    btn.innerHTML = icon.outerHTML + ' Channel: ' + text + ' <i class="bi bi-chevron-down"></i>';
                } else if (btn.getAttribute('data-filter') === 'goals') {
                    btn.innerHTML = 'Goals: ' + text + ' <i class="bi bi-chevron-down"></i>';
                } else if (btn.getAttribute('data-filter') === 'date') {
                    btn.innerHTML = '<i class="bi bi-calendar3"></i> ' + text + ' <i class="bi bi-chevron-down"></i>';
                }

                menu.classList.remove('show');
            });
        });

        // ACTION DROPDOWNS
        document.querySelectorAll('.action-btn[data-action]').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.stopPropagation();
                const actionId = btn.getAttribute('data-action');
                const menu = document.getElementById('action-menu-' + actionId);

                document.querySelectorAll('.action-menu').forEach(m => {
                    if (m.id !== 'action-menu-' + actionId) m.classList.remove('show');
                });

                menu.classList.toggle('show');
            });
        });

        document.querySelectorAll('.action-menu-item').forEach(item => {
            item.addEventListener('click', (e) => {
                e.stopPropagation();
                const menu = item.closest('.action-menu');
                menu.classList.remove('show');

                const action = item.getAttribute('data-act');
                const row = item.closest('tr');

                if (action === 'delete' && row) {
                    row.classList.add('row-deleting');
                    setTimeout(() => {
                        row.remove();
                        updatePostCount();
                    }, 800);
                }
            });
        });

        document.addEventListener('click', () => {
            document.querySelectorAll('.filter-menu, .action-menu').forEach(m => m.classList.remove('show'));
        });

        document.querySelectorAll('.filter-menu, .action-menu').forEach(menu => {
            menu.addEventListener('click', (e) => e.stopPropagation());
        });

        // TABS
        document.querySelectorAll('.tab-item').forEach(tab => {
            tab.addEventListener('click', () => {
                document.querySelectorAll('.tab-item').forEach(t => t.classList.remove('active'));
                tab.classList.add('active');
            });
        });

        // THEME TOGGLE
        const themeToggle = document.getElementById('themeToggle');
        const html = document.documentElement;
        const savedTheme = localStorage.getItem('theme') || 'light';
        html.setAttribute('data-theme', savedTheme);

        themeToggle.addEventListener('click', () => {
            const current = html.getAttribute('data-theme');
            const next = current === 'light' ? 'dark' : 'light';
            html.setAttribute('data-theme', next);
            localStorage.setItem('theme', next);
        });

        // NOTIFICATIONS PANEL
        const notifBtn = document.getElementById('notifBtn');
        const notifPanel = document.getElementById('notifPanel');
        const notifBackdrop = document.getElementById('notifBackdrop');
        const notifClose = document.getElementById('notifClose');

        function openNotif() { notifPanel.classList.add('show'); notifBackdrop.classList.add('show'); }
        function closeNotif() { notifPanel.classList.remove('show'); notifBackdrop.classList.remove('show'); }

        notifBtn.addEventListener('click', openNotif);
        notifClose.addEventListener('click', closeNotif);
        notifBackdrop.addEventListener('click', closeNotif);

        // HIDE ALERTS
        const hideAlerts = document.getElementById('hideAlerts');
        const alertsSection = document.getElementById('alertsSection');

        hideAlerts.addEventListener('click', () => {
            alertsSection.style.display = 'none';
        });

        // COUNTER ANIMATION
        function animateCounter(el) {
            const target = parseFloat(el.getAttribute('data-target'));
            const prefix = el.getAttribute('data-prefix') || '';
            const suffix = el.getAttribute('data-suffix') || '';
            const format = el.getAttribute('data-format');
            const duration = 1500;
            const startTime = performance.now();

            function update(currentTime) {
                const elapsed = currentTime - startTime;
                const progress = Math.min(elapsed / duration, 1);
                const eased = 1 - Math.pow(1 - progress, 3);
                const current = target * eased;

                let display;
                if (format === 'currency') {
                    display = prefix + Math.floor(current).toLocaleString() + suffix;
                } else if (format === 'number') {
                    display = prefix + Math.floor(current).toLocaleString() + suffix;
                } else {
                    display = prefix + current.toFixed(target % 1 !== 0 ? 1 : 0) + suffix;
                }

                el.textContent = display;

                if (progress < 1) {
                    requestAnimationFrame(update);
                }
            }

            requestAnimationFrame(update);
        }

        const counterObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    animateCounter(entry.target);
                    counterObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.5 });

        document.querySelectorAll('.counter').forEach(el => counterObserver.observe(el));

        // PROGRESS BAR ANIMATION
        const progressObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const width = entry.target.getAttribute('data-width');
                    setTimeout(() => {
                        entry.target.style.width = width;
                    }, 300);
                    progressObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.5 });

        document.querySelectorAll('.campaign-progress-fill').forEach(el => progressObserver.observe(el));

        // ADD RECORD
        const addRecordBtn = document.getElementById('addRecordBtn');
        let recordId = 5;

        addRecordBtn.addEventListener('click', () => {
            const tbody = document.getElementById('postsBody');
            const newRow = document.createElement('tr');
            newRow.setAttribute('data-id', recordId);
            newRow.classList.add('row-adding');

            const titles = ['Flash Sale: 50% Off Everything!', 'Summer Collection Launch 2026', 'Behind the Scenes: Our Team', 'Customer Success Story'];
            const audiences = ['75,000', '110,000', '45,000', '62,000'];
            const rois = ['2.5x', '3.8x', '1.9x', '2.7x'];
            const ctrs = ['3.2%', '4.1%', '2.5%', '3.6%'];
            const cpls = ['$2.10', '$1.80', '$3.50', '$2.40'];
            const budgets = ['$75/day', '$120/day', '$60/day', '$85/day'];

            const idx = Math.floor(Math.random() * titles.length);

            newRow.innerHTML = `
      <td class="post-title-cell"><i class="bi bi-instagram"></i> ${titles[idx]}</td>
      <td class="fw-semibold">${audiences[idx]}</td>
      <td><span class="roi-badge">${rois[idx]}</span></td>
      <td>${ctrs[idx]}</td>
      <td>${cpls[idx]}</td>
      <td>${budgets[idx]}</td>
      <td>
        <div class="manager-cell">
          <div class="manager-avatar avatar-lj">LJ</div>
          <div><p class="manager-name">Louis Jensen</p><p class="manager-role">SMM manager</p></div>
        </div>
      </td>
      <td>
        <div class="action-dropdown">
          <button class="action-btn" data-action="${recordId}"><i class="bi bi-three-dots-vertical"></i></button>
          <div class="action-menu" id="action-menu-${recordId}">
            <button class="action-menu-item" data-act="view"><i class="bi bi-eye"></i> View</button>
            <button class="action-menu-item" data-act="edit"><i class="bi bi-pencil"></i> Edit</button>
            <button class="action-menu-item danger" data-act="delete"><i class="bi bi-trash3"></i> Delete</button>
          </div>
        </div>
      </td>
    `;

            tbody.insertBefore(newRow, tbody.firstChild);
            recordId++;
            updatePostCount();

            const newActionBtn = newRow.querySelector('.action-btn');
            newActionBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                const actionId = newActionBtn.getAttribute('data-action');
                const menu = document.getElementById('action-menu-' + actionId);

                document.querySelectorAll('.action-menu').forEach(m => {
                    if (m.id !== 'action-menu-' + actionId) m.classList.remove('show');
                });

                menu.classList.toggle('show');
            });

            const newMenuItems = newRow.querySelectorAll('.action-menu-item');
            newMenuItems.forEach(item => {
                item.addEventListener('click', (e) => {
                    e.stopPropagation();
                    const menu = item.closest('.action-menu');
                    menu.classList.remove('show');

                    const action = item.getAttribute('data-act');
                    const row = item.closest('tr');

                    if (action === 'delete' && row) {
                        row.classList.add('row-deleting');
                        setTimeout(() => {
                            row.remove();
                            updatePostCount();
                        }, 800);
                    }
                });
            });

            setTimeout(() => { newRow.classList.remove('row-adding'); }, 1200);
        });

        function updatePostCount() {
            const count = document.querySelectorAll('#postsBody tr').length;
            document.getElementById('postCount').textContent = count;
            document.getElementById('showingCount').textContent = count;
            document.getElementById('totalCount').textContent = count;
        }

        // PAGINATION
        document.querySelectorAll('.page-btn[data-page]').forEach(btn => {
            btn.addEventListener('click', () => {
                document.querySelectorAll('.page-btn').forEach(b => b.classList.remove('active'));
                btn.classList.add('active');

                const page = parseInt(btn.getAttribute('data-page'));
                document.getElementById('prevPage').disabled = page === 1;
                document.getElementById('nextPage').disabled = page === 3;
            });
        });

        document.getElementById('prevPage').addEventListener('click', () => {
            const active = document.querySelector('.page-btn.active');
            const page = parseInt(active.getAttribute('data-page'));
            if (page > 1) {
                document.querySelectorAll('.page-btn').forEach(b => b.classList.remove('active'));
                document.querySelector(`.page-btn[data-page="${page - 1}"]`).classList.add('active');
                document.getElementById('prevPage').disabled = page - 1 === 1;
                document.getElementById('nextPage').disabled = false;
            }
        });

        document.getElementById('nextPage').addEventListener('click', () => {
            const active = document.querySelector('.page-btn.active');
            const page = parseInt(active.getAttribute('data-page'));
            if (page < 3) {
                document.querySelectorAll('.page-btn').forEach(b => b.classList.remove('active'));
                document.querySelector(`.page-btn[data-page="${page + 1}"]`).classList.add('active');
                document.getElementById('nextPage').disabled = page + 1 === 3;
                document.getElementById('prevPage').disabled = false;
            }
        });
    </script>
    <div class="sidebar-backdrop"></div>
    <script>
        /**FAB - Floating Action Button (exibir sidebar no mobile) */
        document.addEventListener("DOMContentLoaded", () => {
            const fab = document.getElementById("fab-toggle") || document.querySelector(".fab");
            const sidebar = document.querySelector(".sidebar");
            const bd = document.querySelector(".sidebar-backdrop");
            if (!fab || !sidebar || !bd) return; const open = () => {
                sidebar.classList.add("show");
                bd.classList.add("show")
            };
            const close = () => {
                sidebar.classList.remove("show");
                bd.classList.remove("show")
            };
            fab.addEventListener("click", e => {
                if (window.innerWidth <= 991.98) {
                    e.stopPropagation();
                    open();
                }
            });
            bd.addEventListener("click", close);
            document.addEventListener("click", e => {
                if (window.innerWidth <= 991.98 && sidebar.classList.contains("show") && !sidebar.contains(e.target) && !fab.contains(e.target))
                    close();
            });
        });
    </script>
    <style>
        .sidebar-backdrop {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, .35);
            opacity: 0;
            visibility: hidden;
            transition: .25s;
            z-index: 1039
        }

        .sidebar-backdrop.show {
            opacity: 1;
            visibility: visible
        }

        .sidebar {
            z-index: 1040
        }
    </style>

    @stack('scripts')
</body>
</html>