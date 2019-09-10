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
                    {{-- Reports --}}
                    <h3 class="panel-title panel-icon"><i class="voyager-search"></i>Berichte</h3>
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
                    {{-- appointments per month --}}
                    <h3 class="panel-title panel-icon"><i class="voyager-archive"></i>Termine in diesem Monat</h3>
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
    @if (strtolower(auth()->user()->role->name) != 'sales_agent')    
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-primary panelbordered">
                    <div class="panel-heading">
                        {{-- number of appointments per sales agent --}}
                        <h3 class="panel-title panel-icon"><i class="voyager-archive"></i>Anzahl Termine pro Sales</h3>
                        <div class="panel-actions">
                            <a class="panel-action voyager-angle-up" data-toggle="panel-collapse" aria-hidden="true"></a>
                        </div>
                    </div>
                    <div class="panel-body mt-2">
                        <reports-sales-agents-table :sales-agents-data="numOfAppointmentsPerSalesAgent"></reports-sales-agents-table>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="panel panel-primary panelbordered">
                    <div class="panel-heading">
                        {{-- number of appointments per call agent --}}
                        <h3 class="panel-title panel-icon"><i class="voyager-archive"></i>Anzahl Termine pro Call agent</h3>
                        <div class="panel-actions">
                            <a class="panel-action voyager-angle-up" data-toggle="panel-collapse" aria-hidden="true"></a>
                        </div>
                    </div>
                    <div class="panel-body mt-2">
                        <reports-call-agents-table :call-agents-data="numOfAppointmentsPerCallAgent"></reports-call-agents-table>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="panel panel-primary panelbordered">
                    <div class="panel-heading">
                        {{-- number of appointments per call agent --}}
                        <h3 class="panel-title panel-icon"><i class="voyager-archive"></i>Call centers</h3>
                        <div class="panel-actions">
                            <a class="panel-action voyager-angle-up" data-toggle="panel-collapse" aria-hidden="true"></a>
                        </div>
                    </div>
                    <div class="panel-body mt-2">
                        <reports-call-centers-table :call-centers-data="callCenters"></reports-call-centers-table>
                    </div>
                </div>
            </div>
        </div>
    @endif
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary panelbordered">
                <div class="panel-heading" style="margin-bottom: 2em">
                    {{-- general stats --}}
                    <h3 class="panel-title panel-icon"><i class="voyager-bar-chart"></i>Berichtübersicht</h3>
                    <div class="panel-actions">
                        <a class="panel-action voyager-angle-up" data-toggle="panel-collapse" aria-hidden="true"></a>
                    </div>
                </div>
                <div class="panel-body mt-2 graphs">
                    <div class="row row-flex">
                        <reports-bar-chart :data-to-load="numOfAppointmentsPerDay" background-color="#E4572E" label="Termine pro Tag"></reports-bar-chart>
                        <reports-pie-chart :data-to-load="numOfAppointmentsPerStatus"></reports-pie-chart>
                    </div>
                    <div class="row row-flex">
                        <reports-bar-chart :data-to-load="numOfAllApointmentsPerDayPositive" background-color="#70D6FF" label="Positive Termine"></reports-bar-chart>
                        <reports-bar-chart :data-to-load="numOfAllApointmentsPerDayNegative" background-color="#ED254E" label="Negative Termine"></reports-bar-chart>
                    </div>
                    {{-- <div class="row row-flex">
                        <reports-bar-chart :data-to-load="numberOfAppointmentsWonPerDay" background-color="#00CECB" label="Appointments Won"></reports-bar-chart>
                        <reports-bar-chart :data-to-load="numberOfAppointmentsNotWonPerDay" background-color="#ED254E" label="Appointments not Won"></reports-bar-chart>
                    </div> --}}
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
        justify-content: center;
        margin-bottom: 3em;
        max-width: 100%;
        margin-right: 0; 
        margin-left: 0;
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
