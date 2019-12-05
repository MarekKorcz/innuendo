@extends('layouts.app')
@section('content')

{!! Html::style('css/welcome.css') !!}

    <div class="container-fluid">
        <div class="row jumbotron">
            <div class="col-12 text-center">
                <h2 class="text-center" style="letter-spacing: 6px;">
                    REGULAMIN
                </h2>
                <p style="font-size: 24px;">
                    Serwisu internetowego
                    <strong>
                        „{{ config('app.name') }} {{ config('app.name_2nd_part') }}”
                    </strong>
                </p>
                <h6>
                    (obowiązuje od 13-12-2019)
                </h6>
            </div>
        </div>
    </div>
        
    <div class="container">
        <div class="row" style="padding-bottom: 1rem;">
            <div class="col-1"></div>
            <div id="first-paragraph" class="col-10">
                <p class="text-center">
                    <strong>§ 1</strong>
                    <br>
                    <strong>Słowniczek</strong>
                </p>
                <p>
                    Ilekroć poniżej używa się pojęcia:
                    <ol>
                        <li>Masaż+ – należy przez to rozumieć Marek Korcz ......................................</li>
                        <li>Serwis – należy przez to rozumieć serwis internetowy należący do
                            Masaż+, umożliwiający zakup masażu lub pakietów abonamentowych
                            masaży dla pracowników przedsiębiorstw, prowadzony pod adresem
                            <a href="{{route('welcome')}}">www.masazplusdlafirm.pl</a>.</li>
                        <li>Regulamin – należy przez to rozumieć niniejszy Regulamin, o którym
                            jednocześnie mowa w art. 8 ustawy z dnia 18 lipca 2002 r. o świadczeniu
                            usług drogą elektroniczną.</li>
                        <li>Umowa sprzedaży – należy przez to rozumieć umowę sprzedaży, o której
                            mowa w przepisach ustawy z dnia 23 kwietnia 1964 roku Kodeks cywilny.</li>
                        <li>Usługa – należy przez to rozumieć sprzedaż masaży lub pakietów
                            abonamentowych masaży prowadzoną przez Masaż+ w ramach
                            wykonywanej działalności gospodarczej oraz prowadzenie działalności
                            fizjoterapeutycznej polegającej na wykonywaniu masażu.</li>
                        <li>Użytkownik – należy przez to rozumieć osobę, która dokonała zakupu
                            usługi lub posiada zarejestrowane konto użytkownika serwisu.
                            Użytkownik ma dostęp do usługi z poziomu „szef” dla przedsiębiorcy
                            dokonującego zakupu i korzystającego z wykupionej usługi oraz z poziomu „pracownik” dla pracownika
                            korzystającego z wykupionej usługi.</li>
                    </ol>
                </p>
                <p class="text-center">
                    <strong>§ 2</strong>
                    <br>
                    <strong>Postanowienia ogólne</strong>
                </p>
                <p>
                    <ol>
                        <li>Regulamin określa zasady korzystania z serwisu, świadczenia usług przez
                            Masaż+, zawierania umów sprzedaży oraz wskazuje na prawa i obowiązki
                            Użytkownika.</li>
                        <li>Regulamin jest w każdej chwili dostępny pod adresem
                            <a href="{{route('regulations')}}">www.masazplusdlafirm.pl/regulations</a>.</li>
                        <li>Stroną umowy sprzedaży mogą być osoby, które ukończyły 18 lat.</li>
                        <li>W celu korzystania z usługi Użytkownik zobowiązany jest we własnym
                            zakresie uzyskać dostęp do stanowiska komputerowego lub urządzenia
                            końcowego z dostępem do Internetu.</li>
                    </ol>
                </p>
                <p class="text-center">
                    <strong>§ 3</strong>
                    <br>
                    <strong>Procedura zawarcia umowy</strong>
                </p>
                <p>
                    <ol>
                        <li>Użytkownik dokonuje zakupu usługi poprzez rejestrację, wybór jednej lub
                            wielu opcji masażu lub ich pakietów dostępnych w serwisie oraz
                            potwierdzenie zakupu.</li>
                        <li>Rejestracja jest możliwa także poprzez jej zlecenie administratorowi
                            serwisu lub przy pomocy kodu rejestracyjnego otrzymanego od
                            administratora lub osób trzecich w ramach akcji promocyjnych
                            prowadzonych przez Masaż+.</li>
                        <li>Warunkiem zawarcia umowy sprzedaży jest akceptacja Regulaminu oraz
                            Polityki Prywatności.</li>
                        <li>Po potwierdzeniu wyboru usługi, Użytkownik otrzymuje kody
                            rejestracyjne dla poszczególnych osób, dla których ma zostać wykonany
                            masaż. Kody rejestracyjne są przydzielane z poziomu „szef”. Pracownik
                            może się zarejestrować z użyciem przekazanego mu kodu.</li>
                        <li>
                            Dla zawarcia umowy sprzedaży, Użytkownik zobowiązany jest podać
                            swoje dane:
                            <ul style="list-style-type: circle;">
                                <li>Imie;</li>
                                <li>Nazwisko;</li>
                                <li>Numer telefonu;</li>
                                <li>Adres e-mail;</li>
                                <li>Nazwa firmy;</li>
                                <li>Adres lokalizacji w których mają być wykonywane usługi;</li>
                                <li>Dane kontaktowe firmy (numer telefonu, adres e-mail);</li>
                                <li>Dane niezbędne do poprawnego wystawienia faktury;</li>
                            </ul>
                        </li>
                        <li>Z momentem zawarcia umowy sprzedaży Użytkownik uzyskuje dostęp do
                            panelu użytkownika poziomu „szef”, który umożliwia przydzielanie
                            pracowników do pakietów, generowanie kodów rejestracyjnych i ich
                            przydzielanie pracownikom, korzystanie z kalendarza, śledzenie
                            przebiegu rezerwacji, podgląd stanu płatności.</li>
                        <li>Zakup usługi następuje jednorazowo lub na określone okresy
                            abonamentowe i nie jest automatycznie przedłużany. W celu
                            kontynuowania masaży, Użytkownik powinien dokonać ich zakupu na
                            kolejny wybrany przez siebie okres.</li>
                    </ol>
                </p>
                <p class="text-center">
                    <strong>§ 4</strong>
                    <br>
                    <strong>Zasady korzystania z usługi</strong>
                </p>
                <p>
                    <ol>
                        <li>Zamówienia na zakupione masaże realizowane są poprzez wybór i
                            umawianie ustalonych terminów w kalendarzu. Masaż jest wykonywany
                            dla pracownika, który wykorzysta kod rejestracyjny.</li>
                        <li>Płatności rozliczane są na koniec miesiąca kalendarzowego, chyba że z
                            Użytkownikiem ustalony został indywidualny system rozliczeń. Po
                            zakończeniu okresu rozliczeniowego Użytkownik otrzyma drogą
                            elektroniczną fakturę i zobowiązany jest do jej opłacenia w sposób i w
                            terminie weń wskazanych.</li>
                        <li>Płatność naliczana jest według ceny wykupionego masażu lub pakietu
                            masaży, niezależnie od liczby faktycznie wykorzystanych przez
                            Użytkownika masaży, z zastrzeżeniem ust. 4.</li>
                        <li>Jeżeli masaż nie zostanie zrealizowany z winy Masaż+, płatność za okres
                            abonamentowy pomniejszana jest proporcjonalnie do wartości
                            niezrealizowanych masaży.</li>
                        <li>Masaż wykonywany jest w siedzibie Użytkownika. Przedstawiciele
                            Masaż+ posiadają wszelkie niezbędne sprzęty umożliwiające
                            profesjonalne wykonanie masażu.</li>
                        <li>
                            W celu zapewnienia bezpieczeństwa świadczenia usług oraz w celu
                            ochrony zgromadzonych danych, Masaż+ podejmuje środki techniczne i
                            organizacyjne odpowiednie do stopnia zagrożenia bezpieczeństwa
                            serwisu, w szczególności środki służące ochronie zamieszczonych w
                            zasobach serwisu, materiałów oraz przechowywanych danych przed
                            pozyskaniem i modyfikacją przez osoby nieuprawnione.
                            <br>
                            Użytkownik winien jednak mieć świadomość, że korzystanie z usług
                            świadczonych przez sieci informatyczne zawsze wiąże się z ryzykiem,
                            m.in. poniesienia szkody przez działanie złośliwego oprogramowania,
                            ataków socjotechnicznych i hakerskich. Masaż+ rekomenduje, by
                            Użytkownik zaopatrzył urządzenie, które wykorzystuje podłączając się do
                            Internetu, w program antywirusowy i stale go aktualizował.
                        </li>
                        <li>Masaż+ nie ponosi odpowiedzialności za okresową niedostępność
                            serwisu, wywołaną przez czynniki obiektywne, w tym siłę wyższą, awarie
                            techniczne czy też związaną z czynnikami leżącymi po stronie dostawców
                            usług informatycznych. Masaż+ nie ponosi odpowiedzialności za straty
                            lub utracone korzyści spowodowane wystąpieniem awarii technicznej.
                            Masaż+ zastrzega sobie prawo do celowego, przejściowego zaprzestania
                            świadczenia usługi serwisu ze względu na czynności konserwacyjne lub
                            związane z modyfikacją serwisu lub do innych koniecznych przerw
                            technicznych. Masaż+ dokłada wszelkich starań ku temu, by dostęp do
                            serwisu był trwały i niezakłócony.</li>
                        <li>Dla prawidłowego korzystania z seriwsu, Użytkownik powinien zadbać o
                            aktualną wersję posiadanego oprogramowania. Masaż+ zaleca
                            stosowanie przeglądarek Firefox i Chrome.</li>
                    </ol>
                </p>
                <p class="text-center">
                    <strong>§ 5</strong>
                    <br>
                    <strong>Odstąpienie od umowy</strong>
                </p>
                <p>
                    Oferta Masaż+ kierowana jest do przedsiębiorców. Stąd wyłączone jest
                    przysługujące konsumentom prawo do odstąpienia od umowy zawartej poza
                    lokalem przedsiębiorstwa.
                </p>
                <p class="text-center">
                    <strong>§ 6</strong>
                    <br>
                    <strong>Reklamacje</strong>
                </p>
                <p>
                    <ol>
                        <li>
                            Reklamacje należy kierować mailowo na adres e-mail: 
                            <ul>
                                <li>masazplus@masazplusdlafirm.pl</li>
                            </ul>
                            lub pocztą na adres korespondencyjny:
                            <ul>
                                <li>Marek Korcz, ul. Domaniewska 17/19/109, 02-672 Warszawa;</li>
                            </ul>
                        </li>
                        <li>Masaż+ zobowiązuje się do rozpatrzenia reklamacji w terminie do 30
                            dni.</li>
                        <li>Osoby świadczące usługi jako Masaż+ świadczą profesjonalne usługi
                            mające na celu poprawę samopoczucia, kondycji i efektywności w pracy,
                            w tym celu dokładając wszelkich starań, aby efekt był jak najpełniej
                            odczuwalny. Indywidualne efekty zależą jednak od wielu czynników, stąd
                            rezultaty i poprawa samopoczucia mogą u poszczególnych osób
                            występować w różnym natężeniu i po różnym czasie.</li>
                        <li>Masaż+ podejmuje działania w celu zapewnienia w pełni poprawnego
                            działania serwisu, w takim zakresie, jaki wynika z aktualnej wiedzy
                            technicznej i zobowiązuje się usunąć bez zbędnej zwłoki, w rozsądnym
                            terminie wszelkie nieprawidłowości zgłoszone przez Użytkowników.</li>
                        <li>Użytkownik może zgłaszać wszelkie nieprawidłowości oraz przerwy w
                            funkcjonowaniu serwisu oraz w dostępie na adres e-mail: 
                            <ul>
                                <li>masazplus@masazplusdlafirm.pl</li>
                            </ul>
                            lub pocztą na adres korespondencyjny:
                            <ul>
                                <li>Marek Korcz, ul. Domaniewska 17/19/109, 02-672 Warszawa;</li>
                            </ul>
                        </li>
                    </ol>
                </p>
                <p class="text-center">
                    <strong>§ 7</strong>
                    <br>
                    <strong>Cennik</strong>
                </p>
                <div class="row text-center">
                    <div class="col-1"></div>
                    <div class="col-10">
                        <span>Masaż klasyczny (15 min) - 40zł / osoba</span>
                        <br>
                        <span>Masaż klasyczny (30 min) - 70zł / osoba</span>
                    </div>
                    <div class="col-1"></div>
                </div>
                <p>
                    Pakiety abonamentowe:
                    <ul style="list-style-type: circle;">
                        <li>
                            <strong>1 x masaż klasyczny (15 min)</strong> w miesiącu (łączny czas trwania - 12
                            miesięcy) - <strong>38 zł (zniżka 5%)</strong> / osoba
                        </li>
                        <li>
                            <strong>2 x masaż klasyczny (15 min)</strong> w miesiącu (łączny czas trwania - 12
                            miesięcy) - <strong>72 zł (zniżka 10%)</strong> / osoba
                        </li>
                        <li>
                            <strong>3 x masaż klasyczny (15 min)</strong> w miesiącu (łączny czas trwania - 12
                            miesięcy) - <strong>102 zł (zniżka 15%)</strong> / osoba
                        </li>
                        <li>
                            <strong>4 x masaż klasyczny (15 min)</strong> w miesiącu (łączny czas trwania - 12
                            miesięcy) - <strong>128 zł (zniżka 20%)</strong> / osoba
                        </li>
                    </ul>
                    <ul style="list-style-type: circle;">
                        <li>
                            <strong>1 x masaż klasyczny (30 min)</strong> w miesiącu (łączny czas trwania - 12
                            miesięcy) - <strong>66.5 zł (zniżka 5%)</strong> / osoba
                        </li>
                        <li>
                            <strong>2 x masaż klasyczny (30 min)</strong> w miesiącu (łączny czas trwania - 12
                            miesięcy) - <strong>126 zł (zniżka 10%)</strong> / osoba
                        </li>
                        <li>
                            <strong>3 x masaż klasyczny (30 min)</strong> w miesiącu (łączny czas trwania - 12
                            miesięcy) - <strong>178,5 zł (zniżka 15%)</strong> / osoba
                        </li>
                        <li>
                            <strong>4 x masaż klasyczny (30 min)</strong> w miesiącu (łączny czas trwania - 12
                            miesięcy) - <strong>224 zł (zniżka 20%)</strong> / osoba
                        </li>
                    </ul>
                    Progi wzrostu zniżki abonamentowej (<strong>zniżka</strong> w pakietach
                    abonamentowych <strong>może wzrosnąć</strong> w zależności od tego <strong>ile osób w
                    danym miesiącu skorzysta z masażu</strong>):
                    <ul style="list-style-type: circle;">
                        <li>
                            wzrost zniżki abonamentowej o <strong>5%</strong> gdy osób korzystających jest <strong>więcej niż 5</strong>
                        </li>
                        <li>
                            wzrost zniżki abonamentowej o <strong>15%</strong> gdy osób korzystających jest <strong>więcej niż 25</strong>
                        </li>
                        <li>
                            wzrost zniżki abonamentowej o <strong>30%</strong> gdy osób korzystających jest <strong>więcej niż 50</strong>
                        </li>
                        <li>
                            wzrost zniżki abonamentowej o <strong>50%</strong> gdy osób korzystających jest <strong>więcej niż 100</strong>
                        </li>
                    </ul>
                </p>
                <p class="text-center">
                    <strong>§ 8</strong>
                    <br>
                    <strong>Postanowienia końcowe</strong>
                </p>
                <p>
                    <ol>
                        <li>Regulamin wchodzi w życie 13 grudnia 2019 roku.</li>
                        <li>W razie zmiany koncepcji prowadzenia serwisu, udostępniania szkoleń
                            lub wystąpienia innych ważnych przyczyn Masaż+ zastrzega sobie prawo
                            do jednostronnej zmiany Regulaminu. Zmiany Regulaminu wchodzą w
                            życie po upływie 3 dni od umieszczenia zmienionego Regulaminu w
                            serwisie. O zmianie Regulaminu Masaż+ może dodatkowo zawiadomić
                            Użytkownika drogą elektroniczną na adres zapisany w koncie
                            Użytkownika. Zmiana Regulaminu nie wpływa na nabyte prawa
                            Użytkownika.</li>
                        <li>Masaż+ dąży do polubownego załatwienia wszelkich ewentualnych
                            sporów wszczętych przez Użytkownika i zachęca do zgłaszania uwag,
                            zobowiązując się do ich merytorycznego rozpatrzenia i, w razie
                            możliwości, uwzględnienia.</li>
                        <li>W sprawach nieuregulowanych w Regulaminie zastosowanie mają
                            przepisy ustawy o świadczeniu usług drogą elektroniczną i Kodeksu
                            cywilnego.</li>
                    </ol>
                </p>
            </div>
            <div class="col-1"></div>
        </div>
    </div>

    @if ($showBanner)
        @include('layouts.banner')
    @endif

@endsection