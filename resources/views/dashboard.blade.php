@extends('layouts.app')

@section('title', 'Dashboard')

@php
    $hour = (int) now()->format('H');
    $greeting = $hour < 12 ? 'Bom dia' : ($hour < 18 ? 'Boa tarde' : 'Boa noite');
    $firstName = explode(' ', auth()->user()->name)[0];
@endphp

@section('greeting', $greeting . ', ' . $firstName . '.')
@section('greeting-sub')
<!-- ou@section('greeting-sub', 'Você possui 3 prioridades para hoje.') -->

@section('content')

<!-- BREADCRUMB -->
            <div class="breadcrumb-custom">
                <a href="#">Campaigns</a>
                <span class="sep">/</span>
                <a href="#">Overview</a>
                <span class="sep">/</span>
                <span>Marketing strategy</span>
            </div>

            <!-- PAGE HEADER -->
            <div class="page-header">
                <div>
                    <div class="page-title-wrap">
                        <h1 class="page-title">Marketing strategy</h1>
                        <span class="badge-active">Active</span>
                    </div>
                    <p class="page-subtitle mb-0">Create, manage, and track posts activity for your campaign all in one
                        place.</p>
                </div>
                <button class="btn-create-post" data-bs-toggle="modal" data-bs-target="#createPostModal">
                    <i class="bi bi-plus-lg"></i>
                    <span>Create post</span>
                </button>
            </div>

            <!-- TABS + FILTERS -->
            <div class="tabs-row">
                <div class="tabs">
                    <button class="tab-item active">Overview</button>
                    <button class="tab-item">Posts</button>
                    <button class="tab-item">Performance</button>
                    <button class="tab-item">Settings</button>
                </div>
                <div class="filters">
                    <div class="filter-dropdown">
                        <button class="filter-btn" data-filter="channel">
                            <i class="bi bi-instagram" style="color:#e1306c;"></i> Channel: Instagram <i
                                class="bi bi-chevron-down"></i>
                        </button>
                        <div class="filter-menu" id="filter-channel">
                            <div class="filter-menu-header">Select channel</div>
                            <div class="filter-menu-item active" data-value="instagram"><i class="bi bi-instagram"
                                    style="color:#e1306c;"></i> Instagram</div>
                            <div class="filter-menu-item" data-value="facebook"><i class="bi bi-facebook"
                                    style="color:#1877f2;"></i> Facebook</div>
                            <div class="filter-menu-item" data-value="tiktok"><i class="bi bi-music-note-beamed"></i>
                                TikTok</div>
                            <div class="filter-menu-item" data-value="twitter"><i class="bi bi-twitter-x"></i> X
                                (Twitter)</div>
                            <div class="filter-menu-divider"></div>
                            <div class="filter-menu-item" data-value="linkedin"><i class="bi bi-linkedin"
                                    style="color:#0a66c2;"></i> LinkedIn</div>
                            <div class="filter-menu-item" data-value="google"><i class="bi bi-google"></i> Google Ads
                            </div>
                        </div>
                    </div>

                    <div class="filter-dropdown">
                        <button class="filter-btn" data-filter="goals">Goals: All <i
                                class="bi bi-chevron-down"></i></button>
                        <div class="filter-menu" id="filter-goals">
                            <div class="filter-menu-header">Select goal</div>
                            <div class="filter-menu-item active" data-value="all"><i class="bi bi-circle-fill"
                                    style="font-size:0.5rem;"></i> All Goals</div>
                            <div class="filter-menu-divider"></div>
                            <div class="filter-menu-item" data-value="awareness"><i class="bi bi-eye-fill"
                                    style="color:var(--blue);"></i> Brand Awareness</div>
                            <div class="filter-menu-item" data-value="leads"><i class="bi bi-person-plus-fill"
                                    style="color:var(--primary);"></i> Lead Generation</div>
                            <div class="filter-menu-item" data-value="conversions"><i class="bi bi-cart-fill"
                                    style="color:var(--success);"></i> Conversions</div>
                            <div class="filter-menu-item" data-value="engagement"><i class="bi bi-heart-fill"
                                    style="color:var(--pink);"></i> Engagement</div>
                            <div class="filter-menu-item" data-value="traffic"><i class="bi bi-arrow-right-circle-fill"
                                    style="color:var(--orange);"></i> Traffic</div>
                        </div>
                    </div>

                    <div class="filter-dropdown">
                        <button class="filter-btn" data-filter="date"><i class="bi bi-calendar3"></i> Last 30 days <i
                                class="bi bi-chevron-down"></i></button>
                        <div class="filter-menu" id="filter-date">
                            <div class="filter-menu-header">Select period</div>
                            <div class="filter-menu-item" data-value="7"><i class="bi bi-calendar-week"></i> Last 7 days
                            </div>
                            <div class="filter-menu-item active" data-value="30"><i class="bi bi-calendar-month"></i>
                                Last 30 days</div>
                            <div class="filter-menu-item" data-value="90"><i class="bi bi-calendar3"></i> Last 90 days
                            </div>
                            <div class="filter-menu-divider"></div>
                            <div class="filter-menu-item" data-value="mtd"><i class="bi bi-calendar-range"></i> Month to
                                date</div>
                            <div class="filter-menu-item" data-value="ytd"><i class="bi bi-calendar-event"></i> Year to
                                date</div>
                            <div class="filter-menu-item" data-value="custom"><i class="bi bi-sliders"></i> Custom range
                            </div>
                        </div>
                    </div>

                    <div class="filter-dropdown">
                        <button class="filter-btn" data-filter="advanced"><i class="bi bi-funnel"></i> Filter <i
                                class="bi bi-chevron-down"></i></button>
                        <div class="filter-menu" id="filter-advanced" style="min-width:240px;">
                            <div class="filter-menu-header">Advanced filters</div>
                            <div class="filter-menu-item" data-value="roi"><i class="bi bi-graph-up-arrow"
                                    style="color:var(--success);"></i> ROI &gt; 2x</div>
                            <div class="filter-menu-item" data-value="budget"><i class="bi bi-cash-stack"
                                    style="color:var(--warning);"></i> Budget &gt; $100/day</div>
                            <div class="filter-menu-item" data-value="ctr"><i class="bi bi-cursor-fill"
                                    style="color:var(--blue);"></i> CTR &gt; 3%</div>
                            <div class="filter-menu-divider"></div>
                            <div class="filter-menu-item" data-value="manager"><i class="bi bi-person-fill"
                                    style="color:var(--primary);"></i> Manager: Louis</div>
                            <div class="filter-menu-item" data-value="status"><i class="bi bi-check-circle-fill"
                                    style="color:var(--success);"></i> Status: Active</div>
                            <div class="filter-menu-divider"></div>
                            <div class="filter-menu-item" style="color:var(--primary); font-weight:500;"><i
                                    class="bi bi-plus-lg"></i> Add custom filter</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ALERTS SECTION -->
            <div class="alerts-section" id="alertsSection">
                <div class="alerts-header">
                    <h5 class="alerts-title">
                        <i class="bi bi-exclamation-triangle-fill text-danger"></i> Alerts
                        <span class="count">3</span>
                    </h5>
                    <button class="btn-hide-alerts" id="hideAlerts">Hide</button>
                </div>
                <div class="row g-3" id="alertsContent">
                    <div class="col-md-4">
                        <div class="alert-card">
                            <div class="alert-card-icon"><i class="bi bi-exclamation-circle-fill"></i></div>
                            <div class="flex-grow-1">
                                <div class="alert-card-title">Budget Exceeded</div>
                                <div class="alert-card-text">Your campaign <strong>Lead generation</strong> has
                                    surpassed its budget limit.</div>
                                <div class="alert-card-actions">
                                    <a href="#" class="dismiss">Dismiss</a>
                                    <a href="#" class="learn">Learn more</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="alert-card">
                            <div class="alert-card-icon"><i class="bi bi-graph-down-arrow"></i></div>
                            <div class="flex-grow-1">
                                <div class="alert-card-title">CTR Dropped</div>
                                <div class="alert-card-text">The click-through rate (CTR) for <strong>Facebook
                                        clicks</strong> has decreased by 12%.</div>
                                <div class="alert-card-actions">
                                    <a href="#" class="dismiss">Dismiss</a>
                                    <a href="#" class="learn">Learn more</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="alert-card">
                            <div class="alert-card-icon"><i class="bi bi-flag-fill"></i></div>
                            <div class="flex-grow-1">
                                <div class="alert-card-title">Campaign Ended</div>
                                <div class="alert-card-text"><strong>Instagram pushes</strong> has reached its end date.
                                    Renew or analyze performance.</div>
                                <div class="alert-card-actions">
                                    <a href="#" class="dismiss">Dismiss</a>
                                    <a href="#" class="learn">Learn more</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- METRIC CARDS ROW -->
            <div class="row g-3 mb-4">
                <div class="col-md-6 col-xl-3">
                    <div class="metric-card">
                        <div class="metric-header">
                            <span class="metric-label">ROI</span>
                            <span class="metric-change positive"><i class="bi bi-arrow-up-right"></i> 230%</span>
                        </div>
                        <div class="metric-value"><span class="counter" data-target="12.5" data-prefix="+"
                                data-suffix="%">+0%</span></div>
                        <div class="metric-subtext">from 7.8% (last 30 days)</div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-3">
                    <div class="metric-card">
                        <div class="metric-header">
                            <span class="metric-label">CPL</span>
                            <span class="metric-change positive"><i class="bi bi-arrow-up-right"></i> 29%</span>
                        </div>
                        <div class="metric-value"><span class="counter" data-target="3.75" data-prefix="$"
                                data-suffix="">$0</span></div>
                        <div class="metric-subtext">from $4.60 (last 30 days)</div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-3">
                    <div class="metric-card">
                        <div class="metric-header">
                            <span class="metric-label">Total earned</span>
                            <span class="metric-change positive"><i class="bi bi-arrow-up-right"></i> 24%</span>
                        </div>
                        <div class="metric-value"><span class="counter" data-target="32250" data-prefix="$"
                                data-suffix="" data-format="currency">$0</span></div>
                        <div class="metric-subtext">from $24,500 (last 30 days)</div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-3">
                    <div class="metric-card">
                        <div class="metric-header">
                            <span class="metric-label">Total Budget Spent</span>
                            <span class="metric-change positive"><i class="bi bi-arrow-up-right"></i> 44%</span>
                        </div>
                        <div class="metric-value"><span class="counter" data-target="17000" data-prefix="$"
                                data-suffix="" data-format="currency">$0</span></div>
                        <div class="metric-subtext">of $30,000 (last 30 days)</div>
                    </div>
                </div>
            </div>

            <!-- PERFORMANCE + EXPENSES -->
            <div class="row g-3 mb-4">
                <div class="col-lg-8">
                    <div class="chart-card">
                        <div class="chart-header">
                            <div>
                                <h5 class="chart-title">Performance</h5>
                            </div>
                            <div class="d-flex gap-2 flex-wrap">
                                <button class="chart-filter">Assets <i class="bi bi-chevron-down"></i></button>
                                <button class="chart-filter">This year <i class="bi bi-chevron-down"></i></button>
                                <button class="chart-filter">All campaigns <i class="bi bi-chevron-down"></i></button>
                            </div>
                        </div>
                        <div class="chart-legend mb-3">
                            <div class="legend-item"><span class="legend-dot" style="background:#818cf8;"></span> ROI
                            </div>
                            <div class="legend-item"><span class="legend-dot" style="background:#34d399;"></span> CTR
                            </div>
                            <div class="legend-item"><span class="legend-dot" style="background:#fb923c;"></span> CPL
                            </div>
                        </div>
                        <svg viewBox="0 0 700 240" width="100%" height="240" preserveAspectRatio="none">
                            <line x1="50" y1="20" x2="680" y2="20" stroke="var(--border-light)" stroke-width="1" />
                            <line x1="50" y1="70" x2="680" y2="70" stroke="var(--border-light)" stroke-width="1" />
                            <line x1="50" y1="120" x2="680" y2="120" stroke="var(--border-light)" stroke-width="1" />
                            <line x1="50" y1="170" x2="680" y2="170" stroke="var(--border-light)" stroke-width="1" />
                            <line x1="50" y1="220" x2="680" y2="220" stroke="var(--border-light)" stroke-width="1" />

                            <text x="40" y="24" text-anchor="end" font-size="10" fill="var(--text-muted)"
                                font-weight="400">100</text>
                            <text x="40" y="74" text-anchor="end" font-size="10" fill="var(--text-muted)"
                                font-weight="400">80</text>
                            <text x="40" y="124" text-anchor="end" font-size="10" fill="var(--text-muted)"
                                font-weight="400">60</text>
                            <text x="40" y="174" text-anchor="end" font-size="10" fill="var(--text-muted)"
                                font-weight="400">40</text>
                            <text x="40" y="224" text-anchor="end" font-size="10" fill="var(--text-muted)"
                                font-weight="400">20</text>

                            <text x="70" y="238" text-anchor="middle" font-size="10" fill="var(--text-muted)"
                                font-weight="400">Jan</text>
                            <text x="125" y="238" text-anchor="middle" font-size="10" fill="var(--text-muted)"
                                font-weight="400">Feb</text>
                            <text x="180" y="238" text-anchor="middle" font-size="10" fill="var(--text-muted)"
                                font-weight="400">Mar</text>
                            <text x="235" y="238" text-anchor="middle" font-size="10" fill="var(--text-muted)"
                                font-weight="400">Apr</text>
                            <text x="290" y="238" text-anchor="middle" font-size="10" fill="var(--text-muted)"
                                font-weight="400">May</text>
                            <text x="345" y="238" text-anchor="middle" font-size="10" fill="var(--text-muted)"
                                font-weight="400">Jun</text>
                            <text x="400" y="238" text-anchor="middle" font-size="10" fill="var(--text-muted)"
                                font-weight="400">Jul</text>
                            <text x="455" y="238" text-anchor="middle" font-size="10" fill="var(--text-muted)"
                                font-weight="400">Aug</text>
                            <text x="510" y="238" text-anchor="middle" font-size="10" fill="var(--text-muted)"
                                font-weight="400">Sep</text>
                            <text x="565" y="238" text-anchor="middle" font-size="10" fill="var(--text-muted)"
                                font-weight="400">Oct</text>
                            <text x="620" y="238" text-anchor="middle" font-size="10" fill="var(--text-muted)"
                                font-weight="400">Nov</text>
                            <text x="670" y="238" text-anchor="middle" font-size="10" fill="var(--text-muted)"
                                font-weight="400">Dec</text>

                            <path class="chart-area"
                                d="M70,90 L125,85 L180,82 L235,78 L290,75 L345,70 L400,65 L455,60 L510,58 L565,55 L620,50 L670,45 L670,220 L70,220 Z"
                                fill="#818cf8" opacity="0.08" />
                            <path class="chart-line"
                                d="M70,90 L125,85 L180,82 L235,78 L290,75 L345,70 L400,65 L455,60 L510,58 L565,55 L620,50 L670,45"
                                fill="none" stroke="#818cf8" stroke-width="2.5" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path class="chart-line"
                                d="M70,140 L125,138 L180,135 L235,132 L290,130 L345,128 L400,125 L455,122 L510,120 L565,118 L620,115 L670,110"
                                fill="none" stroke="#34d399" stroke-width="2.5" stroke-linecap="round"
                                stroke-linejoin="round" style="animation-delay: 0.2s" />
                            <path class="chart-line"
                                d="M70,190 L125,188 L180,185 L235,180 L290,175 L345,170 L400,165 L455,160 L510,155 L565,150 L620,145 L670,138"
                                fill="none" stroke="#fb923c" stroke-width="2.5" stroke-linecap="round"
                                stroke-linejoin="round" style="animation-delay: 0.4s" />
                        </svg>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="chart-card">
                        <div class="chart-header">
                            <h5 class="chart-title">Expenses breakdown</h5>
                        </div>
                        <div class="d-flex justify-content-center my-3">
                            <svg viewBox="0 0 200 200" width="200" height="200">
                                <circle cx="100" cy="100" r="70" fill="none" stroke="#818cf8" stroke-width="30"
                                    stroke-dasharray="131.95 439.82" stroke-dashoffset="0"
                                    transform="rotate(-90 100 100)" class="chart-line" />
                                <circle cx="100" cy="100" r="70" fill="none" stroke="#34d399" stroke-width="30"
                                    stroke-dasharray="109.96 439.82" stroke-dashoffset="-131.95"
                                    transform="rotate(-90 100 100)" class="chart-line" style="animation-delay: 0.2s" />
                                <circle cx="100" cy="100" r="70" fill="none" stroke="#fb923c" stroke-width="30"
                                    stroke-dasharray="87.96 439.82" stroke-dashoffset="-241.91"
                                    transform="rotate(-90 100 100)" class="chart-line" style="animation-delay: 0.4s" />
                                <circle cx="100" cy="100" r="70" fill="none" stroke="#d1d5db" stroke-width="30"
                                    stroke-dasharray="43.98 439.82" stroke-dashoffset="-329.87"
                                    transform="rotate(-90 100 100)" class="chart-line" style="animation-delay: 0.6s" />
                                <circle cx="100" cy="100" r="70" fill="none" stroke="#f472b6" stroke-width="30"
                                    stroke-dasharray="43.98 439.82" stroke-dashoffset="-373.85"
                                    transform="rotate(-90 100 100)" class="chart-line" style="animation-delay: 0.8s" />
                                <circle cx="100" cy="100" r="70" fill="none" stroke="#fbbf24" stroke-width="30"
                                    stroke-dasharray="21.99 439.82" stroke-dashoffset="-417.83"
                                    transform="rotate(-90 100 100)" class="chart-line" style="animation-delay: 1s" />
                            </svg>
                        </div>
                        <div class="donut-legend" style="justify-content:flex-start; gap:0.75rem;">
                            <div class="donut-legend-item"><span class="legend-dot"
                                    style="background:#818cf8;"></span><span>Google Ads</span></div>
                            <div class="donut-legend-item"><span class="legend-dot"
                                    style="background:#34d399;"></span><span>Facebook</span></div>
                            <div class="donut-legend-item"><span class="legend-dot"
                                    style="background:#fb923c;"></span><span>Instagram</span></div>
                            <div class="donut-legend-item"><span class="legend-dot"
                                    style="background:#d1d5db;"></span><span>Email</span></div>
                            <div class="donut-legend-item"><span class="legend-dot"
                                    style="background:#f472b6;"></span><span>LinkedIn</span></div>
                            <div class="donut-legend-item"><span class="legend-dot"
                                    style="background:#fbbf24;"></span><span>X (Twitter)</span></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CAMPAIGN CARDS -->
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <div class="campaign-card">
                        <div class="campaign-card-header">
                            <div class="campaign-platform platform-facebook"><i class="bi bi-facebook"></i></div>
                            <span class="campaign-date">Last update: 18 Feb 2025</span>
                            <button class="campaign-menu-btn"><i class="bi bi-three-dots-vertical"></i></button>
                        </div>
                        <div class="campaign-card-body">
                            <div class="campaign-card-title">Growth Hack: The Secret to Boosting Engagement!</div>
                            <div class="campaign-card-image">
                                <img src="https://images.unsplash.com/photo-1552664730-d307ca884978?w=400&h=200&fit=crop"
                                    alt="Campaign">
                            </div>
                            <div class="campaign-dates">
                                <span>Start: 25 Jan 2025</span>
                                <span>End: 25 May 2025</span>
                            </div>
                            <div class="campaign-progress">
                                <div class="campaign-progress-fill" style="width: 0%;" data-width="65%"></div>
                            </div>
                            <div class="campaign-metrics">
                                <div class="campaign-metric"><i class="bi bi-eye"></i> <span>120k</span></div>
                                <div class="campaign-metric"><i class="bi bi-hand-thumbs-up"></i> <span>32k</span></div>
                                <div class="campaign-metric"><i class="bi bi-chat"></i> <span>7k</span></div>
                                <div class="campaign-metric"><i class="bi bi-share"></i> <span>12k</span></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="campaign-card">
                        <div class="campaign-card-header">
                            <div class="campaign-platform platform-instagram"><i class="bi bi-instagram"></i></div>
                            <span class="campaign-date">Last update: 04 Feb 2025</span>
                            <button class="campaign-menu-btn"><i class="bi bi-three-dots-vertical"></i></button>
                        </div>
                        <div class="campaign-card-body">
                            <div class="campaign-card-title">Unlock Exclusive Deals – Limited Time Only!</div>
                            <div class="campaign-card-image">
                                <img src="https://images.unsplash.com/photo-1529139574466-a303027c1d8b?w=400&h=200&fit=crop"
                                    alt="Campaign">
                            </div>
                            <div class="campaign-dates">
                                <span>Start: 12 Jan 2025</span>
                                <span>End: 1 Mar 2025</span>
                            </div>
                            <div class="campaign-progress">
                                <div class="campaign-progress-fill" style="width: 0%;" data-width="80%"></div>
                            </div>
                            <div class="campaign-metrics">
                                <div class="campaign-metric"><i class="bi bi-eye"></i> <span>82k</span></div>
                                <div class="campaign-metric"><i class="bi bi-hand-thumbs-up"></i> <span>7k</span></div>
                                <div class="campaign-metric"><i class="bi bi-chat"></i> <span>3k</span></div>
                                <div class="campaign-metric"><i class="bi bi-share"></i> <span>2k</span></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- METRICS WITH SPARKLINES + AUDIENCE -->
            <div class="row g-3 mb-4">
                <div class="col-lg-5">
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="metric-card">
                                <div class="metric-header">
                                    <span class="metric-label">Lead generation</span>
                                    <span class="metric-change positive"><i class="bi bi-arrow-up-right"></i> 28%</span>
                                </div>
                                <div class="metric-value"><span class="counter" data-target="2245" data-prefix=""
                                        data-suffix="" data-format="number">0</span></div>
                                <div class="metric-subtext">from 987 (last 30 days)</div>
                                <div class="sparkline">
                                    <svg viewBox="0 0 200 50" width="100%" height="50" preserveAspectRatio="none">
                                        <defs>
                                            <linearGradient id="sparkG1" x1="0" y1="0" x2="0" y2="1">
                                                <stop offset="0%" stop-color="#22c55e" stop-opacity="0.3" />
                                                <stop offset="100%" stop-color="#22c55e" stop-opacity="0" />
                                            </linearGradient>
                                        </defs>
                                        <path class="chart-area"
                                            d="M0,40 L20,38 L40,35 L60,30 L80,28 L100,22 L120,20 L140,15 L160,12 L180,8 L200,5 L200,50 L0,50 Z"
                                            fill="url(#sparkG1)" />
                                        <path class="chart-line"
                                            d="M0,40 L20,38 L40,35 L60,30 L80,28 L100,22 L120,20 L140,15 L160,12 L180,8 L200,5"
                                            fill="none" stroke="#22c55e" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="metric-card">
                                <div class="metric-header">
                                    <span class="metric-label">Sales Conversion</span>
                                    <span class="metric-change negative"><i class="bi bi-arrow-down-right"></i>
                                        12%</span>
                                </div>
                                <div class="metric-value"><span class="counter" data-target="393" data-prefix=""
                                        data-suffix="" data-format="number">0</span></div>
                                <div class="metric-subtext">from 568 (last 30 days)</div>
                                <div class="sparkline">
                                    <svg viewBox="0 0 200 50" width="100%" height="50" preserveAspectRatio="none">
                                        <defs>
                                            <linearGradient id="sparkG2" x1="0" y1="0" x2="0" y2="1">
                                                <stop offset="0%" stop-color="#ef4444" stop-opacity="0.3" />
                                                <stop offset="100%" stop-color="#ef4444" stop-opacity="0" />
                                            </linearGradient>
                                        </defs>
                                        <path class="chart-area"
                                            d="M0,10 L20,12 L40,15 L60,18 L80,20 L100,25 L120,28 L140,32 L160,35 L180,38 L200,42 L200,50 L0,50 Z"
                                            fill="url(#sparkG2)" />
                                        <path class="chart-line"
                                            d="M0,10 L20,12 L40,15 L60,18 L80,20 L100,25 L120,28 L140,32 L160,35 L180,38 L200,42"
                                            fill="none" stroke="#ef4444" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" style="animation-delay: 0.2s" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="metric-card">
                                <div class="metric-header">
                                    <span class="metric-label">Engagement</span>
                                    <span class="metric-change positive"><i class="bi bi-arrow-up-right"></i> 67%</span>
                                </div>
                                <div class="metric-value"><span class="counter" data-target="9026" data-prefix=""
                                        data-suffix="" data-format="number">0</span></div>
                                <div class="metric-subtext">from 2,873 (last 30 days)</div>
                                <div class="sparkline">
                                    <svg viewBox="0 0 200 50" width="100%" height="50" preserveAspectRatio="none">
                                        <defs>
                                            <linearGradient id="sparkG3" x1="0" y1="0" x2="0" y2="1">
                                                <stop offset="0%" stop-color="#22c55e" stop-opacity="0.3" />
                                                <stop offset="100%" stop-color="#22c55e" stop-opacity="0" />
                                            </linearGradient>
                                        </defs>
                                        <path class="chart-area"
                                            d="M0,45 L20,42 L40,38 L60,35 L80,30 L100,25 L120,22 L140,18 L160,12 L180,8 L200,3 L200,50 L0,50 Z"
                                            fill="url(#sparkG3)" />
                                        <path class="chart-line"
                                            d="M0,45 L20,42 L40,38 L60,35 L80,30 L100,25 L120,22 L140,18 L160,12 L180,8 L200,3"
                                            fill="none" stroke="#22c55e" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" style="animation-delay: 0.4s" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-7">
                    <div class="chart-card">
                        <div class="chart-header">
                            <div>
                                <h5 class="chart-title">Performance</h5>
                            </div>
                            <button class="chart-filter">Compare</button>
                        </div>
                        <div class="chart-legend mb-3">
                            <div class="legend-item"><span class="legend-dot" style="background:#818cf8;"></span> Lead
                                generation</div>
                            <div class="legend-item"><span class="legend-dot" style="background:#34d399;"></span> Sales
                                Conversion</div>
                            <div class="legend-item"><span class="legend-dot" style="background:#fb923c;"></span>
                                Engagement</div>
                        </div>
                        <svg viewBox="0 0 600 220" width="100%" height="220" preserveAspectRatio="none">
                            <line x1="40" y1="20" x2="580" y2="20" stroke="var(--border-light)" stroke-width="1" />
                            <line x1="40" y1="70" x2="580" y2="70" stroke="var(--border-light)" stroke-width="1" />
                            <line x1="40" y1="120" x2="580" y2="120" stroke="var(--border-light)" stroke-width="1" />
                            <line x1="40" y1="170" x2="580" y2="170" stroke="var(--border-light)" stroke-width="1" />

                            <text x="30" y="24" text-anchor="end" font-size="10" fill="var(--text-muted)"
                                font-weight="400">100</text>
                            <text x="30" y="74" text-anchor="end" font-size="10" fill="var(--text-muted)"
                                font-weight="400">60</text>
                            <text x="30" y="124" text-anchor="end" font-size="10" fill="var(--text-muted)"
                                font-weight="400">40</text>
                            <text x="30" y="174" text-anchor="end" font-size="10" fill="var(--text-muted)"
                                font-weight="400">20</text>

                            <text x="60" y="200" text-anchor="middle" font-size="10" fill="var(--text-muted)"
                                font-weight="400">2</text>
                            <text x="115" y="200" text-anchor="middle" font-size="10" fill="var(--text-muted)"
                                font-weight="400">6</text>
                            <text x="170" y="200" text-anchor="middle" font-size="10" fill="var(--text-muted)"
                                font-weight="400">10</text>
                            <text x="225" y="200" text-anchor="middle" font-size="10" fill="var(--text-muted)"
                                font-weight="400">14</text>
                            <text x="280" y="200" text-anchor="middle" font-size="10" fill="var(--text-muted)"
                                font-weight="400">18</text>
                            <text x="335" y="200" text-anchor="middle" font-size="10" fill="var(--text-muted)"
                                font-weight="400">22</text>
                            <text x="390" y="200" text-anchor="middle" font-size="10" fill="var(--text-muted)"
                                font-weight="400">26</text>
                            <text x="445" y="200" text-anchor="middle" font-size="10" fill="var(--text-muted)"
                                font-weight="400">30</text>

                            <path class="chart-line"
                                d="M60,100 L115,95 L170,90 L225,85 L280,75 L335,65 L390,55 L445,50 L500,45 L555,40"
                                fill="none" stroke="#818cf8" stroke-width="2.5" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path class="chart-line"
                                d="M60,130 L115,128 L170,125 L225,120 L280,115 L335,110 L390,108 L445,105 L500,103 L555,100"
                                fill="none" stroke="#34d399" stroke-width="2.5" stroke-linecap="round"
                                stroke-linejoin="round" style="animation-delay: 0.2s" />
                            <path class="chart-line"
                                d="M60,160 L115,158 L170,155 L225,150 L280,145 L335,140 L390,135 L445,132 L500,130 L555,128"
                                fill="none" stroke="#fb923c" stroke-width="2.5" stroke-linecap="round"
                                stroke-linejoin="round" style="animation-delay: 0.4s" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- AUDIENCE DONUT -->
            <div class="row g-3 mb-4">
                <div class="col-lg-4">
                    <div class="chart-card">
                        <div class="chart-header">
                            <h5 class="chart-title">Audience</h5>
                            <button class="chart-filter">Gender <i class="bi bi-chevron-down"></i></button>
                        </div>
                        <div class="d-flex justify-content-center my-3">
                            <svg viewBox="0 0 200 200" width="220" height="220">
                                <circle cx="100" cy="100" r="70" fill="none" stroke="#818cf8" stroke-width="35"
                                    stroke-dasharray="197.92 439.82" stroke-dashoffset="0"
                                    transform="rotate(-90 100 100)" class="chart-line" />
                                <circle cx="100" cy="100" r="70" fill="none" stroke="#f472b6" stroke-width="35"
                                    stroke-dasharray="175.93 439.82" stroke-dashoffset="-197.92"
                                    transform="rotate(-90 100 100)" class="chart-line" style="animation-delay: 0.2s" />
                                <circle cx="100" cy="100" r="70" fill="none" stroke="#e5e7eb" stroke-width="35"
                                    stroke-dasharray="65.97 439.82" stroke-dashoffset="-373.85"
                                    transform="rotate(-90 100 100)" class="chart-line" style="animation-delay: 0.4s" />
                            </svg>
                        </div>
                        <div class="donut-legend">
                            <div class="donut-legend-item"><span class="legend-dot"
                                    style="background:#818cf8;"></span><span>Male</span></div>
                            <div class="donut-legend-item"><span class="legend-dot"
                                    style="background:#f472b6;"></span><span>Female</span></div>
                            <div class="donut-legend-item"><span class="legend-dot"
                                    style="background:#e5e7eb;"></span><span>Unspecified</span></div>
                        </div>
                    </div>
                </div>

                <!-- RECENT POSTS TABLE -->
                <div class="col-lg-8">
                    <div class="posts-card">
                        <div class="posts-header">
                            <div>
                                <h5 class="posts-title">Recent posts <span class="count" id="postCount">4</span></h5>
                                <div class="posts-subtitle">View key metrics for all active campaigns.</div>
                            </div>
                            <div class="d-flex gap-2 align-items-center">
                                <button class="btn-add-record" id="addRecordBtn">
                                    <i class="bi bi-plus-lg"></i> Add record
                                </button>
                                <a href="#" class="btn-view-posts">View posts <i class="bi bi-arrow-right"></i></a>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-jc align-middle" id="postsTable">
                                <thead>
                                    <tr>
                                        <th>Post</th>
                                        <th>Audience</th>
                                        <th>ROI</th>
                                        <th>CTR</th>
                                        <th>CPL</th>
                                        <th>Budget</th>
                                        <th>Manager</th>
                                        <th>Ação</th>
                                    </tr>
                                </thead>
                                <tbody id="postsBody">
                                    <tr data-id="1">
                                        <td class="post-title-cell"><i class="bi bi-instagram"></i> Growth Hack: The
                                            Secret to Boosting Engagement!</td>
                                        <td class="fw-semibold">120,000</td>
                                        <td><span class="roi-badge">3.2x</span></td>
                                        <td>2.9%</td>
                                        <td>$2.90</td>
                                        <td>$100/day</td>
                                        <td>
                                            <div class="manager-cell">
                                                <div class="manager-avatar avatar-lj">LJ</div>
                                                <div>
                                                    <p class="manager-name">Louis Jensen</p>
                                                    <p class="manager-role">SMM manager</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="action-dropdown">
                                                <button class="action-btn" data-action="1"><i
                                                        class="bi bi-three-dots-vertical"></i></button>
                                                <div class="action-menu" id="action-menu-1">
                                                    <button class="action-menu-item" data-act="view"><i
                                                            class="bi bi-eye"></i> View</button>
                                                    <button class="action-menu-item" data-act="edit"><i
                                                            class="bi bi-pencil"></i> Edit</button>
                                                    <button class="action-menu-item danger" data-act="delete"><i
                                                            class="bi bi-trash3"></i> Delete</button>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr data-id="2">
                                        <td class="post-title-cell"><i class="bi bi-instagram"></i> Unlock Exclusive
                                            Deals – Limited Time Only!</td>
                                        <td class="fw-semibold">80,000</td>
                                        <td><span class="roi-badge">2.1x</span></td>
                                        <td>3.8%</td>
                                        <td>$1.50</td>
                                        <td>$80/day</td>
                                        <td>
                                            <div class="manager-cell">
                                                <div class="manager-avatar avatar-lj">LJ</div>
                                                <div>
                                                    <p class="manager-name">Louis Jensen</p>
                                                    <p class="manager-role">SMM manager</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="action-dropdown">
                                                <button class="action-btn" data-action="2"><i
                                                        class="bi bi-three-dots-vertical"></i></button>
                                                <div class="action-menu" id="action-menu-2">
                                                    <button class="action-menu-item" data-act="view"><i
                                                            class="bi bi-eye"></i> View</button>
                                                    <button class="action-menu-item" data-act="edit"><i
                                                            class="bi bi-pencil"></i> Edit</button>
                                                    <button class="action-menu-item danger" data-act="delete"><i
                                                            class="bi bi-trash3"></i> Delete</button>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr data-id="3">
                                        <td class="post-title-cell"><i class="bi bi-instagram"></i> Giveaway Time! Tag a
                                            Friend & Win Big!</td>
                                        <td class="fw-semibold">95,000</td>
                                        <td><span class="roi-badge">2.8x</span></td>
                                        <td>2.2%</td>
                                        <td>$3.10</td>
                                        <td>$90/day</td>
                                        <td>
                                            <div class="manager-cell">
                                                <div class="manager-avatar avatar-lj">LJ</div>
                                                <div>
                                                    <p class="manager-name">Louis Jensen</p>
                                                    <p class="manager-role">SMM manager</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="action-dropdown">
                                                <button class="action-btn" data-action="3"><i
                                                        class="bi bi-three-dots-vertical"></i></button>
                                                <div class="action-menu" id="action-menu-3">
                                                    <button class="action-menu-item" data-act="view"><i
                                                            class="bi bi-eye"></i> View</button>
                                                    <button class="action-menu-item" data-act="edit"><i
                                                            class="bi bi-pencil"></i> Edit</button>
                                                    <button class="action-menu-item danger" data-act="delete"><i
                                                            class="bi bi-trash3"></i> Delete</button>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr data-id="4">
                                        <td class="post-title-cell"><i class="bi bi-instagram"></i> New Product Drop!
                                        </td>
                                        <td class="fw-semibold">50,000</td>
                                        <td><span class="roi-badge">1.5x</span></td>
                                        <td>1.9%</td>
                                        <td>$4.20</td>
                                        <td>$50/day</td>
                                        <td>
                                            <div class="manager-cell">
                                                <div class="manager-avatar avatar-ej">EJ</div>
                                                <div>
                                                    <p class="manager-name">Emily Jones</p>
                                                    <p class="manager-role">SMM Specialist</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="action-dropdown">
                                                <button class="action-btn" data-action="4"><i
                                                        class="bi bi-three-dots-vertical"></i></button>
                                                <div class="action-menu" id="action-menu-4">
                                                    <button class="action-menu-item" data-act="view"><i
                                                            class="bi bi-eye"></i> View</button>
                                                    <button class="action-menu-item" data-act="edit"><i
                                                            class="bi bi-pencil"></i> Edit</button>
                                                    <button class="action-menu-item danger" data-act="delete"><i
                                                            class="bi bi-trash3"></i> Delete</button>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- PAGINATION -->
                        <div class="pagination-custom">
                            <div class="pagination-info">
                                Showing <strong>1</strong> to <strong id="showingCount">4</strong> of <strong
                                    id="totalCount">4</strong> results
                            </div>
                            <div class="pagination-controls">
                                <button class="page-btn nav-btn" id="prevPage" disabled>
                                    <i class="bi bi-chevron-left"></i>
                                </button>
                                <button class="page-btn active" data-page="1">1</button>
                                <button class="page-btn" data-page="2">2</button>
                                <button class="page-btn" data-page="3">3</button>
                                <button class="page-btn nav-btn" id="nextPage">
                                    <i class="bi bi-chevron-right"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

@endsection

@push('scripts')
<script>
</script>
@endpush