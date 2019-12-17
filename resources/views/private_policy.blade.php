@extends('layouts.app')
@section('content')

{!! Html::style('css/welcome.css') !!}

    <div class="container-fluid">
        <div class="row jumbotron">
            <div class="col-12 text-center">
                <h2 class="text-center" style="letter-spacing: 6px;">
                    POLITYKA PRYWATNOŚCI
                </h2>
                <p style="font-size: 24px;">
                    Serwisu internetowego
                    <strong>
                        „{{ config('app.name') }} {{ config('app.name_2nd_part') }}”
                    </strong>
                </p>
<!--                <h6>
                    (obowiązuje od 13-12-2019)
                </h6>-->
            </div>
        </div>
    </div>
        
    <div class="container">
        <div class="row" style="padding-bottom: 1rem;">
            <div class="col-1"></div>
            <div id="first-paragraph" class="col-10">
                <p class="text-center">
                    <strong>
                        § 1
                    </strong>
                </p>
                <p>
                    <strong>
                        Informacja dla Użytkownika zgodnie z wymogami Rozporządzenia Parlamentu
                        Europejskiego i Rady (UE) 2016/679 z dnia 27 kwietnia 2016 r. w sprawie ochrony osób
                        fizycznych w związku z przetwarzaniem danych osobowych i w sprawie swobodnego
                        przepływu takich danych oraz uchylenia dyrektywy 95/46/WE (ogólne rozporządzenie o
                        ochronie danych; dalej: RODO)
                    </strong>
                </p>
                <p>
                    <strong>
                        Kto jest administratorem Twoich danych osobowych?
                    </strong>
                    <br>
                    Administratorem, czyli podmiotem decydującym o tym, jak będą wykorzystywane Twoje
                    dane osobowe, jest Marek Korcz ................... w dalszej części dokumentu zwanym „Masaż+”.

                </p>
                <p>
                    <strong>
                        Jak się z nami skontaktować, żeby uzyskać więcej informacji o przetwarzaniu danych osobowych?
                    </strong>
                    <br>
                    Przedstawiciele Masaż+ są do Twojej dyspozycji pod następującymi danymi kontaktowymi:
                    <ul style="list-style-type: circle;">
                        <li>adres korespondencyjny: Marek Korcz, ul. Domaniewska 17/19/109, 02-672 Warszawa;</li>
                        <li>adres e-mail: masazplus@masazplusdlafirm.pl</li>
                    </ul>
                </p>
                <p>
                    <strong>
                        Jak i w jakim celu zostały pozyskane i są przetwarzane Twoje dane osobowe?
                    </strong>
                    <br>
                    Podanie przez Ciebie danych następuje dobrowolnie celem prawidłowego wykonania zawartej z nami umowy.
                    <br>
                    Przetwarzanie Twoich danych osobowych jest niezbędne do wykonania zawartej z Tobą
                    umowy. Co więcej, obowiązujące przepisy związane z prawem podatkowym i
                    rachunkowością wymagają od administratora przetwarzania tych danych. Kolejną podstawą
                    przetwarzania jest prawnie uzasadniony interes Masaż+, którym jest:
                    <ul style="list-style-type: circle;">
                        <li>kontaktowanie się w celach związanych z realizacją umowy;</li>
                        <li>zapewnienie obsługi płatności;</li>
                        <li>prawidłowe administrowanie serwisem internetowym;</li>
                        <li>windykacja należności; prowadzenie postępowań sądowych, arbitrażowych i mediacyjnych;</li>
                        <li>prowadzenie działań marketingowych i reklama;</li>
                        <li>prowadzenie analiz statystycznych;</li>
                        <li>przechowywanie danych dla celów archiwizacyjnych, oraz zapewnienie rozliczalności,
                            (wykazania spełnienia obowiązków wynikających z przepisów prawa).</li>
                    </ul>
                </p>
                <p>
                    <strong>
                        Jakie dane są niezbędne dla wykonania umowy?
                    </strong>
                    <br>
                    Gromadzone przez nas dane ograniczają się do wymogów prawidłowego wykonania umowy,
                    w celu zapewnienia najwyższej jakości usług. Dla zawarcia umowy wymagamy podania:
                    <br>
                    <ul style="list-style-type: circle;">
                        <li>Imie;</li>
                        <li>Nazwisko;</li>
                        <li>Numer telefonu;</li>
                        <li>Adres e-mail;</li>
                    </ul>
                    Dodatkowo w przypadku osoby decyzyjnej, odpowiedzialnej za kwestie związane z finansowaniem usług wykonywanych przez <a href="{{route('welcome')}}">www.masazplusdlafirm.pl</a>:
                    <ul style="list-style-type: circle;">
                        <li>Nazwa firmy;</li>
                        <li>Adres lokalizacji w których mają być wykonywane usługi;</li>
                        <li>Dane kontaktowe firmy (numer telefonu, adres e-mail);</li>
                        <li>Dane niezbędne do poprawnego wystawienia faktury;</li>
                    </ul>
                    Pozostałe dane, które uzupełnisz na swoim koncie użytkownika mają charakter dobrowolny,
                    pomogą Ci lepiej i pełniej korzystać z usługi, a nam podnosić jej jakość i dostosować ją do
                    Twoich potrzeb.
                </p>    
                <p>
                    Jeżeli wymagają tego przepisy prawa, możemy wymagać od Ciebie ewentualnie podania
                    innych danych niezbędnych np. ze względów rachunkowych lub podatkowych.
                </p>
                <p>
                    W związku z prawidłowym administrowaniem naszym serwisem internetowym
                    przetwarzamy takie dane jak Twój adres IP, datę i czas serwera, informacje o przeglądarce
                    internetowej, informacje o systemie operacyjnym, a także tzw. pliki cookies, o których
                    przeczytasz w § 2.
                </p>
                <p>
                    <strong>
                        Jakie są Twoje prawa w zakresie przetwarzania danych osobowych?
                    </strong>
                    <br>
                    Gwarantujemy prawo dostępu, sprostowania oraz usunięcia danych, ograniczenia ich
                    przetwarzania, prawo do ich przenoszenia, niepodlegania zautomatyzowanemu
                    podejmowaniu decyzji, w tym profilowaniu, a także prawo do wyrażenia sprzeciwu wobec
                    przetwarzania danych osobowych.
                    <br>
                    Z uprawnień tych można skorzystać, gdy:
                    <ul style="list-style-type: circle;">
                        <li>w odniesieniu do żądania sprostowania danych: zauważysz, że dane są
                            nieprawidłowe lub niekompletne;</li>
                        <li>w odniesieniu do żądania usunięcia danych: dane nie będą już niezbędne do celów,
                            dla których zostały zebrane przez Masaż+; cofnięcie zgody na przetwarzanie danych;
                            zgłoszenie sprzeciwu wobec przetwarzania danych; dane będą przetwarzane
                            niezgodnie z prawem; dane powinny być usunięte w celu wywiązania się z obowiązku
                            wynikającego z przepisu prawa;</li>
                        <li>w odniesieniu do żądania ograniczenia przetwarzania danych: dane są nieprawidłowe
                            – można żądać ograniczenia przetwarzania danych na okres pozwalający ustalić
                            prawidłowość tych danych; dane będą przetwarzane niezgodnie z prawem, ale nie
                            będziesz żądał, aby zostały usunięte; dane nie będą już potrzebne, ale mogą być Ci
                            potrzebne do obrony lub dochodzenia roszczeń; wniesiony zostanie sprzeciw wobec
                            przetwarzania danych – do czasu ustalenia, czy prawnie uzasadnione podstawy po
                            naszej stronie są nadrzędne wobec podstawy sprzeciwu;</li>
                        <li>w odniesieniu do żądania przeniesienia danych: przetwarzanie danych odbywa się na
                            podstawie Twojej zgody lub umowy zawartej z Tobą oraz, gdy przetwarzanie to
                            odbywałoby się w sposób automatyczny.</li>
                    </ul>
                </p>
                <p>
                    Masz prawo wnieść skargę w związku z przetwarzaniem przez nas danych osobowych do
                    organu nadzorczego, którym jest Generalny Inspektor Ochrony Danych Osobowych (adres:
                    Generalny Inspektor Ochrony Danych Osobowych, ul. Stawki 2, 00-193 Warszawa).
                </p>
                <p>
                    <strong>
                        W jakich sytuacjach można się sprzeciwić wobec przetwarzania danych?
                    </strong>
                    <br>
                    Prawo wniesienia sprzeciwu wobec przetwarzania danych osobowych, powstaje gdy:
                    <br>
                    <ul style="list-style-type: circle;">
                        <li>przetwarzanie danych osobowych odbywa się na podstawie prawnie
                            uzasadnionego interesu lub dla celów statystycznych, a sprzeciw jest uzasadniony
                            przez szczególną sytuację,</li>
                        <li>dane osobowe przetwarzane są na potrzeby marketingu bezpośredniego, w tym
                            są profilowane dla tego celu.</li>
                    </ul>
                </p>
                <p>
                    <strong>
                        Komu udostępniane są Twoje dane osobowe?
                    </strong>
                    <br>
                    Dane udostępniane są podmiotom współpracującym przy realizacji transakcji a także
                    podmiotom zajmującym się finansową obsługą transakcji, księgowością oraz
                    rachunkowością, zaś w wypadku powstania sporu podmiotom profesjonalnie świadczącym
                    pomoc prawną. W szczególnych wypadkach, dane osobowe mogą zostać przekazane
                    organom publicznym walczącym z oszustwami i nadużyciami.
                </p>
                <p>
                    <strong>
                        Jak długo przechowywane są Twoje dane osobowe?
                    </strong>
                    <br>
                    Przechowujemy dane osobowe przez czas obowiązywania zawartej z Tobą umowy, w całym
                    okresie w którym posiadasz aktywne konto Użytkownika, a także po zakończeniu umowy w
                    celach:
                    <br>
                    <ul style="list-style-type: circle;">
                        <li>dochodzenia roszczeń w związku z wykonywaniem umowy,</li>
                        <li>wykonania obowiązków wynikających z przepisów prawa, w tym w szczególności
                            podatkowych i rachunkowych (5 lat),</li>
                        <li>zapobiegania nadużyciom i oszustwom,</li>
                        <li>statystycznych i archiwizacyjnych,</li>
                        <li>maksymalnie przez okres 6 lat od dnia zakończenia wykonania umowy
                            (przedawnienie roszczeń cywilnoprawnych).</li>
                    </ul>
                    W celu rozliczalności tj. udowodnienia przestrzegania przepisów dotyczących
                    przetwarzania danych osobowych będziemy przechowywać dane przez okres, w którym
                    jesteśmy zobowiązani jest do zachowania danych lub dokumentów je zawierających dla
                    udokumentowania spełnienia wymagań prawnych i umożliwienia kontroli ich spełnienia
                    przez organy publiczne.
                </p>
                <p>
                    <strong>
                        Czy przekazujemy dane do państw spoza Europejskiego Obszaru Gospodarczego?
                    </strong>
                    <br>
                    Nie.
                </p> 
                <p>
                    <strong>
                        Czy przetwarzamy dane osobowe automatycznie (w tym poprzez profilowanie) w sposób
                        wpływający na Państwa prawa?
                    </strong>
                    <br>
                    Nie. Nie dokonujemy zautomatyzowanego podejmowania decyzji, w tym w oparciu o
                    profilowanie. Twoje konto użytkownika nie podlega ocenie przez system informatyczny.
                </p>
                <p class="text-center">
                    <strong>§ 2</strong>
                    <br>
                    <strong>Polityka Cookies</strong>
                </p>
                <p>
                    Masaż+ w swoim serwisie internetowym <a href="{{route('welcome')}}">www.masazplusdlafirm.pl</a>, podobnie jak inne
                    podmioty, wykorzystuje tzw. cookies (ciasteczka), czyli krótkie informacje tekstowe,
                    zapisywane na komputerze, telefonie, tablecie, czy też innym urządzeniu użytkownika. Mogą
                    być one odczytywane przez nasz system, a także przez systemy należące do innych
                    podmiotów, z których usług korzystamy (np. Facebooka, Google’a).
                    <br>
                    Cookies spełniają bardzo wiele przydanych funkcji, bez których korzystanie z naszych usług
                    nie byłoby pełne ani sprawne. Staraliśmy się je opisać poniżej, ale jeżeli informacje te
                    okazałyby się niewystarczające, napisz do nas, a odpowiemy na wszystkie wątpliwości.
                    <br>
                    <ul style="list-style-type: circle;">
                        <li>zapewnianie bezpieczeństwa — pliki cookies są wykorzystywane w celu
                            uwierzytelniania użytkowników oraz zapobiegania nieupoważnionemu korzystaniu z
                            konta użytkownika.</li>
                        <li>wpływ na procesy i wydajność korzystania ze strony internetowej — pliki cookies są
                            wykorzystywane do tego, aby serwis sprawnie działał i aby można było korzystać z
                            funkcji w nim dostępnych, co jest możliwe między innymi dzięki zapamiętywaniu
                            ustawień pomiędzy kolejnymi odwiedzinami serwisu.</li>
                        <li>stan sesji — w plikach cookies często są zapisywane informacje o tym, jak
                            odwiedzający korzystają z serwisu, np. które podstrony lub oferty najczęściej
                            wyświetlają. Umożliwiają również identyfikację błędów wyświetlanych w niektórych
                            funkcjonalnościach. Pliki cookies służące do zapisywania tzw. „stanu sesji” i pomagają
                            ulepszać usługi i zwiększać komfort korzystania z serwisu.</li>
                        <li>utrzymanie stanu sesji — jeżeli użytkownik loguje się do swojego konta, to pliki
                            cookies umożliwiają podtrzymanie sesji. Oznacza to, że nie trzeba każdorazowo
                            podawać ponownie loginu i hasła, co sprzyja komfortowi korzystania z usługi.</li>
                        <li>tworzenie statystyk — pliki cookies są wykorzystywane do tego, aby przeanalizować,
                            w jaki sposób użytkownicy korzystają z serwisu (jak wielu wchodzi na stronę, jak
                            długo na niej pozostają, jakie treści cieszą się największym zainteresowaniem itp.).
                            Dzięki temu możemy stale ulepszać nasz serwis i dostosowywać jego działanie do
                            preferencji użytkowników. W celu śledzenia aktywności i tworzenia statystyk
                            możemy wykorzystywać narzędzia Google’a, takie jak Google Analytics; oprócz
                            raportowania statystyk użytkowania witryny pikselowy Google Analytics może
                            również służyć, razem z niektórymi opisanymi powyżej plikami cookies, do pomocy w
                            wyświetlaniu użytkownikowi bardziej trafnych treści w usługach Google (np. w
                            wyszukiwarce Google)</li>
                        <li>korzystanie z funkcji społecznościowych — serwis może korzystać z Facebook Pixel,
                            czyli narzędzia które umożliwia polubienie naszego Fanpage’a w tym serwisie podczas
                            korzystania z witryny. Również by to było możliwe, musimy korzystać z plików
                            cookies dostarczanych przez Facebooka.</li>
                    </ul>
                </p>
                <p>
                    Twoja przeglądarka internetowa domyślnie dopuszcza wykorzystywanie cookies w Twoim
                    urządzeniu, dlatego przy pierwszej wizycie prosimy o akceptację naszej Polityki Prywatności,
                    której nieodłącznym elementem jest opisana tutaj Polityka Cookies. Taka akceptacja oznacza
                    wyrażenie zgody na użycie cookies w sposób i dla celów wyżej opisanych. Jeżeli nie życzysz
                    sobie używania cookies i nie wyrażasz takiej zgody, a jednak chcesz korzystać z naszych
                    usług, zmień ustawienia w swojej przeglądarce internetowej. Możesz całkowicie blokować
                    automatyczną obsługę plików cookies lub żądać powiadomienia o każdorazowym
                    zamieszczeniu cookies w urządzeniu. Ustawienia można zmienić w dowolnej chwili. Musisz
                    jednak pamiętać, że wyłączenie lub ograniczenie obsługi plików cookies może spowodować
                    dość poważne trudności w korzystaniu z naszych usług i wpłynąć na niektóre funkcjonalności
                    naszego serwisu.
                    <br><br>
                    W ramach naszego serwisu stosowane są dwa zasadnicze rodzaje plików cookies: „sesyjne”
                    (session cookies) oraz „stałe” (persistent cookies). Cookies „sesyjne” są plikami
                    tymczasowymi, które przechowywane są w urządzeniu końcowym użytkownika do czasu
                    wylogowania, opuszczenia strony lub wyłączenia oprogramowania (przeglądarki
                    internetowej). „Stałe” pliki cookies przechowywane są w urządzeniu końcowym użytkownika
                    przez czas określony w parametrach plików cookies lub do czasu ich usunięcia przez
                    użytkownika.
                </p>
            </div>
            <div class="col-1"></div>
        </div>
    </div>

    @if ($showBanner)
        @include('layouts.banner')
    @endif

@endsection