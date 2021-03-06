@extends('layouts.master')

@section('title')
База знаний
@stop

@section('styles')
<style type="text/css">
    /* Style the buttons that are used to open and close the accordion panel */
    .accordion {
        background-color: #eee;
        color: #444;
        cursor: pointer;
        padding: 18px;
        width: 100%;
        border: none;
        text-align: left;
        outline: none;
        font-size: 15px;
        transition: 0.4s;
    }

    .accordion:after {
        content: '\002B';
        color: #777;
        font-weight: bold;
        float: right;
        margin-left: 5px;
    }

    /* Add a background color to the button if it is clicked on (add the .active class with JS), and when you move the mouse over it (hover) */
    .active,
    .accordion:hover {
        background-color: #ccc;
    }

    .active:after {
        content: "\2212";
    }

    /* Style the accordion panel. Note: hidden by default */
    .panel {
        padding: 0 18px;
        background-color: white;
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.2s ease-out;
    }
</style>
@stop

@section('content')

<div class="page-wrapper">
    <div class="container-xl">
        <!-- Page title -->
        <div class="page-header d-print-none mt-4">
            <div class="row align-items-center">
                <div class="col">
                    <!-- Page pre-title -->
                    <div id="project_name" class="page-pretitle"></div>
                    <h2 class="page-title">
                        <b class="fal fa-book me-2"></b> База знаний
                    </h2>
                </div>
            </div>
        </div>



        <div class="row mb-3" id="client_part" hidden>
            <div class="col-md-12 mx-auto">

                <div class="accordion">1. Как пройти идентификацию в сервисе</div>
                <div class="panel">
                    <div class="p-3">
                        <p>
                            Для прохождения идентификации свяжитесь с нами, написав на info@izanyat.ru или позвонив на
                            +7 (499) 705-80-70 для уточнения деталей.
                        </p>
                        <p>Предоставьте подтверждающие документы компании:</p>
                        <ol>
                            <li>Выписка из ЕГРЮЛ;</li>
                            <li>Доверенность от имени организации на конкретное физическое лицо(несколько лиц), которому
                                даётся </li>возможность подписывать документы от имени организации;
                            <li>Решение/Протокол уполномоченного органа управления юридического лица о назначении
                                руководителя </li>юридического лица;
                            <li>Устав.</li>
                            <li>Подготовить документы на доверенное лицо от компании: Паспорт, СНИЛС, ИНН</li>
                        </ol>
                    </div>
                </div>


                <div class="accordion">2. Как встать на учет в качестве НПД?</div>
                <div class="panel">
                    <div class="p-3">
                        <p>
                            Скачайте мобильное приложение "Мой налог" и следуйте указаниям.
                        </p>
                    </div>
                </div>

                <div class="accordion">3. Как обратиться в техническую поддержку</div>
                <div class="panel">
                    <div class="p-3">
                        <p>
                            "Обратиться в техническую поддержку можно написав на info@izanyat.ru. Тел. +7 (499)
                            705-80-70"
                        </p>
                    </div>
                </div>


                <div class="accordion">4. Как общаться по Заданиям</div>
                <div class="panel">
                    <div class="p-3">
                        <p>
                            У Закакзчика и исполнителя в личных данных выводятся контактный номер телефона и Email.
                            Используя эти данные вы сможете связаться друг с другом.
                        </p>
                    </div>
                </div>


                <div class="accordion">5.Как создать Задание</div>
                <div class="panel">
                    <div class="p-3">
                        <p>
                            Для создания задания зайдите в раздел Мои проекты - Создайте проект вкладке и нажмите кнопку
                            "Создать задачу, заполните всю запрашиваемую информацию по заданию. После создания задачи -
                            она станет доступна для исполнителей.
                        </p>
                        <p>
                            <img class="img" src="images/knowledge-base/client4.png" width="100%">
                        </p>
                    </div>
                </div>


                <div class="accordion">6. Какие Задания запрещено размещать на сервисе </div>
                <div class="panel">
                    <div class="p-3">
                        <p>
                            Нельзя создавать задания, противоречащие закону РФ. Мы удалим задание, если для его
                            выполнения нужно:
                        </p>
                        <ul>
                            <li>Предоставить или получить займ, пополнить счёт в платежных системах, перевести деньги.
                            </li>
                            <li>Привлечь пользователей на сторонние ресурсы, сайты, зарегистрироваться на таких
                                ресурсах.</li>
                            <li>Рекламировать и распространять услуги и товары (собственные или принадлежащие третьим
                                лицам).</li>
                            <li>Накручивать или изменять статистику сайтов, число подписчиков в социальных сетях.</li>
                            <li>Автоматически или вручную рассылать приглашения и сообщения пользователям в социальных
                                сетей, по электронной почте.</li>
                            <li>Оказывать услуги эротического характера.</li>
                            <li>Помогать со сдачей экзаменов/тестов/отчетных работ онлайн или сдавать их вместо другого
                                человека.</li>
                            <li>Покупать рецептурные препараты без рецепта.</li>
                            <li>Работать с криптовалютами, майнить.</li>
                            <li>Заниматься привлечением в сетевой бизнес.</li>
                            <li>Подделывать документы, печати, официальные бумаги, медицинские справки и др."</li>
                        </ul>

                    </div>
                </div>


                <div class="accordion">7. Как изменить номер телефона или почту</div>
                <div class="panel">
                    <div class="p-3">
                        <p>
                            Для изменения контактных данных следует обратиться в техническую поддержку, написав на
                            info@izanyat.ru. Тел. +7 (499) 705-80-70
                        </p>
                    </div>
                </div>


                <div class="accordion">8. Как предложить Задание конкретному Исполнителю</div>
                <div class="panel">
                    <div class="p-3">
                        <p>
                            Пригласите понравившегося исполнителя выполнить ваше задание.
                        </p>
                        <p>
                            <img class="img" src="images/knowledge-base/client7.png" width="100%">
                        </p>
                    </div>
                </div>


                <div class="accordion">9. Как принять выполненные Задания у Исполнителя?</div>
                <div class="panel">
                    <div class="p-3">
                        <p>
                            После того, как исполнитель закончит выполнение задания заказчик проверяет задание в
                            соответствии оговорённым требованиям, после этого заказчик может либо "Принять и оплатить"
                            задание, либо "Отправить на доработку", отправив задание обратно исполнителя в статусе "В
                            работе".
                        </p>
                    </div>
                </div>


                <div class="accordion">10. Что такое чек и для чего он нужен</div>
                <div class="panel">
                    <div class="p-3">
                        <p>
                            Самозанятые исполнители платят 4% с выручки, полученной от физических лиц, 6% — от компаний.
                            Для того чтобы платить налог требуется отображать полученный доход.
                        </p>
                        <p>
                            Работая с заказчиками через платформу "ЯЗанят исполнителям не придётся самостоятельно
                            создавать чек в приложении "Мой Налог", за них всё сделает платформа автоматически после
                            перечисления денежных средств исполнителю за выполненное задание.
                        </p>
                        <p>
                            После того, как платформа создаст чек - в вашем приложении Мой Налог" будет отображаться
                            сумма налога, которую нужно будет оплатить.
                        </p>
                    </div>
                </div>


                <div class="accordion">11. Как получить чек за оплаченное Задание</div>
                <div class="panel">
                    <div class="p-3">
                        <p>
                            Вы можете скачать чек в разделе "Платежи"
                        </p>
                        <p>
                            <img class="img" src="images/knowledge-base/client10.png" width="100%">
                        </p>
                    </div>
                </div>


                <div class="accordion">12. Чек получен с ошибкой, что можно сделать?</div>
                <div class="panel">
                    <div class="p-3">
                        <p>
                            В случае, если вы не согласны с содержанием Чека, сформированного Оператором и загруженного
                            в Личный кабинет, вы можете сообщить об этом Оператору «ЯЗанят» посредством направления
                            уведомления на адрес электронной почты info@izanyat.ru письмом с темой "Чеки". В таком
                            письме должны быть указаны ФИО, ИНН Пользователя, дата и номер Заказа, причина несогласия с
                            Чеком (строго одна из двух причин - ошибочные данные в Чеке или возврат денежных средств).
                        </p>
                    </div>
                </div>


                <div class="accordion">13. Как аннулировать чек</div>
                <div class="panel">
                    <div class="p-3">
                        <p>
                            Обратитесь в техническую поддержку, написав на info@izanyat.ru. Письмом с темой "Чеки". В
                            таком письме должны быть указаны ФИО, ИНН Пользователя, дата и номер Заказа, причина
                            несогласия с Чеком (строго одна из двух причин - ошибочные данные в Чеке или возврат
                            денежных средств).
                        </p>
                    </div>
                </div>
            </div>
        </div>




        <div class="row mb-3" id="contractor_part" hidden>
            <div class="col-md-12 mx-auto">


                <div class="accordion">1. Как обратиться в техническую поддержку</div>
                <div class="panel">
                    <div class="p-3">
                        <p>
                            Обратиться в техническую поддержку можно написав на info@izanyat.ru или позвоните по номеру
                            +7 (499) 705-80-70.
                        </p>
                    </div>
                </div>

                <div class="accordion">2. Как встать на учет в качестве НПД?</div>
                <div class="panel">
                    <div class="p-3">
                        <p>
                        <p>
                            Скачать приложение «Мой Налог» Для <a
                                href="https://apps.apple.com/ru/app/%D0%BC%D0%BE%D0%B9-%D0%BD%D0%B0%D0%BB%D0%BE%D0%B3/id1437518854?l=en">iOS</a>,
                            для
                            <a
                                href="https://play.google.com/store/apps/details?id=com.gnivts.selfemployed&hl=ru&gl=US">Android</a>
                            и пройти регистрацию по инструкции ниже. Вам понадобится ИНН и паспорт или подтвержденная
                            учетная запись на Госуслугах.
                        </p>

                        <p>
                            Самый быстрый процесс регистрации, если у Вас уже есть личный кабинет налогоплательщика –
                            физического лица, тогда Вам понадобится только ИНН и пароль от ЛК физлица. После установки
                            мобильного приложения «Мой налог» на свой телефон или планшет, достаточно войти в мобильное
                            приложение «Мой налог» и выбрать режим регистрации «Через ЛК физического лица», подтвердить
                            свой номер мобильного телефона и выбрать регион осуществления деятельности. В случае
                            отсутствия у Вас личного кабинета налогоплательщика – физического лица, можно использовать
                            Ваш логин и пароль от Портала государственных услуг Российской Федерации.
                        </p>

                        <p>
                            Если Вы не пользуетесь личным кабинетом физического лица, то в процессе регистрации
                            добавится шаг, на котором мобильное приложение «Мой налог» попросит отсканировать Ваш
                            паспорт гражданина России и сделать собственную фотографию (селфи). Процесс регистрации
                            очень прост и сопровождается подробными разъяснениями и подсказками на каждом этапе.
                        </p>
                        </p>
                    </div>
                </div>


                <div class="accordion">3. Как общаться по Заданиям</div>
                <div class="panel">
                    <div class="p-3">
                        <p>
                            У Закакзчика и исполнителя в личных данных выводятся контактный номер телефона и Email.
                            Используя эти данные вы сможете связаться друг с другом.
                        </p>
                    </div>
                </div>


                <div class="accordion">4. Как стать Исполнителем - что нужно сделать, чтобы начать брать Заказы</div>
                <div class="panel">
                    <div class="p-3">
                        <p>
                            Для того, чтобы стать исполнителем в платформе вам потребуется:
                        </p>
                        <ol>
                            <li>ИНН. Если у вас нет ИНН - необходимо его получить</li>
                            <li>Получить статус самозанятого<br>
                                Скачать приложение «Мой Налог» <a
                                    href="https://apps.apple.com/ru/app/%D0%BC%D0%BE%D0%B9-%D0%BD%D0%B0%D0%BB%D0%BE%D0%B3/id1437518854?l=en">Для
                                    iOS</a>, <a
                                    href="https://play.google.com/store/apps/details?id=com.gnivts.selfemployed&hl=ru&gl=US">для
                                    Android</a> и пройти регистрацию по <a
                                    href="https://npd.nalog.ru/app/">инструкции</a>. Вам понадобится ИНН и паспорт или
                                подтвержденная учетная запись на Госуслугах.</li>
                            <li>Зарегистрироваться в сервисе «ЯЗанят» по кнопке «Войти в сервис»</li>
                            <li>Заполните паспортные данные, СНИЛС, реквизиты для получения выплат от заказчиков.</li>
                            <li>Получить электронную подпись и вы можете начать работать.</li>
                        </ol>
                    </div>
                </div>


                <div class="accordion">5. Как создать привлекательное предложение</div>
                <div class="panel">
                    <div class="p-3">
                        <p>
                            Чтобы предложить заказчику свои услуги, необходимо откликнуться на его задание в разделе
                            "Все предложения". Заказчику будет отображаться вся ваша информация, находящаяся в разделе
                            "О себе"
                        </p>
                        <ul>
                            <li>Информативно и полно заполните раздел "О себе"</li>
                            <li>Опишите услуги, которые вы предоставляете. </li>
                            <li>Напишите 3-4 предложения о себе и своём опыте работы. Расскажите о своих главных
                                достижениях и напишите, какие задания вы хотите выполнять через сервис «ЯЗанят».</li>
                        </ul>
                    </div>
                </div>


                <div class="accordion">6. Как начать выполнять Задание</div>
                <div class="panel">
                    <div class="p-3">
                        <p>
                            Откликнуться на заказ и предложить свои услуги можно на странице "Все предложения". На
                            странице "Мои задачи" будут отображаться все ваши задания. Включая те задания, на которые
                            вас пригласил заказчик. Нажмите кнопку "Взять в работу" и после согласования подробностей с
                            заказчиком - приступайте к выполнению работы.
                        </p>
                        <p>
                            <img class="img" src="images/knowledge-base/contractor5.png" width="100%">
                        </p>
                    </div>
                </div>


                <div class="accordion">7. Как уведомить Заказчика о выполнении Задания</div>
                <div class="panel">
                    <div class="p-3">
                        <p>
                            Когда вы закончите выполнение задания, откройте задание и нажмите "Завершить задачу".
                        </p>
                    </div>
                </div>


                <div class="accordion">8. Как изменить номер телефона или почту </div>
                <div class="panel">
                    <div class="p-3">
                        <p>
                            Для изменения контактных данных следует обратиться в техническую поддержку, написав на
                            info@izanyat.ru. Тел.: +7 (499) 705-80-70
                        </p>
                    </div>
                </div>


                <div class="accordion">9. Как правильно заполнить профиль - Что рассказывать о себе, чтобы вам поручали
                    заказы</div>
                <div class="panel">
                    <div class="p-3">
                        <ul>
                            <li>Информативно и полно заполните раздел "О себе"</li>
                            <li>Опишите услуги, которые вы предоставляете. </li>
                            <li>Напишите 3-4 предложения о себе и своём опыте работы. Расскажите о своих главных
                                достижениях и напишите, какие задания вы хотите выполнять через сервис «ЯЗанят».</li>
                        </ul>
                    </div>
                </div>


                <div class="accordion">10. Как выполнить привязку к платформе ФНС и для чего это нужно? </div>
                <div class="panel">
                    <div class="p-3">
                        <p>
                            Для связки вашего аккаунта самозанятого с платформой «ЯЗанят» вы должны стоять на учёта как
                            плательщика налога на профессиональный доход. Необходимо открыть личный кабинет на сайте <a
                                href="https://lknpd.nalog.ru/auth/login">https://lknpd.nalog.ru/auth/login</a> либо
                            мобильное приложение «Мой Налог» и предоставить доступ сервису «ЯЗанят».
                        </p>
                        <p>
                            Настройки → Партнёры → «Разрешить» действия партнёру «ЯЗанят»
                        </p>
                        <p>
                            После получения дохода чеки в ФНС будут сформированы автоматически. Вам не потребуется
                            заходить в приложение «Мой Налог» для регистрации полученного дохода. Все чеки вы всегда
                            сможете посмотреть в разделе «Мой доход»
                        </p>
                        <p>
                            Скачать приложение «Мой Налог» <a
                                href="https://apps.apple.com/ru/app/%D0%BC%D0%BE%D0%B9-%D0%BD%D0%B0%D0%BB%D0%BE%D0%B3/id1437518854?l=en">Для
                                iOS</a>, <a
                                href="https://play.google.com/store/apps/details?id=com.gnivts.selfemployed&hl=ru&gl=US">для
                                Android</a>
                        </p>
                    </div>
                </div>


                <div class="accordion">11. Как быстро находить профильные Задания</div>
                <div class="panel">
                    <div class="p-3">
                        <p>
                            Для того, чтобы быстрее находить интересующие вас задания - можно пользоваться фильтром.
                            Выбирать нужную категорию и по ней будут отфильтрованы задания, которые вам интересны.
                        </p>
                        <p>
                            <img class="img" src="images/knowledge-base/contractor10.png" width="100%">
                        </p>
                    </div>
                </div>


                <div class="accordion">12. Как сделать отклик на Задание</div>
                <div class="panel">
                    <div class="p-3">
                        <p>
                            Для того, чтобы предложить свои услуги на доступные для выполнения задания требуется зайти
                            на вкладку "Все предложения", выбрать интересуюшее вас задание и нажать на кнопку
                            "Откликнуться". У заказчика отобразится предложение ваших услуг и если вы ему подойдёте, он
                            сможет связаться с вами.
                        </p>
                    </div>
                </div>


                <div class="accordion">13. Как я пойму, что выбрали меня для исполнения Задания</div>
                <div class="panel">
                    <div class="p-3">
                        <p>
                            После того, как вы откликнулись на задание в разделе "Все предложения" до момента
                            подтверждения заказчиком задание будет иметь статус "Ждёт подтверждения", если заказчик
                            выбирает вас как исполнителя на это задание вам придёт уведомление и задание перейдёт в
                            раздел "Мои задачи". Вы можете связаться с заказчиком и начинать выполнение работ.
                        </p>
                    </div>
                </div>


                <div class="accordion">14. Как принять Задание к выполнению </div>
                <div class="panel">
                    <div class="p-3">
                        <p>
                            Заказчик может предложить вам задание для выполнения. На странице "Все предложения"
                            отобразиться предложение по заданию которое вы можете взять в работу. Вы можете откликнуться
                            на выполнение этого задания.
                        </p>
                    </div>
                </div>


                <div class="accordion">15. Как получить оплату за выполненное Задание</div>
                <div class="panel">
                    <div class="p-3">
                        <p>
                            Пройдя регистрацию в сервисе ЯЗанят, перейдите в профиль и укажите номер карты, на которую
                            вы хотите получать оплату
                        </p>
                    </div>
                </div>


                <div class="accordion">16. Куда я получу деньги от Заказчика</div>
                <div class="panel">
                    <div class="p-3">
                        <p>
                            Вы получите деньги на ту карту, которую укажите в профиле. ЯЗанят не несет ответственности
                            за случаи, если Пользователем умышленно или по неосторожности были указаны неверные
                            реквизиты и(или) номер карты
                        </p>
                    </div>
                </div>


                <div class="accordion">17. Как быстро я получу деньги после оплаты Заказчиком</div>
                <div class="panel">
                    <div class="p-3">
                        <p>
                            После поступления Распоряжения площадке от Заказчика, выплата будет произведена моментально
                        </p>
                    </div>
                </div>


                <div class="accordion">18. Что если я не получил деньги на свой счет, а чек уже отправлен</div>
                <div class="panel">
                    <div class="p-3">
                        <p>
                            Обратитесь в техническую поддержку, написав на info@izanyat.ru или позвоните по номеру +7
                            (499) 705-80-70. В письме укажите id платежа из таблицы Мои платежи.
                        </p>
                    </div>
                </div>


                <div class="accordion">19. Что если оплаченная сумма не совпадает с суммой в чеке</div>
                <div class="panel">
                    <div class="p-3">
                        <p>
                            В случае, если вы не согласны с содержанием Чека, сформированного Оператором и загруженного
                            в Личный кабинет, вы можете сообщить об этом Оператору «ЯЗанят» посредством направления
                            уведомления на адрес электронной почты info@izanyat.ru письмом с темой "Чеки". В таком
                            письме должны быть указаны ФИО, ИНН Пользователя, дата и номер Заказа, причина несогласия с
                            Чеком (строго одна из двух причин - ошибочные данные в Чеке или возврат денежных средств).
                        </p>
                    </div>
                </div>


                <div class="accordion">20. Как изменить реквизиты для оплаты</div>
                <div class="panel">
                    <div class="p-3">
                        <p>
                            Обратитесь в техническую поддержку, написав на info@izanyat.ru.
                        </p>
                    </div>
                </div>


                <div class="accordion">21. Что такое чек и для чего он нужен</div>
                <div class="panel">
                    <div class="p-3">
                        <p>
                            Самозанятые исполнители платят 4% с выручки, полученной от физических лиц, 6% — от компаний.
                            Для того чтобы платить налог требуется отображать полученный доход.
                        </p>
                        <p>
                            Работая с заказчиками через платформу "ЯЗанят исполнителям не придётся самостоятельно
                            создавать чек в приложении "Мой Налог", за них всё сделает платформа автоматически после
                            перечисления денежных средств исполнителю за выполненное задание.
                        </p>
                        <p>
                            После того, как платформа создаст чек - в вашем приложении Мой Налог" будет отображаться
                            сумма налога, которую нужно будет оплатить.
                        </p>
                    </div>
                </div>


                <div class="accordion">22. Как аннулировать чек</div>
                <div class="panel">
                    <div class="p-3">
                        <p>
                            Обратитесь в техническую поддержку, написав на info@izanyat.ru. Тел.: +7 (499) 705-80-70 "
                        </p>
                    </div>
                </div>

            </div>
        </div>


    </div>
</div>

<script>
    $(function(){
        window.InterfaceManager = new InterfaceManagerClass;
        window.InterfaceManager.menuShow('main_menu');
        window.InterfaceManager.checkAuth();

        window.InterfaceManager.loadMe(function(me){
            if (me.is_selfemployed == true) {
                $('#contractor_part').prop('hidden', false);
            }
            if (me.is_client == true) {
                $('#client_part').prop('hidden', false);
            }

        });


        window.InterfaceManager.notificationsCount();

        let acc = $(".accordion");

        for(let item of acc){
            $(item).on('click',function(){
                this.classList.toggle("active");
                let panel = this.nextElementSibling;

                if (panel.style.maxHeight) {
                    panel.style.maxHeight = null;
                } else {
                    panel.style.maxHeight = panel.scrollHeight + "px";
                }
            });
        }
    });



</script>

@stop
