<div>
    @section('title','داشبورد')
    @if(auth()->user()->hasPermissionTo('show_dashboard'))
        <div class="subheader py-2 py-lg-6 subheader-solid" id="kt_subheader">
            <div class="container-fluid d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
                <!--begin::Info-->
                <div class="d-flex align-items-center flex-wrap mr-1">
                    <!--begin::Page Heading-->
                    <div class="d-flex align-items-baseline flex-wrap mr-5">
                        <!--begin::Page Title-->
                        <h5 class="text-dark font-weight-bold my-1 mr-5">داشبورد</h5>
                        <!--end::Page Title-->
                    </div>
                    <!--end::Page Heading-->
                </div>
                <!--end::Info-->
                <!--begin::Toolbar-->
                <div class="d-flex align-items-center justify-content-between">
                    <!--begin::Actions-->
                    <p class="m-0">از تاریخ</p>
                    <div class="d-flex align-center justify-content-between">
                        <x-admin.forms.jdate-picker id="date" label=""   wire:model.defer="from_date_view"/>
                    </div>
                    <p class="m-0">تا تاریخ</p>
                    <div>
                        <x-admin.forms.jdate-picker id="date2" label=""  wire:model.defer="to_date_viwe"/>
                    </div>
                    <div>
                        <button wire:loading.attr="disabled" class="btn btn-light-primary font-weight-bolder btn-sm" wire:click.prevent="confirmFilter">اعمال فیلتر</button>
                    </div>
                    <!--end::Actions-->
                </div>
                <!--end::Toolbar-->
            </div>
        </div>
        <div class="card card-custom">
            <div class="card-body">
                <div class="d-flex align-items-center flex-wrap mr-1">
                    <!--begin::Page Heading-->
                    <div class="d-flex align-items-baseline flex-wrap mr-5">
                        <!--begin::Page Title-->
                        <h4 class="card-label">
                            <span class="d-block text-dark font-weight-bolder">گزارش کلی</span>
                        </h4>
                        <!--end::Page Title-->
                    </div>
                    <!--end::Page Heading-->
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <!--begin::Stats Widget 25-->
                        <div class="card card-custom bg-light-primary card-stretch gutter-b">
                            <!--begin::Body-->
                            <div class="card-body">
                            <span class="svg-icon svg-icon-info svg-icon-4x">
                                <i class="text-info flaticon2-list-1 fa-3x"></i>
                            </span>
                                <span class="card-title font-weight-bolder text-dark-75 font-size-h2 mb-0 mt-6 d-block">
                                {{ $box['all'] }}عدد
                            </span>
                                <span class="font-weight-bold text-dark font-size-lg">کل لایسنس ها</span>
                            </div>
                            <!--end::Body-->
                        </div>
                        <!--end::Stats Widget 25-->
                    </div>
                    <div class="col-md-3">
                        <!--begin::Stats Widget 25-->
                        <div class="card card-custom bg-light-primary card-stretch gutter-b">
                            <!--begin::Body-->
                            <div class="card-body">
                            <span class="svg-icon svg-icon-info svg-icon-4x">
                                <i class="text-info flaticon2-list-1 fa-3x"></i>
                            </span>
                                <span class="card-title font-weight-bolder text-dark-75 font-size-h2 mb-0 mt-6 d-block">
                                {{ $box['used'] }}عدد
                            </span>
                                <span class="font-weight-bold text-dark font-size-lg">استفاده شده</span>
                            </div>
                            <!--end::Body-->
                        </div>
                        <!--end::Stats Widget 25-->
                    </div>
                    <div class="col-md-3">
                        <!--begin::Stats Widget 25-->
                        <div class="card card-custom bg-light-primary card-stretch gutter-b">
                            <!--begin::Body-->
                            <div class="card-body">
                            <span class="svg-icon svg-icon-info svg-icon-4x">
                                <i class="text-info flaticon2-list-1 fa-3x"></i>
                            </span>
                                <span class="card-title font-weight-bolder text-dark-75 font-size-h2 mb-0 mt-6 d-block">
                                {{ $box['not_used'] }}عدد
                            </span>
                                <span class="font-weight-bold text-dark font-size-lg">استفاده نشده</span>
                            </div>
                            <!--end::Body-->
                        </div>
                        <!--end::Stats Widget 25-->
                    </div>
                    <div class="col-md-3">
                        <!--begin::Stats Widget 25-->
                        <div class="card card-custom bg-light-primary card-stretch gutter-b">
                            <!--begin::Body-->
                            <div class="card-body">
                            <span class="svg-icon svg-icon-info svg-icon-4x">
                                <i class="text-info flaticon2-list-1 fa-3x"></i>
                            </span>
                                <span class="card-title font-weight-bolder text-dark-75 font-size-h2 mb-0 mt-6 d-block">
                                {{ $box['deleted'] }}عدد
                            </span>
                                <span class="font-weight-bold text-dark font-size-lg">حذف شده </span>
                            </div>
                            <!--end::Body-->
                        </div>
                        <!--end::Stats Widget 25-->
                    </div>
                </div>
                <hr>
                <div class="row" wire:ignore>
                    <div class="col-xl-12" wire:init="runChart()">
                        <!--begin::Charts Widget 4-->
                        <div class="card card-custom card-stretch gutter-b">
                            <!--begin::Header-->
                            <div class="card-header h-auto border-0">
                                <div class="card-title py-5">
                                    <h3 class="card-label">
                                        <span class="d-block text-dark font-weight-bolder"> نمودار ورودی/خروجی</span>
                                    </h3>
                                </div>
                            </div>
                            <!--end::Header-->
                            <!--begin::Body-->
                            <div class="card-body">
                                <div id="kt_charts_widget_4_chart2"></div>
                            </div>
                            <!--end::Body-->
                        </div>
                        <!--end::Charts Widget 4-->
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

@push('scripts')
    <script>
        Livewire.on('runChart', function (data) {
            const element = document.getElementById("kt_charts_widget_4_chart2");
            if (!element) {
                return;
            }
            const obj = JSON.parse(data);
            const options = {
                series: [{
                    name: 'ورودی ها',
                    data: obj.enter
                },{
                    name: 'خروجی  ها',
                    data: obj.exit
                }
                ],
                chart: {
                    type: 'area',
                    height: 350,
                    toolbar: {
                        show: true
                    }
                },
                fill: {
                    type: 'solid',
                    opacity: 1
                },
                stroke: {
                    curve: 'smooth'
                },
                xaxis: {
                    categories: obj.label,
                    axisBorder: {
                        show: false,
                    },
                    axisTicks: {
                        show: false
                    },
                    labels: {
                        style: {
                            colors: KTApp.getSettings()['colors']['gray']['gray-500'],
                            fontSize: '12px',
                            fontFamily: KTApp.getSettings()['font-family']
                        }
                    },
                    crosshairs: {
                        position: 'front',
                        stroke: {
                            color: KTApp.getSettings()['colors']['theme']['light']['success'],
                            width: 1,
                            dashArray: 3
                        }
                    },
                    tooltip: {
                        enabled: true,
                        formatter: undefined,
                        offsetY: 0,
                        style: {
                            fontSize: '12px',
                            fontFamily: KTApp.getSettings()['font-family']
                        }
                    }
                },
                yaxis: {
                    labels: {
                        style: {
                            colors: KTApp.getSettings()['colors']['gray']['gray-500'],
                            fontSize: '12px',
                            fontFamily: KTApp.getSettings()['font-family']
                        }
                    }
                },
                states: {
                    normal: {
                        filter: {
                            type: 'none',
                            value: 0
                        }
                    },
                    hover: {
                        filter: {
                            type: 'none',
                            value: 0
                        }
                    },
                    active: {
                        allowMultipleDataPointsSelection: false,
                        filter: {
                            type: 'none',
                            value: 0
                        }
                    }
                },
                tooltip: {
                    style: {
                        fontSize: '12px',
                        fontFamily: KTApp.getSettings()['font-family']
                    },
                    y: {
                        formatter: function (val) {
                            return val.toLocaleString() + " عدد"
                        }
                    }
                },
                colors: [
                    KTApp.getSettings()['colors']['theme']['base']['success'],
                    KTApp.getSettings()['colors']['theme']['base']['danger'],
                ],
                grid: {
                    borderColor: KTApp.getSettings()['colors']['gray']['gray-200'],
                    strokeDashArray: 4,
                    yaxis: {
                        lines: {
                            show: true
                        }
                    }
                },
                markers: {
                    colors: [
                        KTApp.getSettings()['colors']['theme']['light']['success'],
                    ],
                    strokeColor: [
                        KTApp.getSettings()['colors']['theme']['light']['success'],
                    ],
                    strokeWidth: 3
                }
            };

            const chart = new ApexCharts(element, options);
            throw chart.render();
        });
    </script>
@endpush
