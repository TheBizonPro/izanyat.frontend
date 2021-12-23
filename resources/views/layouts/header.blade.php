<div id="not_identified_alert" class="p-2 text-center bg-danger text-white border-top" hidden>
    <b class="fad fa- me-2"></b> Вы получите доступ к полному функционалу личного кабинета после прохождения
    идентификации через Sign.Me!<br>
    <button id="sign_me_complete_identify_btn" class="btn btn-danger text-white mt-3">Нажмите сюда для проверки статуса
        идентификации</button>
</div>

<div id="not_npd_alert" class="p-2 text-center bg-danger text-white border-top" hidden>
    <b class="fad fa- me-2"></b> Вы получите доступ к полному функционалу личного кабинета после привязки к партнеру "Я
    Занят" в приложении "Мой Налог" для самозанятых.<br>
    <a href="/my/integrations/npd" class="btn btn-danger text-white mt-3">Перейти на страницу привязки к Партнеру</a>
</div>

<header class="navbar navbar-expand-md d-print-none navbar-light">
    <div class="container-xl">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-menu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <a href="/" class="navbar-brand navbar-brand-autodark d-none-navbar-horizontal pr-0 pr-md-3">
            <img src="/images/logo.svg" width="100">
        </a>
        <div class="navbar-nav flex-row order-md-last">
            <div class="nav-item d-none d-md-flex me-3">
                <div class="btn-list">


                    <button id="balance_btn" class="btn btn-sm btn-light py-2 me-4" hidden>
                        Баланс <b id="balance" class="mx-1">...</b> руб
                    </button>

                    <!-- <button id="request_signs_documents_btn" class="btn btn-sm btn-light py-2 me-4" hidden>
						<b class="fal fa-file me-2"></b> На подпись <span  id="unrequested_signs_count_badge" class="ms-2 badge bg-green"></span>
					</button> -->

                    <a href="/notifications" class="btn btn-sm btn-light py-2 me-4">
                        <b class="fal fa-bell me-2"></b> Уведомления <span id="new_notifications_badge"
                            class="ms-2 badge bg-green" hidden></span>
                    </a>
                </div>
            </div>
            <div class="nav-item dropdown">
                <a href="#" class="nav-link d-flex lh-1 text-reset p-0" data-bs-toggle="dropdown">
                    <div class="d-none d-xl-block ps-2">
                        <div id="user_name">...</div>
                        <div id="user_info" class="mt-1 small text-muted">...</div>
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">

                    <a id="menu-profile-contractor" href="/my" class="dropdown-item" hidden><b
                            class="fal fa-user me-2"></b> Профиль</a>
                    <a id="menu-profile-client" href="/my-company-profile" class="dropdown-item" hidden><b
                            class="fal fa-user me-2"></b> Профиль</a>
                    <a id="menu-knowledge-base" href="/knowledge-base" class="dropdown-item"><b
                            class="fal fa-brain me-2"></b> База знаний</a>

                    <div class="dropdown-divider"></div>
                    <a href="/logout" class="dropdown-item"><b class="fal fa-sign-out me-2"></b> Выйти</a>
                </div>
            </div>
        </div>
    </div>
</header>

<div class="navbar-expand-md" id="main_menu" hidden>
    <div class="collapse navbar-collapse" id="navbar-menu">
        <div class="navbar navbar-light">
            <div class="container-xl">
                <ul id="client_menu" hidden class="navbar-nav">
                    <li class="nav-item" id="all-projects-button" hidden>
                        <a href="/client/projects" class="nav-link"><b class="fad fa-folder me-2"></b> Мои проекты</a>
                    </li>
                    <li class="nav-item">
                        <a href="/client/contractors/all" class="nav-link"><b class="fad fa-users-class me-2"></b> База
                            исполнителей</a>
                    </li>

                    <li class="nav-item">
                        <a href="/my-company" class="nav-link"><b class="fad fa-user me-2"></b> Профиль</a>
                    </li>

                    <li class="nav-item" id="all-payouts-button" hidden>
                        <a href="/client/payouts" class="nav-link"><b class="fad fa-money-check-edit me-2"></b>
                            Платежи</a>
                    </li>
                    <li class="nav-item" id="all-documents-button" hidden>
                        <a href="/company/documents" class="nav-link"><b class="fad fa-file-alt me-2"></b> Все
                            документы</a>
                    </li>
                </ul>
                <ul id="contractor_menu" hidden class="navbar-nav">
                    <li class="nav-item">
                        <a href="/contractor/tasks/new" class="nav-link"><b class="fad fa-bars me-2"></b> Все
                            предложения</a>
                    </li>
                    <li class="nav-item">
                        <a href="/contractor/tasks/my" class="nav-link"><b class="fad fa-tasks me-2"></b> Мои задачи</a>
                    </li>
                    <!-- <li class="nav-item">
						<a href="/contractor/npd-attach"  class="nav-link"><b class="fad fa-link me-2"></b> Привязка к "Мой Налог"</a>
					</li> -->
                    <li class="nav-item">
                        <a href="/documents" class="nav-link"><b class="fad fa-file-alt me-2"></b> Мои документы</a>
                    </li>
                    <li class="nav-item">
                        <a href="/contractor/payouts" class="nav-link"><b class="fad fa-money-check-edit me-2"></b> Мои
                            платежи</a>
                    </li>
                    <!-- <li class="nav-item">
						<a href="/my" class="nav-link"><b class="fad fa-user me-2"></b> Профиль</a>
					</li> -->
                </ul>
            </div>
        </div>
    </div>
</div>
