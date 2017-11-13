<nav id="page-leftbar" role="navigation">
                <!-- BEGIN SIDEBAR MENU -->
            <ul class="acc-menu" id="sidebar">
                <li id="search">
                    <a href="javascript:;"><i class="fa fa-search opacity-control"></i></a>
                     <form>
                        <input type="text" class="search-query" placeholder="Search...">
                        <button type="submit"><i class="fa fa-search"></i></button>
                    </form>
                </li>
                <li class="divider"></li>
                <li><a href="{{ route('company.index') }}"><i class="fa fa-home"></i> <span>Company</span></a></li>
                <li><a href="{{ route('security_question.index') }}"><i class="fa fa-home"></i> <span>Security Question</span></a></li>
                <li><a href="{{ route('employee.index') }}"><i class="fa fa-home"></i> <span>Employee</span></a></li>
                <li><a href="{{ route('user.index') }}"><i class="fa fa-home"></i> <span>User</span></a></li>
            </ul>
            <!-- END SIDEBAR MENU -->
        </nav>