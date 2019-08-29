<!DOCTYPE html>
<html lang="pl">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-2"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@lang('common.invoice_for') {{$interval->start_date->format("Y-m-d")}}_{{$interval->end_date->format("Y-m-d")}}</title>

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

    <style>
        body {
            font-family: DejaVu Sans;
            font-size: 11px;
        }
        th {
            background-color: #33ff33;
            color: #f2f2f2;
        } 
        table {
            font-size: 9px;
        }
        .green-font-color {
            color: #33ff33
        }
        .bottom-table {
            padding-left: 50%;
            padding-right: 2%;
        }
        .bottom-tile {
            background-color: #33ff33;
            border: 1px #f2f2f2 solid;
            color: #f2f2f2;
            font-size: 18px;
            text-align: center;
            height: 81px;
        }
    </style>

</head>
<body>
    
    <div style="padding-left: 2rem; padding-right: 2rem;">
        
        <div class="green-font-color text-center">
            <h2>@lang('common.invoice')</h2>
            <p>Nr {{$substart->id}}/{{$interval->start_date->format("Y/m")}}</p>
        </div>
        
        <div class="row">
            <div class="col-xs-2" style="text-align: right;">
                <strong>@lang('common.executor') : </strong><br>
                <strong>@lang('common.address') : </strong><br>
                <strong>@lang('common.phone_number'): </strong><br>
                <strong>@lang('common.email_address') : </strong><br>
                <strong>Nip: </strong><br>
            </div>
            
            <div class="col-xs-3" style="text-align: left;">
                {{config('app.name')}}<br>
                :todo ??         !!!!Adres firmy!!!!<br>
                602-342-396<br>
                {{$adminInvoiceData->email}}<br>
                {{$adminInvoiceData->nip}}<br>
            </div>
            
            <div class="col-xs-2" style="text-align: right;">
                <strong>@lang('common.receiver') :</strong><br>
                <strong>@lang('common.address') :</strong><br>
                @if ($bossInvoiceData->phone_number)
                    <strong>@lang('common.phone_number') :</strong><br>
                @endif
                <strong>@lang('common.email_address') : </strong><br>
                <strong>Nip :</strong><br>
            </div>

            <div class="col-xs-3" style="text-align: left;">
                {{$bossInvoiceData->company_name}}<br>
                {{$bossProperty->street}}
                @if ($bossProperty->street_number && $bossProperty->house_number)
                    {{$bossProperty->street_number}} / {{$bossProperty->house_number}}
                @endif
                <br>
                @if ($bossInvoiceData->phone_number)
                    {{$bossInvoiceData->phone_number}}<br>
                @endif
                {{$bossInvoiceData->email}}<br>
                {{$bossInvoiceData->nip}}<br>
            </div>
        </div>

        <hr style="border-color: #33ff33;">

        <div class="row">
            <div class="col-xs-2" style="text-align: right;">
                <strong>@lang('common.date_of_issue') :</strong><br>
                <strong>@lang('common.payment_deadline') :</strong><br>
            </div>
            
            <div class="col-xs-3" style="text-align: left;">
                {{$interval->end_date->format("d/m/Y")}}<br>
                {{date('d/m/Y', strtotime("+2 week", strtotime($interval->end_date->format("Y-m-d"))))}}<br>
            </div>
            
            <div class="col-xs-2" style="text-align: right;">
                <strong>@lang('common.payment_method') :</strong><br>
                <strong>Bank :</strong><br>
                <strong>@lang('common.account_number') :</strong><br>
            </div>
            
            <div class="col-xs-3" style="text-align: left;">
                @lang('common.transfer')<br>
                {{$adminInvoiceData->bank_name}}<br>
                {{$adminInvoiceData->account_number}}<br>
            </div>
        </div>
        
        <div style="margin-bottom: 0px">&nbsp;</div>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th scope="col">Lp.</th>
                    <th scope="col-3">@lang('common.label')</th>
                    <th scope="col">@lang('common.quantity')</th>
                    <th scope="col">Jm</th>
                    <th scope="col">@lang('common.net_price')</th>
                    <th scope="col">@lang('common.discount') %</th>
                    <th scope="col">@lang('common.net_price_after_discount')</th>
                    <th scope="col">VAT</th>
                    <th scope="col">@lang('common.net_amount')</th>
                    <th scope="col">@lang('common.vat_amount')</th>
                    <th scope="col">@lang('common.the_gross_amount')</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td scope="col">1</td>
                    <td scope="col-3">{{$subscription->name}}</td>
                    <td scope="col">{{$intervalWorkersCount}}</td>
                    <td scope="col"></td>
                    <td scope="col">{{$subscriptionSingleNetPrice}} zł</td>
                    <td scope="col">{{$subscriptionSingleDiscount}} %</td>
                    <td scope="col">{{$subscriptionSingleNetPriceAfterDiscount}} zł</td>
                    <td scope="col">{{$VAT}}</td>
                    <td scope="col">{{$subscriptionAllNetPrice}} zł</td>
                    <td scope="col">{{$theAmountOfVAT}} zł</td>
                    <td scope="col">{{$subscriptionAllGrossPrice}} zł</td>
                </tr>
            </tbody>
        </table>
        
        <div class="row">
            <div class="col-6 offset-6">
                <table class="table table-bordered bottom-table">
                    <thead>
                        <tr>
                            <th scope="col">@lang('common.vat_rate')</th>
                            <th scope="col">@lang('common.net')</th>
                            <th scope="col">VAT</th>
                            <th scope="col">@lang('common.gross')</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{$VAT}} %</td>
                            <td>{{$subscriptionAllNetPrice}} zł</td>
                            <td>{{$theAmountOfVAT}} zł</td>
                            <td>{{$subscriptionAllGrossPrice}} zł</td>
                        </tr>
                    <tbody>
                </table>
            </div>
        </div>
        
        <div class="row" style="padding-right: 6rem;">
            <div class="col-xs-8"></div>
            <div class="col-xs-4">
                <div class="bottom-tile">
                    @lang('common.together_to_pay') : <br>
                    <strong>{{$subscriptionAllGrossPrice}} zł</strong>
                </div>
            </div>
        </div>

        <div style="margin-bottom: 0px">&nbsp;</div>
        <div style="margin-bottom: 0px">&nbsp;</div>
        <div style="margin-bottom: 0px">&nbsp;</div>
        <div style="margin-bottom: 0px">&nbsp;</div>
        <div style="margin-bottom: 0px">&nbsp;</div>
        <div style="margin-bottom: 0px">&nbsp;</div>
        <div style="margin-bottom: 0px">&nbsp;</div>
        <div style="margin-bottom: 0px">&nbsp;</div>
        
        <div class="row" style="padding-right: 6rem;">
            <div class="col-xs-6 text-center">
                <p class="text-center">----------------------------------------------------------------------</p>
                <p class="text-center">@lang('common.person_authorized_to_pick_up')</p>
            </div>
            <div class="col-xs-6">
                <p class="text-center">----------------------------------------------------------------------</p>
                <p class="text-center">@lang('common.person_authorized_to_issue')</p>
            </div>
        </div>
    </div>
</body>
</html>