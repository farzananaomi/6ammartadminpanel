@extends('layouts.admin.app')

@section('title', translate('messages.transaction_report'))

@push('css_or_js')
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-header-title">
                <span class="page-header-icon">
                    <img src="{{ asset('public/assets/admin/img/report.png') }}" class="w--22" alt="">
                </span>
                <span>
                    {{ translate('messages.transection_report') }}
                    @if (isset($filter) && $filter != 'all_time')
                    <span class="mb-0 h6 badge badge-soft-success ml-2"
                        id="itemCount">( {{ session('from_date') }} - {{ session('to_date') }} )</span>
                        @endif
                </span>
            </h1>
        </div>
        <!-- End Page Header -->
        <div class="card mb-20">
            <div class="card-body">
                <h4 class="">{{ translate('Search Data') }}</h4>
                <form action="{{ route('admin.transactions.report.set-date') }}" method="post">
                    @csrf
                    <div class="row g-3">
                        <div class="col-sm-6 col-md-3">
                            <select name="module_id" class="form-control js-select2-custom set-filter" data-url="{{ url()->full() }}" data-filter="module_id"
                                title="{{ translate('messages.select_modules') }}">
                                <option value="" {{ !request('module_id') ? 'selected' : '' }}>
                                    {{ translate('messages.all_modules') }}</option>
                                @foreach (\App\Models\Module::notParcel()->get() as $module)
                                    <option value="{{ $module->id }}"
                                        {{ request('module_id') == $module->id ? 'selected' : '' }}>
                                        {{ $module['module_name'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <select name="zone_id" class="form-control js-select2-custom set-filter" data-url="{{ url()->full() }}" data-filter="zone_id" id="zone">
                                <option value="all">{{ translate('messages.All_Zones') }}</option>
                                @foreach (\App\Models\Zone::orderBy('name')->get() as $z)
                                    <option value="{{ $z['id'] }}"
                                        {{ isset($zone) && $zone->id == $z['id'] ? 'selected' : '' }}>
                                        {{ $z['name'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <select name="store_id" data-url="{{ url()->full() }}" data-filter="store_id"
                                data-placeholder="{{ translate('messages.select_store') }}"
                                class="js-data-example-ajax form-control set-filter">
                                @if (isset($store))
                                    <option value="{{ $store->id }}" selected>{{ $store->name }}</option>
                                @else
                                    <option value="all" selected>{{ translate('messages.all_stores') }}</option>
                                @endif
                            </select>
                        </div>

                        <div class="col-sm-6 col-md-3">
                            <select class="form-control set-filter" name="filter" data-url="{{ url()->full() }}" data-filter="filter">
                                <option value="all_time" {{ isset($filter) && $filter == 'all_time' ? 'selected' : '' }}>
                                    {{ translate('messages.All Time') }}</option>
                                <option value="this_year" {{ isset($filter) && $filter == 'this_year' ? 'selected' : '' }}>
                                    {{ translate('messages.This Year') }}</option>
                                <option value="previous_year"
                                    {{ isset($filter) && $filter == 'previous_year' ? 'selected' : '' }}>
                                    {{ translate('messages.Previous Year') }}</option>
                                <option value="this_month"
                                    {{ isset($filter) && $filter == 'this_month' ? 'selected' : '' }}>
                                    {{ translate('messages.This Month') }}</option>
                                <option value="this_week" {{ isset($filter) && $filter == 'this_week' ? 'selected' : '' }}>
                                    {{ translate('messages.This Week') }}</option>
                                <option value="custom" {{ isset($filter) && $filter == 'custom' ? 'selected' : '' }}>
                                    {{ translate('messages.Custom') }}</option>
                            </select>
                        </div>
                        @if (isset($filter) && $filter == 'custom')
                            <div class="col-sm-6 col-md-3">

                                <input type="date" name="from" id="from_date" class="form-control"
                                    placeholder="{{ translate('Start Date') }}"
                                    {{ session()->has('from_date') ? 'value=' . session('from_date') : '' }} required>

                            </div>
                            <div class="col-sm-6 col-md-3">

                                <input type="date" name="to" id="to_date" class="form-control"
                                    placeholder="{{ translate('End Date') }}"
                                    {{ session()->has('to_date') ? 'value=' . session('to_date') : '' }} required>

                            </div>
                        @endif
                        <div class="col-sm-6 col-md-3 ml-auto">
                            <button type="submit"
                                class="btn btn-primary btn-block h--45px">{{ translate('Filter') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        @php
            $from = session('from_date') . ' 00:00:00';
            $to = session('to_date') . ' 23:59:59';
            $total = \App\Models\Order::when(isset($zone), function ($query) use ($zone) {
                return $query->where('zone_id', $zone->id);
            })
            ->when(isset($key), function ($query) use ($key) {
                    return $query->where(function ($q) use ($key) {
                            foreach ($key as $value) {
                                $q->orWhere('id', 'like', "%{$value}%");
                            }
                        });
                })
                ->when(request('module_id'), function ($query) {
                    return $query->module(request('module_id'));
                })
                ->when(isset($store), function ($query) use ($store) {
                    return $query->where('store_id', $store->id);
                })
                ->when(isset($from) && isset($to) && $from != null && $to != null && $filter == 'custom', function ($query) use ($from, $to) {
                    return $query->whereBetween('created_at', [$from . ' 00:00:00', $to . ' 23:59:59']);
                })
                ->when(isset($filter) && $filter == 'this_year', function ($query) {
                    return $query->whereYear('created_at', now()->format('Y'));
                })
                ->when(isset($filter) && $filter == 'this_month', function ($query) {
                    return $query->whereMonth('created_at', now()->format('m'))->whereYear('created_at', now()->format('Y'));
                })
                ->when(isset($filter) && $filter == 'this_month', function ($query) {
                    return $query->whereMonth('created_at', now()->format('m'))->whereYear('created_at', now()->format('Y'));
                })
                ->when(isset($filter) && $filter == 'previous_year', function ($query) {
                    return $query->whereYear('created_at', date('Y') - 1);
                })
                ->when(isset($filter) && $filter == 'this_week', function ($query) {
                    return $query->whereBetween('created_at', [
                        now()
                            ->startOfWeek()
                            ->format('Y-m-d H:i:s'),
                        now()
                            ->endOfWeek()
                            ->format('Y-m-d H:i:s'),
                    ]);
                })
                ->Notpos()
                ->count();
            if ($total == 0) {
                $total = 0.01;
            }
        @endphp
        <div class="mb-20">
            <div class="row g-3">
                <div class="col-lg-8">
                    <div class="row g-2">
                        <div class="col-sm-6">
                            @php
                                $delivered = \App\Models\Order::when(isset($zone), function ($query) use ($zone) {
                                    return $query->where('zone_id', $zone->id);
                                })
                                ->when(isset($key), function ($query) use ($key) {
                                        return $query->where(function ($q) use ($key) {
                                                foreach ($key as $value) {
                                                    $q->orWhere('id', 'like', "%{$value}%");
                                                }
                                            });
                                    })
                                    ->when(request('module_id'), function ($query) {
                                        return $query->module(request('module_id'));
                                    })
                                    ->whereIn('order_status', ['delivered','refund_requested','refund_request_canceled'])
                                    ->when(isset($store), function ($query) use ($store) {
                                        return $query->where('store_id', $store->id);
                                    })
                                    ->when(isset($from) && isset($to) && $from != null && $to != null && $filter == 'custom', function ($query) use ($from, $to) {
                                        return $query->whereBetween('created_at', [$from . ' 00:00:00', $to . ' 23:59:59']);
                                    })
                                    ->when(isset($filter) && $filter == 'this_year', function ($query) {
                                        return $query->whereYear('created_at', now()->format('Y'));
                                    })
                                    ->when(isset($filter) && $filter == 'this_month', function ($query) {
                                        return $query->whereMonth('created_at', now()->format('m'))->whereYear('created_at', now()->format('Y'));
                                    })
                                    ->when(isset($filter) && $filter == 'this_month', function ($query) {
                                        return $query->whereMonth('created_at', now()->format('m'))->whereYear('created_at', now()->format('Y'));
                                    })
                                    ->when(isset($filter) && $filter == 'previous_year', function ($query) {
                                        return $query->whereYear('created_at', date('Y') - 1);
                                    })
                                    ->when(isset($filter) && $filter == 'this_week', function ($query) {
                                        return $query->whereBetween('created_at', [
                                            now()
                                                ->startOfWeek()
                                                ->format('Y-m-d H:i:s'),
                                            now()
                                                ->endOfWeek()
                                                ->format('Y-m-d H:i:s'),
                                        ]);
                                    })
                                    ->Notpos()
                                    ->sum('order_amount');
                            @endphp
                            <a class="__card-3 h-100" href="#">
                                <img src="{{ asset('/public/assets/admin/img/report/new/trx1.png') }}" class="icon"
                                    alt="report/new">
                                <h3 class="title text-008958">{{ \App\CentralLogics\Helpers::number_format_short($delivered) }}
                                </h3>
                                <h6 class="subtitle">{{ translate('Completed Transaction') }}</h6>
                                <div class="info-icon" data-toggle="tooltip" data-placement="top"
                                    data-original-title="{{ translate('When the order is successfully delivered full order amount goes to this section.') }}">
                                    <img src="{{ asset('/public/assets/admin/img/report/new/info1.png') }}"
                                        alt="report/new">
                                </div>
                            </a>
                        </div>
                        <div class="col-sm-6">
                            @php
                                $canceled = \App\Models\Order::when(isset($zone), function ($query) use ($zone) {
                                    return $query->where('zone_id', $zone->id);
                                })
                                ->when(isset($key), function ($query) use ($key) {
                                        return $query->where(function ($q) use ($key) {
                                                foreach ($key as $value) {
                                                    $q->orWhere('id', 'like', "%{$value}%");
                                                }
                                            });
                                    })
                                    ->when(request('module_id'), function ($query) {
                                        return $query->module(request('module_id'));
                                    })
                                    ->where(['order_status' => 'refunded'])
                                    ->when(isset($store), function ($query) use ($store) {
                                        return $query->where('store_id', $store->id);
                                    })
                                    ->when(isset($from) && isset($to) && $from != null && $to != null && $filter == 'custom', function ($query) use ($from, $to) {
                                        return $query->whereBetween('created_at', [$from . ' 00:00:00', $to . ' 23:59:59']);
                                    })
                                    ->when(isset($filter) && $filter == 'this_year', function ($query) {
                                        return $query->whereYear('created_at', now()->format('Y'));
                                    })
                                    ->when(isset($filter) && $filter == 'this_month', function ($query) {
                                        return $query->whereMonth('created_at', now()->format('m'))->whereYear('created_at', now()->format('Y'));
                                    })
                                    ->when(isset($filter) && $filter == 'this_month', function ($query) {
                                        return $query->whereMonth('created_at', now()->format('m'))->whereYear('created_at', now()->format('Y'));
                                    })
                                    ->when(isset($filter) && $filter == 'previous_year', function ($query) {
                                        return $query->whereYear('created_at', date('Y') - 1);
                                    })
                                    ->when(isset($filter) && $filter == 'this_week', function ($query) {
                                        return $query->whereBetween('created_at', [
                                            now()
                                                ->startOfWeek()
                                                ->format('Y-m-d H:i:s'),
                                            now()
                                                ->endOfWeek()
                                                ->format('Y-m-d H:i:s'),
                                        ]);
                                    })
                                    ->Notpos()
                                    // ->sum(DB::raw('order_amount - original_delivery_charge'));
                                    ->sum(DB::raw('order_amount - delivery_charge - dm_tips'));
                            @endphp
                            <a class="__card-3 h-100" href="#">
                                <img src="{{ asset('/public/assets/admin/img/report/new/trx3.png') }}" class="icon"
                                    alt="report/new">
                                <h3 class="title text-FF5A54">{{ \App\CentralLogics\Helpers::number_format_short($canceled) }}
                                </h3>
                                <h6 class="subtitle">{{ translate('Refunded Transaction') }}</h6>
                                <div class="info-icon" data-toggle="tooltip" data-placement="top"
                                    data-original-title="{{ translate('If the order is successfully refunded, the full order amount goes to this section without the delivery fee and delivery tips.') }}">
                                    <img src="{{ asset('/public/assets/admin/img/report/new/info3.png') }}"
                                        alt="report/new">
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="row g-2">
                        <div class="col-md-12">
                            <div class="__card-vertical">
                                <div class="__card-vertical-img">
                                    <img class="img"
                                        src="{{ asset('/public/assets/admin/img/report/new/admin-earning.png') }}"
                                        alt="">
                                    <h4 class="name">{{ translate('Admin Earning') }}</h4>
                                    <div class="info-icon" data-toggle="tooltip" data-placement="right"
                                        data-original-title="{{ translate('Deducting the admin discount from the admin earning amount and goes to this section.') }}">
                                        <img src="{{ asset('/public/assets/admin/img/report/new/info1.png') }}"
                                            alt="report/new">
                                    </div>
                                </div>
                                <h4 class="earning text-0661CB">
                                    {{ \App\CentralLogics\Helpers::number_format_short($admin_earned) }}</h4>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="__card-vertical">
                                <div class="__card-vertical-img">
                                    <img class="img"
                                        src="{{ asset('/public/assets/admin/img/report/new/store-earning.png') }}"
                                        alt="">
                                    <h4 class="name">{{ translate('Store Earning') }}</h4>
                                    <div class="info-icon" data-toggle="tooltip" data-placement="right"
                                        data-original-title="{{ translate('Adding_store_earning_amount_with_vat/tax_amount') }}">
                                        <img src="{{ asset('/public/assets/admin/img/report/new/info2.png') }}"
                                            alt="report/new">
                                    </div>
                                </div>
                                <h4 class="earning text-00AA6D">
                                    {{\App\CentralLogics\Helpers::number_format_short($store_earned) }}</h4>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="__card-vertical">
                                <div class="__card-vertical-img">
                                    <img class="img"
                                        src="{{ asset('/public/assets/admin/img/report/new/deliveryman-earning.png') }}"
                                        alt="">
                                    <h4 class="name">{{ translate('Deliveryman Earning') }}</h4>
                                    <div class="info-icon" data-toggle="tooltip" data-placement="right"
                                        data-original-title="{{ translate('Deducting the admin commission on the delivery fee, the delivery fee & tips amount goes to earning section.') }}">
                                        <img src="{{ asset('/public/assets/admin/img/report/new/info3.png') }}"
                                            alt="report/new">
                                    </div>
                                </div>
                                <h4 class="earning text-FF7500">
                                    {{ \App\CentralLogics\Helpers::number_format_short($deliveryman_earned) }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- End Stats -->
        <!-- Card -->
        <div class="card mt-3">
            <!-- Header -->
            <div class="card-header border-0 py-2">
                <div class="search--button-wrapper">
                    <h3 class="card-title">
                        {{ translate('messages.order_transactions') }} <span
                            class="badge badge-soft-secondary" id="countItems">{{ $order_transactions->total() }}</span>
                    </h3>
                    <form class="search-form">
                        <!-- Search -->
                        <div class="input--group input-group input-group-merge input-group-flush">
                            <input class="form-control" placeholder="{{ translate('Search by Order ID') }}" value="{{ request()?->search ?? null}}" name="search">
                            <button type="submit" class="btn btn--secondary"><i class="tio-search"></i></button>
                        </div>
                        <!-- End Search -->
                    </form>
                    <!-- Static Export Button -->
                    <div class="hs-unfold ml-3">
                        <a class="js-hs-unfold-invoker btn btn-sm btn-white dropdown-toggle btn export-btn font--sm"
                            href="javascript:;"
                            data-hs-unfold-options="{
                                &quot;target&quot;: &quot;#usersExportDropdown&quot;,
                                &quot;type&quot;: &quot;css-animation&quot;
                            }"
                            data-hs-unfold-target="#usersExportDropdown" data-hs-unfold-invoker="">
                            <i class="tio-download-to mr-1"></i> {{ translate('export') }}
                        </a>

                        <div id="usersExportDropdown"
                            class="hs-unfold-content dropdown-unfold dropdown-menu dropdown-menu-sm-right hs-unfold-content-initialized hs-unfold-css-animation animated hs-unfold-reverse-y hs-unfold-hidden">

                            <span class="dropdown-header">{{ translate('download_options') }}</span>
                            <a id="export-excel" class="dropdown-item"
                                href="{{ route('admin.transactions.report.day-wise-report-export', ['type' => 'excel', request()->getQueryString()]) }}">
                                <img class="avatar avatar-xss avatar-4by3 mr-2"
                                    src="{{ asset('public/assets/admin/svg/components/excel.svg') }}"
                                    alt="Image Description">
                                {{ translate('messages.excel') }}
                            </a>
                            <a id="export-csv" class="dropdown-item"
                                href="{{ route('admin.transactions.report.day-wise-report-export', ['type' => 'csv', request()->getQueryString()]) }}">
                                <img class="avatar avatar-xss avatar-4by3 mr-2"
                                    src="{{ asset('public/assets/admin/svg/components/placeholder-csv-format.svg') }}"
                                    alt="Image Description">
                                .{{ translate('messages.csv') }}
                            </a>

                        </div>
                    </div>
                    <!-- Static Export Button -->
                </div>
            </div>
            <!-- End Header -->

            <!-- Body -->
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table id="datatable" class="table table-thead-bordered table-align-middle card-table">
                        <thead class="thead-light">
                            <tr>
                                <th class="border-0">{{ translate('sl') }}</th>
                                <th class="border-0">{{ translate('messages.order_id') }}</th>
                                <th class="border-0">{{ translate('messages.store') }}</th>
                                <th class="border-0">{{ translate('messages.customer_name') }}</th>
                                <th class="border-0 min-w-120">{{ translate('messages.total_item_amount') }}</th>
                                <th class="border-0">{{ translate('messages.item_discount') }}</th>
                                <th class="border-0">{{ translate('messages.coupon_discount') }}</th>
                                <th class="border-0">{{ translate('messages.referral_discount') }}</th>
                                <th class="border-0">{{ translate('messages.discounted_amount') }}</th>
                                <th class="border-0">{{ translate('messages.vat/tax') }}</th>
                                <th class="border-0">{{ translate('messages.delivery_charge') }}</th>
                                <th class="border-0">{{ translate('messages.order_amount') }}</th>
                                <th class="border-0">{{ translate('messages.admin_discount') }}</th>
                                <th class="border-0">{{ translate('messages.store_discount') }}</th>
                                <th class="border-0">{{ translate('messages.admin_commission') }}</th>
                                <th class="border-0">{{ \App\CentralLogics\Helpers::get_business_data('additional_charge_name')??translate('messages.additional_charge') }}</th>
                                <th class="border-0">{{ translate('messages.extra_packaging_amount') }}</th>
                                <th class="min-w-140 text-capitalize">{{ translate('commision_on_delivery_charge') }}</th>
                                <th class="min-w-140 text-capitalize">{{ translate('admin_net_income') }}</th>
                                <th class="min-w-140 text-capitalize">{{ translate('store_net_income') }}</th>
                                <th class="border-0 min-w-120">{{ translate('messages.amount_received_by') }}</th>
                                <th class="border-top border-bottom text-capitalize">{{ translate('messages.payment_method') }}</th>
                                <th class="border-0">{{ translate('messages.payment_status') }}</th>
                                <th class="border-0">{{ translate('messages.action') }}</th>
                            </tr>
                        </thead>
                        <tbody id="set-rows">
                            @foreach ($order_transactions as $k => $ot)
                                <tr scope="row">
                                    <td>{{ $k + $order_transactions->firstItem() }}</td>
                                    @if ($ot->order->order_type == 'parcel')
                                        <td><a
                                                href="{{ route('admin.transactions.parcel.order.details', $ot->order_id) }}">{{ $ot->order_id }}</a>
                                        </td>
                                    @else
                                        <td><a
                                                href="{{ route('admin.transactions.order.details', $ot->order_id) }}">{{ $ot->order_id }}</a>
                                        </td>
                                    @endif
                                    <td  class="text-capitalize">
                                        @if($ot->order->store)
                                            {{Str::limit($ot->order->store->name,25,'...')}}
                                        @else
                                            <label class="badge badge-soft-success white-space-nowrap">{{ translate('messages.parcel_order') }}
                                        @endif
                                    </td>
                                    <td class="white-space-nowrap">
                                        @if ($ot->order->customer)
                                            <a class="text-body text-capitalize"
                                                href="{{ route('admin.users.customer.view', [$ot->order['user_id']]) }}">
                                                <strong>{{ $ot->order->customer['f_name'] . ' ' . $ot->order->customer['l_name'] }}</strong>
                                            </a>
                                        @else
                                            <label class="badge badge-danger">{{ translate('messages.invalid_customer_data') }}</label>
                                        @endif
                                    </td>
                                    {{-- total_item_amount --}}
                                    <td class="white-space-nowrap">{{ \App\CentralLogics\Helpers::format_currency($ot->order['order_amount'] - $ot->additional_charge - $ot->order['dm_tips']-$ot->order['delivery_charge']  - $ot['tax'] - $ot->order['extra_packaging_amount'] + $ot->order['coupon_discount_amount'] + $ot->order['store_discount_amount'] + $ot->order['ref_bonus_amount']  +$ot->order['flash_admin_discount_amount'] +$ot->order['flash_store_discount_amount']) }}</td>

                                    {{-- item_discount --}}
                                    <td class="white-space-nowrap">{{ \App\CentralLogics\Helpers::format_currency($ot->order->details()->sum(DB::raw('discount_on_item * quantity')) + $ot->order['flash_admin_discount_amount'] +$ot->order['flash_store_discount_amount']) }}</td>

                                    {{-- coupon_discount --}}
                                    <td class="white-space-nowrap">{{ \App\CentralLogics\Helpers::format_currency($ot->order['coupon_discount_amount']) }}</td>
                                    {{-- referral_discount --}}
                                    <td class="white-space-nowrap">{{ \App\CentralLogics\Helpers::format_currency($ot->order['ref_bonus_amount']) }}</td>
                                    {{-- discounted_amount --}}
                                    <td class="white-space-nowrap">  {{ \App\CentralLogics\Helpers::format_currency($ot->order['coupon_discount_amount'] + $ot->order['store_discount_amount']+$ot->order['flash_store_discount_amount']+$ot->order['flash_admin_discount_amount'] +$ot->order['ref_bonus_amount']) }}</td>

                                    <td class="white-space-nowrap">{{ \App\CentralLogics\Helpers::format_currency($ot->tax) }}</td>
                                    <td class="white-space-nowrap">{{ \App\CentralLogics\Helpers::format_currency($ot->delivery_charge) }}</td>
                                    <td class="white-space-nowrap">{{ \App\CentralLogics\Helpers::format_currency($ot->order_amount) }}</td>

                                    {{-- admin_discount --}}
                                    <td class="white-space-nowrap">{{ \App\CentralLogics\Helpers::format_currency($ot->admin_expense) }}</td>

                                    {{-- store_discount --}}
                                    <td class="white-space-nowrap">{{ \App\CentralLogics\Helpers::format_currency($ot->discount_amount_by_store+$ot->order['flash_store_discount_amount']) }}</td>

                                    {{-- admin_commission --}}
                                    <td class="white-space-nowrap">{{ \App\CentralLogics\Helpers::format_currency(($ot->admin_commission + $ot->admin_expense) - $ot->delivery_fee_comission -$ot->additional_charge - $ot->order['flash_admin_discount_amount'] ) }}</td>

                                    <td class="white-space-nowrap">{{ \App\CentralLogics\Helpers::format_currency(($ot->additional_charge)) }}</td>
                                    <td class="white-space-nowrap">{{ \App\CentralLogics\Helpers::format_currency(($ot->extra_packaging_amount)) }}</td>
                                    <td class="white-space-nowrap">{{ \App\CentralLogics\Helpers::format_currency($ot->delivery_fee_comission) }}</td>
                                    {{-- admin_net_income --}}
                                    <td class="white-space-nowrap">{{ \App\CentralLogics\Helpers::format_currency(($ot->admin_commission - $ot->order['flash_admin_discount_amount'])) }}</td>

                                    {{-- store_net_income --}}
                                    <td class="white-space-nowrap">{{ \App\CentralLogics\Helpers::format_currency($ot->store_amount - $ot->tax) }}</td>
                                    @if ($ot->received_by == 'admin')
                                        <td class="text-capitalize white-space-nowrap">{{ translate('messages.admin') }}</td>
                                    @elseif ($ot->received_by == 'deliveryman')
                                        <td class="text-capitalize white-space-nowrap">
                                            <div>{{ translate('messages.delivery_man') }}</div>
                                            <div class="text-right mw--85px">
                                                @if (isset($ot->delivery_man) && $ot->delivery_man->earning == 1)
                                                <span class="badge badge-soft-primary">
                                                    {{translate('messages.freelance')}}
                                                </span>
                                                @elseif (isset($ot->delivery_man) && $ot->delivery_man->earning == 0 && $ot->delivery_man->type == 'restaurant_wise')
                                                <span class="badge badge-soft-warning">
                                                    {{translate('messages.restaurant')}}
                                                </span>
                                                @elseif (isset($ot->delivery_man) && $ot->delivery_man->earning == 0 && $ot->delivery_man->type == 'zone_wise')
                                                <span class="badge badge-soft-success">
                                                    {{translate('messages.admin')}}
                                                    </span>
                                                @endif
                                            </div>
                                        </td>
                                    @elseif ($ot->received_by == 'store')
                                        <td class="text-capitalize white-space-nowrap">{{ translate('messages.store') }}</td>
                                    @endif
                                    <td class="mw--85px text-capitalize min-w-120 ">
                                            {{ translate(str_replace('_', ' ', $ot->order['payment_method'])) }}
                                    </td>
                                    <td class="text-capitalize white-space-nowrap">
                                        @if ($ot->status)
                                        <span class="badge badge-soft-danger">
                                            {{translate('messages.refunded')}}
                                          </span>
                                        @else
                                        <span class="badge badge-soft-success">
                                            {{translate('messages.completed')}}
                                          </span>
                                        @endif
                                    </td>

                                    <td>
                                        <div class="btn--container justify-content-center">
                                            <a class="btn btn-outline-success square-btn btn-sm mr-1 action-btn"  href="{{route('admin.report.generate-statement',[$ot['id']])}}">
                                                <i class="tio-download-to"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- End Body -->
            @if (count($order_transactions) !== 0)
                <hr>
            @endif
            <div class="page-area">
                {!! $order_transactions->links() !!}
            </div>
            @if (count($order_transactions) === 0)
                <div class="empty--data">
                    <img src="{{ asset('/public/assets/admin/svg/illustrations/sorry.svg') }}" alt="public">
                    <h5>
                        {{ translate('no_data_found') }}
                    </h5>
                </div>
            @endif
        </div>
        <!-- End Card -->
    </div>
@endsection

@push('script')
@endpush

@push('script_2')
    <script src="{{ asset('public/assets/admin') }}/vendor/chart.js/dist/Chart.min.js"></script>
    <script src="{{ asset('public/assets/admin') }}/vendor/chartjs-chart-matrix/dist/chartjs-chart-matrix.min.js">
    </script>
    <script src="{{ asset('public/assets/admin') }}/js/hs.chartjs-matrix.js"></script>
    <script src="{{ asset('public/assets/admin') }}/js/view-pages/admin-reports.js"></script>

    <script>
        "use strict";
        $(document).on('ready', function() {
            $('.js-data-example-ajax').select2({
                ajax: {
                    url: '{{ url('/') }}/admin/store/get-stores',
                    data: function(params) {
                        return {
                            q: params.term, // search term
                            // all:true,
                            @if (isset($zone))
                                zone_ids: [{{ $zone->id }}],
                            @endif
                            @if (request('module_id'))
                                module_id: {{ request('module_id') }},
                            @endif
                            page: params.page
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data
                        };
                    },
                    __port: function(params, success, failure) {
                        let $request = $.ajax(params);

                        $request.then(success);
                        $request.fail(failure);

                        return $request;
                    }
                }
            });
        });

        $('#search-form').on('submit', function(e) {
            e.preventDefault();
            let formData = new FormData(this);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '{{ route('admin.transactions.report.day-wise-report-search') }}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $('#loading').show();
                },
                success: function(data) {
                    $('#set-rows').html(data.view);
                    $('#countItems').html(data.count);
                    $('.page-area').hide();
                },
                complete: function() {
                    $('#loading').hide();
                },
            });
        });
    </script>
@endpush
