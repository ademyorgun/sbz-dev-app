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
        <div class="col-md-9">
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
        <div class="col-md-3">
            <div class="panel panel-primary panelbordered">
                <div class="panel-heading">
                    <h3 class="panel-title panel-icon"><i class="voyager-archive"></i>{{ __('voyager::generic.appointments_per_month') }}</h3>
                    <div class="panel-actions">
                        <a class="panel-action voyager-angle-up" data-toggle="panel-collapse" aria-hidden="true"></a>
                    </div>
                </div>
                <div class="panel-body mt-2">
                    <reports-total-card :reports-total="numOfAllApointments"></reports-total-card>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary panelbordered">
                <div class="panel-heading">
                    <h3 class="panel-title panel-icon"><i class="voyager-archive"></i>{{ __('voyager::generic.appointments_per_user') }}</h3>
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
                <div class="panel-heading" style="margin-bottom: 2em">
                    <h3 class="panel-title panel-icon"><i class="voyager-bar-chart"></i>{{ __('voyager::generic.general_stats') }}</h3>
                    <div class="panel-actions">
                        <a class="panel-action voyager-angle-up" data-toggle="panel-collapse" aria-hidden="true"></a>
                    </div>
                </div>
                <div class="panel-body mt-2 graphs">
                    <div class="row row-flex">
                        <reports-bar-chart :data-to-load="numOfAppointmentsPerDay"></reports-bar-chart>
                        <reports-pie-chart :data-to-load="numOfAppointmentsPerStatus"></reports-pie-chart>
                    </div>
                    <div class="row row-flex">
                        <reports-line-chart :data-to-load="numOfAllApointmentsPerDayPositive" background-color="#70D6FF" label="Positive"></reports-line-chart>
                        <reports-line-chart :data-to-load="numOfAllApointmentsPerDayNegative" background-color="#ED254E" label="Nositive"></reports-line-chart>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
<style>
    .row-flex {
        display: flex;
        flex-direction: row;
        justify-content: space-around;
        margin-bottom: 3em;
        max-width: 100%;
    }
    .panel-body.graphs {
        padding-right: 0;
        padding-left: 0;
        padding-top: 2em;
        padding-bottom: 3em;
    } 
    @media(max-width: 700px) {
        .row-flex {
            flex-direction: column;
            align-items: center;
            margin: 0;
        }
        .panel-body.graphs {
            padding-top: 1em;
            padding-bottom: 0;
        }
    }
</style>
    
@stop
@section('javascript')
    <!-- VUEJS -->
    <script src="js/pages/reports.js"></script>
@stop
