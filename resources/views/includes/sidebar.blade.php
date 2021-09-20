        <aside class="left-sidebar">
            <!-- Sidebar scroll-->
            <div class="scroll-sidebar">
                <!-- User profile -->
                @if( Auth::check() )
                <div class="user-profile">
                    <!-- User profile image -->
                    <div class="profile-img">

                        @if(Auth::user()->avatar != '' && File::exists(public_path().'/assets/uploads/admins/'.Auth::user()->avatar) )
                             <img src="{{ asset('/assets/uploads/admins/'.Auth::user()->avatar) }}"/>
                        @else
                             <img  src="{{ asset('assets/uploads/avatar.png') }}"/>
                        @endif
                             <!-- this is blinking heartbit-->
                            <div class="notify setpos"> <span class="heartbit"></span> <span class="point"></span> </div>
                    </div>
                    <!-- User profile text-->

                    <div class="profile-text">
                        <h5> {{ Auth::user()->name }}  {{ Auth::user()->prenom }}</h5>

                        <a href="#" class="dropdown-toggle u-dropdown" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="true"><i class="mdi mdi-settings"></i></a>


                        <div class="dropdown-menu animated flipInY" style="width:220px">
                        <!-- text-->
                        <a  href="{{ route('admin.account') }}" class="dropdown-item"> <i class="ti-user"></i> &nbsp;Gérer mon compte</a>
                        <!-- text-->
                        <!-- <a href="#" class="dropdown-item"><i class="ti-wallet"></i> My Balance</a> -->
                        <!-- text-->
                        <!-- <a href="#" class="dropdown-item"><i class="ti-email"></i> Inbox</a> -->
                        <!-- text-->
                        <!-- <div class="dropdown-divider"></div> -->
                        <!-- text-->
                        <!-- <a href="#" class="dropdown-item"><i class="ti-settings"></i> Account Setting</a> -->
                        <!-- text-->
                        <div class="dropdown-divider"></div>
                        <!-- text-->
                        <li>
                            <a href="{{ route('logout') }}" class="dropdown-item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fa fa-power-off"></i> &nbsp;Déconnexion</a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </li>

                        <!-- text-->
                        </div>
                    </div>

                </div>
                 @endif
                <!-- End User profile text-->
                <!-- Sidebar navigation-->
                <nav class="sidebar-nav">
                    <ul id="sidebarnav">
                        <li class="nav-devider"></li>
                        <li> <a class="waves-effect waves-dark" href="{{ route('admin.dashboard') }}" aria-expanded="false"><i class="mdi mdi-gauge"></i><span class="hide-menu">Dashboard</span></a></li>

                        @if( Auth::user() && Auth::user()->is_admin == 1 )
                            <!-- <li class="nav-small-cap">Paramétrage</li> -->
                            @if(Auth::user()->is_super_admin == 1)
                                <li class="{{ active_route('admins.*') }}">
                                        <a class="waves-effect waves-dark {{ active_route('admins.*') }}" href="{{ route('admins.index') }}" aria-expanded="false"><i class="mdi mdi-account-key"></i><span class="hide-menu">Administrateurs</span></a>
                                </li>
                            @endif
                            <li>
                                <a class="waves-effect has-arrow  waves-dark" href="#displayMenu" data-toggle="collapse" aria-expanded="false"><i class="mdi mdi-settings"></i><span class="hide-menu">Evènements</span></a>
                                <ul class="collapse list-unstyled" id="displayMenu" aria-expanded="false">
                                    <li>
                                        <a class="waves-effect waves-dark " href="{{ route('events.index') }}" aria-expanded="false"><i class="mdi mdi-calendar"></i><span class="hide-menu">&nbsp; Evènements</span></a>
                                    </li>
                                    <li>
                                        <a class="waves-effect waves-dark " href="{{ route('projects.index') }}" aria-expanded="false"><i class="mdi mdi-lightbulb-on"></i><span class="hide-menu"> &nbsp; Projets</span></a>
                                    </li>
                                    <li>
                                        <a class="waves-effect waves-dark " href="{{ route('judges.index') }}" aria-expanded="false"><i class="mdi mdi-gavel"></i><span class="hide-menu"> &nbsp; Jury</span></a>
                                    </li>

                                </ul>
                            </li>

                            <li>
                                        <a class="waves-effect waves-dark "  href="{{ route('questions.index') }}" aria-expanded="false"><i class="mdi mdi-comment-question-outline"></i><span >Questions</span></a>
                                </li>
                            <li>
                                <a class="waves-effect has-arrow  waves-dark" href="#displayMenu2" data-toggle="collapse" aria-expanded="false"><i class="mdi mdi-cards"></i><span class="hide-menu">Evaluation</span></a>
                                <ul class="collapse list-unstyled" id="displayMenu2" aria-expanded="false">
                                    <li>
                                        <a class="waves-effect waves-dark " href="{{ route('categories.index') }}" aria-expanded="false"><i class="mdi mdi-notification-clear-all"></i><span class="hide-menu"> &nbsp; Catégories</span></a>
                                    </li>
                                    <li>
                                        <a class="waves-effect waves-dark pr-3 " href="{{ route('percentages.index') }}" aria-expanded="false" id="href_percentage"><i class="mdi mdi-percent"></i><span class="hide-menu"> &nbsp; Pourcentages</span>
                                            @if($data_sidebar['somme'] < 100)
                                            <span class="label label-rouded label-danger pull-right" id="sum_percentage">{{ $data_sidebar['somme'] }} %</span>
                                            @endif
                                        </a>
                                    </li>
                                    <li >
                                        <a class="waves-effect waves-dark" href="{{ route('criterias.index') }}" aria-expanded="false"><i class="mdi mdi-comment-check-outline"></i><span class="hide-menu"> &nbsp; Critères</span></a>
                                    </li>
                                    @if(count($data_sidebar['percentages']) > 0)
                                    <li>
                                        <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="mdi mdi-bullseye"></i><span class="hide-menu"><span class="hide-menu"> &nbsp; Notes</span></a>
                                        <ul aria-expanded="false" class="collapse">
                                            @foreach($data_sidebar['percentages'] as $persentage_side)
                                            <li><a class="{{ active_route('notes/'.$persentage_side->id) }}" href="{{ route('notes.backoffice', array('id'=>$persentage_side->id))}}">{{ $persentage_side->titre_fr }}</a></li>
                                            @endforeach
                                        </ul>
                                    </li>
                                    @endif
                                </ul>
                            </li>
                        @endif


                    </ul>
                </nav>
                <!-- End Sidebar navigation -->
            </div>
            <!-- End Sidebar scroll-->
        </aside>
