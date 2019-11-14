@extends('layouts.app')
@section('content')

{!! Html::style('css/welcome.css') !!}

    <div class="container-fluid">
        <div class="row jumbotron">
            <div class="col-12">
                <h2 class="text-center">@lang('common.rodo_policy')</h2>
            </div>
        </div>
    </div>
        
    <div class="container">
    
        <div class="row" style="padding: 1rem 0 1rem 0;">
            <div class="col-1"></div>
            <div id="first-paragraph" class="col-10">
                <h5>
                    <strong>
                        INFORMACJA O TWOICH DANYCH
                    </strong>
                </h5>
                <div id="terms">
                    <p>Szanowni Państwo,</p>
                    <p>
                        25 maja 2018 roku zaczęło obowiązywać Rozporządzenie Parlamentu Europejskiego i Rady (UE) 2016/679 z dnia 27 kwietnia 2016 r. w sprawie ochrony osób fizycznych w związku z przetwarzaniem danych osobowych i w sprawie swobodnego przepływu takich danych oraz uchylenia dyrektywy 95/46/WE (określane jako „RODO”, „ORODO”, „GDPR” lub „Ogólne Rozporządzenie o Ochronie Danych”).
                        W celu dalszego świadczenia Państwu usług za pośrednictwem serwisu {{ config('app.name') }} {{ config('app.name_2nd_part') }} oraz lepszego dopasowania treści marketingowych, chcielibyśmy Państwa poinformować o przetwarzaniu danych oraz zasadach, na jakich będzie się to odbywało po dniu 25 maja 2018 roku.
                    </p>
                    <h4>
                        <strong>
                            Jakie dane przetwarzamy?
                        </strong>
                    </h4>
                    <p>
                       Chodzi o dane osobowe, które są zbierane w ramach korzystania przez Państwa z serwisu {{ config('app.name') }} {{ config('app.name_2nd_part') }}, w tym zapisywane w plikach cookies.
                    </p>
                    <h4>
                        <strong>
                            Kto jest Administratorem Państwa danych?
                        </strong>
                    </h4>
                    <p>
                        Administratorem bazy danych jest {{ config('app.name') }} {{ config('app.name_2nd_part') }}, z siedzibą w Warszawie (02-672) przy ul. Domaniewskiej 17/19/109.
                    </p>
                    <h4>
                        <strong>
                            W jakim celu chcemy przetwarzać Państwa dane?
                        </strong>
                    </h4>
                    <ul>
                        <li>Dopasowujemy treści wyświetlane na naszych stronach do indywidualnych gustów i potrzeb oraz ciągle doskonalimy jakość oferowanych usług, korzystając z analiz Państwa danych.</li>
                        <li>Przetwarzanie danych umożliwia nam zwiększenie bezpieczeństwa usług świadczonych za pośrednictwem {{ config('app.name') }} {{ config('app.name_2nd_part') }} (np. wykrywamy osoby łamiące regulamin Serwisu, zagrażające innym Użytkownikom, boty).</li>
                        <li>Przetwarzamy dane Użytkowników {{ config('app.name') }} {{ config('app.name_2nd_part') }} w celu umożliwienia korzystania z serwisu (zgodnie z Regulaminem i 
                            <a target="_blank" href="{{ URL::to('private-policy') }}">
                                    @lang('private_policy.private_policy')
                            </a>
                            ).
                        </li>
                    </ul>
                    <h4>
                        <strong>
                            Jak długo będziemy przetwarzać Państwa dane?
                        </strong>
                    </h4>
                    <p>
                        Dane przetwarzamy od momentu udzielenia odpowiedniej zgody do momentu jej odwołania / żądania zaprzestania przetwarzania danych osobowych / żądania usunięcia przetwarzania danych osobowych przez ich właściciela. Dane zbierane w ramach profilowania przetwarzamy od momentu rozpoczęcia korzystania z serwisu {{ config('app.name') }} {{ config('app.name_2nd_part') }} (wejścia na naszą stronę) do momentu wyrażenia sprzeciwu wobec profilowania.
                    </p>
                    <h4>
                        <strong>
                            Czy możemy przekazywać dane?
                        </strong>
                    </h4>
                    <p>
                        {{ config('app.name') }} {{ config('app.name_2nd_part') }} przekazuje Państwa dane jedynie podmiotom przetwarzającym je na jego zlecenie i będącym jego podwykonawcami (np. firmom programistycznym) oraz na żądanie podmiotów uprawnionych do ich uzyskania na podstawie obowiązującego prawa (np. sądom). Państwa dane nie będą przekazywane poza Europejski Obszar Gospodarczy ani udostępniane organizacjom międzynarodowym.
                    </p>
<!--                    <h4>
                        <strong>
                            Do kogo można się zwrócić po dalsze informacje odnośnie przetwarzanych przez nas danych?
                        </strong>
                    </h4>
                    <p>
                        W przypadku jakichkolwiek wątpliwości dotyczących danych osobowych prosimy pisać do naszego Inspektora Danych Osobowych rodo@masazplusdlafirm.pl , ul. Domaniewska 17/19/109 02-672 Warszawa, Polska.
                    </p>
                    (CZY W PRZYPADKU DOPIERO STARTUJĄCEJ JEDNOSOBOWEJ DZIAŁALNOŚCI GOSPODARCZEJ, MOGĘ BYĆ SWOIM WŁASNYM IDO??)-->
                    
                    
<!--                    <h4>
                        <strong>
                            Jakie mają Państwo prawa w stosunku do swoich danych?
                        </strong>
                    </h4>
                    <ul>
                        <li>Posiadają Państwo prawo dostępu do treści swoich danych oraz prawo ich sprostowania, usunięcia, ograniczenia przetwarzania, prawo do przenoszenia danych, prawo wniesienia sprzeciwu, prawo do cofnięcia zgody w dowolnym momencie bez wpływu na zgodność z prawem przetwarzania, którego dokonano na podstawie zgody przed jej cofnięciem. Zgoda może zostać cofnięta poprzez wysłanie wiadomości e-mail na adres naszego Inspektora Danych Osobowych (adres e-mail: rodo@masazplusdlafirm.pl) z adresu, którego zgoda dotyczy.</li>
                        <li>Mają Państwo również prawo wniesienia skargi do GIODO w wypadku uznania, iż przetwarzanie danych osobowych Pani/Pana dotyczących narusza przepisy ogólnego rozporządzenia o ochronie danych osobowych z dnia 27 kwietnia 2016 r.</li>
                        <li>Dalsze informacje znajdą Państwo w Polityce Prywatności.</li>
                    </ul>-->

<!--                    <h4>
                        <strong>
                            Jakie są podstawy prawne przetwarzania Państwa danych?
                        </strong>
                    </h4>
                    <ul>
                        <li>Masaż+ dla firm przetwarza Państwa dane na podstawie prawnej zgodnej z obowiązującymi przepisami.</li>
                        <li>Podstawą prawną przetwarzania danych w celu świadczenia usług dla Użytkowników Masaż+ dla firm jest umowa (określona Regulaminem). Masaż+ dla firm musi korzystać z danych Użytkowników w celu realizacji usług Serwisu Masaż+ dla firm. Działania te obejmują również rozwój serwisu, dokonywanie analiz w celu doskonalenia świadczonych usług oraz mechanizmów zapewniających bezpieczeństwo.</li>
                        <li>Podstawą prawną dla pomiarów statystycznych i marketingu własnego Masaż+ dla firm jest tzw. uzasadniony interes administratora zgodnie z art. 6 ust. 1 lit. F RODO.</li>
                        <li>Podstawą prawną przetwarzania danych w celach marketingowych podmiotów trzecich odbywa się na podstawie odrębnej dobrowolnej zgody.</li>
                    </ul>-->

                    <p>
                        Serwis wykorzystuje pliki cookies, czyli pliki tekstowe zapisywane na komputerze Użytkownika, identyfikujące go w sposób potrzebny do umożliwienia niektórych operacji. Ograniczenia stosowania plików cookies mogą wpłynąć na niektóre funkcjonalności dostępne na stronach internetowych Serwisu. Dowiedz się więcej w 
                        <a target="_blank" href="{{ URL::to('cookies-policy') }}">
                            @lang('cookies.cookies_policy')
                        </a>
                    </p>

            </div>
            <div class="col-1"></div>
        </div>
    </div>
        
    @if ($showBanner)
        @include('layouts.banner')
    @endif

@endsection