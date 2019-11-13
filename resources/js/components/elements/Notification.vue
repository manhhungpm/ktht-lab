<template>
    <dropdown
        extra-class="m-nav__item m-topbar__notifications m-topbar__notifications--img m-dropdown--large m-dropdown--header-bg-fill m-dropdown--mobile-full-width"
        header-class="m-nav__link"
        align="center">
        <template slot="header">
            <span class="m-nav__link-badge m-badge m-badge--dot m-badge--dot-small m-badge--danger"></span>
            <span class="m-nav__link-icon"><i class="flaticon-alarm"></i></span>
        </template>

        <template slot="content">
            <div class="m-dropdown__header m--align-center">
                <span class="m-dropdown__header-title">9 thông báo mới</span>
            </div>
            <div class="m-dropdown__body">
                <div class="m-dropdown__content">
                    <ul class="nav nav-tabs m-tabs m-tabs-line m-tabs-line--brand" role="tablist">
                        <li class="nav-item m-tabs__item">
                            <a class="nav-link m-tabs__link active" data-toggle="tab"
                               href="#topbar_notifications_notifications" role="tab">
                                Cảnh báo
                            </a>
                        </li>
                        <li class="nav-item m-tabs__item">
                            <a class="nav-link m-tabs__link" data-toggle="tab" href="#topbar_notifications_events"
                               role="tab">Sự kiện</a>
                        </li>
                        <li class="nav-item m-tabs__item">
                            <a class="nav-link m-tabs__link" data-toggle="tab" href="#topbar_notifications_logs"
                               role="tab">Logs</a>
                        </li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane active" id="topbar_notifications_notifications" role="tabpanel">
                            <scrollbar :height="250" :mobile-height="200">
                                <div class="m-list-timeline m-list-timeline--skin-light">
                                    <div class="m-list-timeline__items">
                                        <div class="m-list-timeline__item">
                                            <span
                                                class="m-list-timeline__badge -m-list-timeline__badge--state-success"></span>
                                            <span class="m-list-timeline__text">12 new users registered</span>
                                            <span class="m-list-timeline__time">Just now</span>
                                        </div>
                                        <div class="m-list-timeline__item">
                                            <span class="m-list-timeline__badge"></span>
                                            <span class="m-list-timeline__text">System shutdown <span
                                                class="m-badge m-badge--success m-badge--wide">pending</span></span>
                                            <span class="m-list-timeline__time">14 mins</span>
                                        </div>
                                        <div class="m-list-timeline__item">
                                            <span class="m-list-timeline__badge"></span>
                                            <span class="m-list-timeline__text">New invoice received</span>
                                            <span class="m-list-timeline__time">20 mins</span>
                                        </div>
                                        <div class="m-list-timeline__item">
                                            <span class="m-list-timeline__badge"></span>
                                            <span class="m-list-timeline__text">DB overloaded 80% <span
                                                class="m-badge m-badge--info m-badge--wide">settled</span></span>
                                            <span class="m-list-timeline__time">1 hr</span>
                                        </div>
                                        <div class="m-list-timeline__item">
                                            <span class="m-list-timeline__badge"></span>
                                            <span class="m-list-timeline__text">System error - <a href="#"
                                                                                                  class="m-link">Check</a></span>
                                            <span class="m-list-timeline__time">2 hrs</span>
                                        </div>
                                        <div class="m-list-timeline__item m-list-timeline__item--read">
                                            <span class="m-list-timeline__badge"></span>
                                            <span href="" class="m-list-timeline__text">New order received <span
                                                class="m-badge m-badge--danger m-badge--wide">urgent</span></span>
                                            <span class="m-list-timeline__time">7 hrs</span>
                                        </div>
                                    </div>
                                </div>
                            </scrollbar>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </dropdown>
</template>

<script>
    export default {
        name: 'Notification',
        mounted(){

        }
    }
</script>
