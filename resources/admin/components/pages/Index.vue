<template>
    <div>
        <div class="kt-subheader kt-grid__item" id="kt_subheader">
            <div class="kt-subheader__main">
                <h3 class="kt-subheader__title">Статистика</h3>
            </div>
        </div>

        <div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">
            <div class="kt-portlet">
                <div class="kt-portlet__body  kt-portlet__body--fit">
                    <div class="row row-no-padding row-col-separator-xl">

                        <div class="col-md-12 col-lg-6 col-xl-3">
                            <!--begin::Total Profit-->
                            <div class="kt-widget24">
                                <div class="kt-widget24__details">
                                    <div class="kt-widget24__info">
                                        <h4 class="kt-widget24__title">
                                            Пополнений на
                                        </h4>
                                        <span class="kt-widget24__desc">
									за сегодня
								</span>
                                    </div>

                                    <span class="kt-widget24__stats kt-font-success">
								{{ stat.payments.today }}<i class="la la-rub"></i>
							</span>
                                </div>
                            </div>
                            <!--end::Total Profit-->
                        </div>

                        <div class="col-md-12 col-lg-6 col-xl-3">
                            <!--begin::New Feedbacks-->
                            <div class="kt-widget24">
                                <div class="kt-widget24__details">
                                    <div class="kt-widget24__info">
                                        <h4 class="kt-widget24__title">
                                            Пополнений на
                                        </h4>
                                        <span class="kt-widget24__desc">
									за 7 дней
								</span>
                                    </div>

                                    <span class="kt-widget24__stats kt-font-success">
							   {{ stat.payments.week }}<i class="la la-rub"></i>
							</span>
                                </div>
                            </div>
                            <!--end::New Feedbacks-->
                        </div>

                        <div class="col-md-12 col-lg-6 col-xl-3">
                            <!--begin::New Orders-->
                            <div class="kt-widget24">
                                <div class="kt-widget24__details">
                                    <div class="kt-widget24__info">
                                        <h4 class="kt-widget24__title">
                                            Пополнений на
                                        </h4>
                                        <span class="kt-widget24__desc">
									за месяц
								</span>
                                    </div>

                                    <span class="kt-widget24__stats kt-font-success">
								{{ stat.payments.month }}<i class="la la-rub"></i>
							</span>
                                </div>
                            </div>
                            <!--end::New Orders-->
                        </div>

                        <div class="col-md-12 col-lg-6 col-xl-3">
                            <!--begin::New Users-->
                            <div class="kt-widget24">
                                <div class="kt-widget24__details">
                                    <div class="kt-widget24__info">
                                        <h4 class="kt-widget24__title">
                                            Пополнений на
                                        </h4>
                                        <span class="kt-widget24__desc">
									за все время
								</span>
                                    </div>

                                    <span class="kt-widget24__stats kt-font-success">
								{{ stat.payments.all }}<i class="la la-rub"></i>
							</span>
                                </div>
                            </div>
                            <!--end::New Users-->
                        </div>

                    </div>
                </div>
            </div>
            <div class="kt-portlet">
                <div class="kt-portlet__body  kt-portlet__body--fit">
                    <div class="row row-no-padding row-col-separator-xl">

                        <div class="col-md-12 col-lg-6 col-xl-3">
                            <!--begin::Total Profit-->
                            <div class="kt-widget24">
                                <div class="kt-widget24__details">
                                    <div class="kt-widget24__info">
                                        <h4 class="kt-widget24__title">
                                            Пользователей
                                        </h4>
                                        <span class="kt-widget24__desc">
									всего
								</span>
                                    </div>

                                    <span class="kt-widget24__stats kt-font-brand">
								        {{ stat.stats.users }}
                                        <i class="la la-user"></i>
							        </span>
                                </div>
                            </div>
                            <!--end::Total Profit-->
                        </div>

                        <div class="col-md-12 col-lg-6 col-xl-3">
                            <!--begin::Total Profit-->
                            <div class="kt-widget24">
                                <div class="kt-widget24__details">
                                    <div class="kt-widget24__info">
                                        <h4 class="kt-widget24__title">
                                            Открытий
                                        </h4>
                                        <span class="kt-widget24__desc">
									всего
								</span>
                                    </div>

                                    <span class="kt-widget24__stats kt-font-brand">
								        {{ stat.stats.opens }}
                                        <i class="la la-archive"></i>
							        </span>
                                </div>
                            </div>
                            <!--end::Total Profit-->
                        </div>

                        <div class="col-md-12 col-lg-6 col-xl-3">
                            <!--begin::Total Profit-->
                            <div class="kt-widget24">
                                <div class="kt-widget24__details">
                                    <div class="kt-widget24__info">
                                        <h4 class="kt-widget24__title">
                                            Профит сайта
                                        </h4>
                                        <span class="kt-widget24__desc">
									пополнения - покупки предметов
								</span>
                                    </div>

                                    <span class="kt-widget24__stats kt-font-brand">
                                        {{ stat.stats.profit }}
                                        <i class="la la-rub"></i>
                                    </span>
                                </div>
                            </div>
                            <!--end::Total Profit-->
                        </div>

                        <div class="col-md-12 col-lg-6 col-xl-3">
                            <!--begin::New Users-->
                            <div class="kt-widget24">
                                <div class="kt-widget24__details">
                                    <div class="kt-widget24__info">
                                        <h4 class="kt-widget24__title">
                                            Баланс кошелька бота
                                        </h4>
                                        <span class="kt-widget24__desc">
									        MARKET RUB
								        </span>
                                    </div>

                                    <span class="kt-widget24__stats kt-font-warning">
                                        <span v-if="balance.loading">
                                            <img src="https://i1.wp.com/caringo.com/wp-content/themes/bootstrap/wwwroot/img/spinning-wheel-1.gif"
                                                 height="26px"/>
                                        </span>
                                        <span v-else>
                                            {{ balance.sum }}
                                        </span>
                                        <i class="la la-rub"></i>
                                    </span>
                                </div>
                            </div>
                            <!--end::New Users-->
                        </div>

                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-6">
                    <div class="kt-portlet kt-portlet--height-fluid">
                        <div class="kt-portlet__head">
                            <div class="kt-portlet__head-label">
                                <h3 class="kt-portlet__head-title">
                                    График регистраций за текущий месяц
                                </h3>
                            </div>
                        </div>
                        <div class="kt-portlet__body kt-portlet__body--fluid">
                            <div class="kt-widget12">
                                <div class="kt-widget12__chart" style="height:250px;">
                                    <canvas id="usersChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6">
                    <div class="kt-portlet kt-portlet--height-fluid">
                        <div class="kt-portlet__head">
                            <div class="kt-portlet__head-label">
                                <h3 class="kt-portlet__head-title">
                                    График пополнений за текущий месяц
                                </h3>
                            </div>
                        </div>
                        <div class="kt-portlet__body kt-portlet__body--fluid">
                            <div class="kt-widget12">
                                <div class="kt-widget12__chart" style="height:250px;">
                                    <canvas id="paymentsChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-4">
                    <div class="kt-portlet">
                        <div class="kt-portlet__head">
                            <div class="kt-portlet__head-label">
                                <h3 class="kt-portlet__head-title">
                                    Последние пополнения
                                </h3>
                            </div>
                        </div>
                        <div class="kt-portlet__body">
                            <div class="kt-widget3 kt-scroll" data-scroll="true" data-height="616">

                                <div v-for="payment in stat.lastPayments" :key="payment.id" class="kt-widget3__item">
                                    <div class="kt-widget3__header">
                                        <div class="kt-widget3__user-img">
                                            <img class="kt-widget3__img" :src="payment.user.avatar" alt="">
                                        </div>
                                        <div class="kt-widget3__info">
                                            <router-link tag="a" :to="{name: 'user', params: {id: payment.user.id}}"
                                                         class="kt-widget3__username">
                                                {{ payment.user.username }}
                                            </router-link>
                                            <br>
                                            <span class="kt-widget3__time">
                                            {{ payment.time }}
									        </span>
                                        </div>
                                        <span class="kt-widget3__status kt-font-success">
									 {{ payment.sum }}<i class="la la-rub"></i>
								</span>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4">
                    <div class="kt-portlet">
                        <div class="kt-portlet__head">
                            <div class="kt-portlet__head-label">
                                <h3 class="kt-portlet__head-title">
                                    Последние пользователи
                                </h3>
                            </div>
                        </div>
                        <div class="kt-portlet__body">
                            <div class="kt-widget3 kt-scroll" data-scroll="true" data-height="616">

                                <div v-for="user in stat.lastUsers" :key="user.id" class="kt-widget3__item">
                                    <div class="kt-widget3__header">
                                        <div class="kt-widget3__user-img">
                                            <img class="kt-widget3__img" :src="user.avatar" alt="">
                                        </div>
                                        <div class="kt-widget3__info">
                                            <router-link tag="a" :to="{name: 'user', params: {id: user.id}}"
                                                         class="kt-widget3__username">
                                                {{ user.username }}
                                            </router-link>
                                            <br>
                                            <span class="kt-widget3__time">

									        </span>
                                        </div>
                                        <span class="kt-widget3__status kt-font-success">
                                             {{ user.time }}
                                        </span>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4">
                    <div class="kt-portlet">
                        <div class="kt-portlet__head">
                            <div class="kt-portlet__head-label">
                                <h3 class="kt-portlet__head-title">
                                    Самые богатые
                                </h3>
                            </div>
                        </div>
                        <div class="kt-portlet__body">
                            <div class="kt-widget3 kt-scroll" data-scroll="true" data-height="616">

                                <div v-for="user in stat.topBalance" :key="user.id" class="kt-widget3__item">
                                    <div class="kt-widget3__header">
                                        <div class="kt-widget3__user-img">
                                            <img class="kt-widget3__img" :src="user.avatar" alt="">
                                        </div>
                                        <div class="kt-widget3__info">
                                            <router-link tag="a" :to="{name: 'user', params: {id: user.id}}"
                                                         class="kt-widget3__username">
                                                {{ user.username }}
                                            </router-link>
                                        </div>
                                        <span class="kt-widget3__status kt-font-success">
									        {{ user.balance }} <i class="la la-rub"></i>
								        </span>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</template>

<script>
    import axios from 'axios';

    export default {
        data() {
            return {
                stat: {
                    payments: {
                        today: 0,
                        week: 0,
                        month: 0,
                        all: 0
                    },
                    stats: {
                        users: 0,
                        opens: 0,
                        profit: 0
                    },
                    chart: {
                        users: {},
                        payments: {}
                    },
                    lastPayments: {},
                    lastUsers: {},
                    topBalance: {}
                },
                balance: {
                    sum: 0,
                    loading: true
                }
            }
        },
        methods: {
            async load() {
                const request = await axios.post('/api/admin/settings/statistic');

                this.stat = request.data;

                this.drawUsersChart();
                this.drawPaymentsChart();
            },
            async getBalance() {
                const request = await axios.post('/api/admin/settings/getBalance');
                const data = request.data;

                if (data.success) {
                    this.balance = {
                        sum: data.balance,
                        loading: false
                    }
                }
            },
            async drawUsersChart() {
                const months = [];
                const users = [];

                $.each(this.stat.chart.users, function (index, data) {
                    months.push(data.date);
                    users.push(data.count);
                });

                const lineCh = document.getElementById('usersChart').getContext("2d");

                new Chart(lineCh, {
                    type: 'line',
                    data: {
                        labels: months,
                        datasets: [{
                            label: "",
                            tension: 0.4,
                            backgroundColor: 'transparent',
                            borderColor: '#2c80ff',
                            pointBorderColor: "#2c80ff",
                            pointBackgroundColor: "#fff",
                            pointBorderWidth: 2,
                            pointHoverRadius: 6,
                            pointHoverBackgroundColor: "#fff",
                            pointHoverBorderColor: "#2c80ff",
                            pointHoverBorderWidth: 2,
                            pointRadius: 6,
                            pointHitRadius: 6,
                            data: users,
                        }]
                    },
                    options: {
                        legend: {
                            display: false
                        },
                        maintainAspectRatio: false,
                        tooltips: {
                            callbacks: {
                                title: function (tooltipItem, data) {
                                    return 'Дата : ' + data['labels'][tooltipItem[0]['index']];
                                },
                                label: function (tooltipItem, data) {
                                    return data['datasets'][0]['data'][tooltipItem['index']] + ' чел.';
                                }
                            },
                            backgroundColor: '#eff6ff',
                            titleFontSize: 13,
                            titleFontColor: '#6783b8',
                            titleMarginBottom: 10,
                            bodyFontColor: '#9eaecf',
                            bodyFontSize: 14,
                            bodySpacing: 4,
                            yPadding: 15,
                            xPadding: 15,
                            footerMarginTop: 5,
                            displayColors: false
                        },
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero: true,
                                    fontSize: 12,
                                    fontColor: '#9eaecf',
                                    stepSize: Math.ceil(users / 5)
                                },
                                gridLines: {
                                    color: "#e5ecf8",
                                    tickMarkLength: 0,
                                    zeroLineColor: '#e5ecf8'
                                },

                            }],
                            xAxes: [{
                                ticks: {
                                    beginAtZero: true,
                                    fontSize: 12,
                                    fontColor: '#9eaecf',
                                    source: 'auto',
                                },
                                gridLines: {
                                    color: "transparent",
                                    tickMarkLength: 20,
                                    zeroLineColor: '#e5ecf8',
                                },
                            }]
                        }
                    }
                });
            },
            async drawPaymentsChart() {
                const months = [];
                const deps = [];

                $.each(this.stat.chart.payments, function (index, data) {
                    months.push(data.date);
                    deps.push(data.sum);
                });

                const lineCh = document.getElementById('paymentsChart').getContext("2d");

                new Chart(lineCh, {
                    type: 'line',
                    data: {
                        labels: months,
                        datasets: [{
                            label: "",
                            tension: 0.4,
                            backgroundColor: 'transparent',
                            borderColor: '#2c80ff',
                            pointBorderColor: "#2c80ff",
                            pointBackgroundColor: "#fff",
                            pointBorderWidth: 2,
                            pointHoverRadius: 6,
                            pointHoverBackgroundColor: "#fff",
                            pointHoverBorderColor: "#2c80ff",
                            pointHoverBorderWidth: 2,
                            pointRadius: 6,
                            pointHitRadius: 6,
                            data: deps,
                        }]
                    },
                    options: {
                        legend: {
                            display: false
                        },
                        maintainAspectRatio: false,
                        tooltips: {
                            callbacks: {
                                title: function (tooltipItem, data) {
                                    return 'Дата : ' + data['labels'][tooltipItem[0]['index']];
                                },
                                label: function (tooltipItem, data) {
                                    return data['datasets'][0]['data'][tooltipItem['index']] + ' руб.';
                                }
                            },
                            backgroundColor: '#eff6ff',
                            titleFontSize: 13,
                            titleFontColor: '#6783b8',
                            titleMarginBottom: 10,
                            bodyFontColor: '#9eaecf',
                            bodyFontSize: 14,
                            bodySpacing: 4,
                            yPadding: 15,
                            xPadding: 15,
                            footerMarginTop: 5,
                            displayColors: false
                        },
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero: true,
                                    fontSize: 12,
                                    fontColor: '#9eaecf',
                                    stepSize: Math.ceil(deps / 5)
                                },
                                gridLines: {
                                    color: "#e5ecf8",
                                    tickMarkLength: 0,
                                    zeroLineColor: '#e5ecf8'
                                },

                            }],
                            xAxes: [{
                                ticks: {
                                    fontSize: 12,
                                    fontColor: '#9eaecf',
                                    source: 'auto',
                                },
                                gridLines: {
                                    color: "transparent",
                                    tickMarkLength: 20,
                                    zeroLineColor: '#e5ecf8',
                                },
                            }]
                        }
                    }
                });
            }
        },
        mounted() {
            this.load();
            this.getBalance();
        }
    }
</script>