@extends('voyager::master')


@section('page_header')
    <div class="container-fluid">
        <h1 class="page-title">
            <i class="voyager-calendar"></i> {{ __('voyager::generic.reports') }}
        </h1>
    </div>
@stop

@section('content')
<div class="page-content browse container-fluid" id="app-reports">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary panelbordered">
                <div class="panel-heading">
                    <h3 class="panel-title panel-icon"><i class="voyager-search"></i>{{ __('voyager::generic.reports') }}</h3>
                    <div class="panel-actions">
                        <a class="panel-action voyager-angle-up" data-toggle="panel-collapse" aria-hidden="true"></a>
                    </div>
                </div>
                <div class="panel-body mt-2">
                    <reports-filter @fetch-data="fetchData"></reports-filter>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary panelbordered">
                <div class="panel-heading">
                    <h3 class="panel-title panel-icon"><i class=""></i>{{ __('voyager::generic.appointments_per_user') }}</h3>
                    <div class="panel-actions">
                        <a class="panel-action voyager-angle-up" data-toggle="panel-collapse" aria-hidden="true"></a>
                    </div>
                </div>
                <div class="panel-body mt-2">
                    <reports-users-table :users-data="numOfAppointmentsPerUser"></reports-users-table>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary panelbordered">
                <div class="panel-heading">
                    <h3 class="panel-title panel-icon"><i class=""></i>{{ __('voyager::generic.general_stats') }}</h3>
                    <div class="panel-actions">
                        <a class="panel-action voyager-angle-up" data-toggle="panel-collapse" aria-hidden="true"></a>
                    </div>
                </div>
                <div class="panel-body mt-2">
                    <reports-chart :data-to-load="numOfAppointmentsPerDay"></reports-chart>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('javascript')
    <!-- VUEJS -->
    <script src="js/pages/reports.js"></script>
@stop
