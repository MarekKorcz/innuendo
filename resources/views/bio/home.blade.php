@extends('bio.app')
@section('content')

{!! Html::style('css/bio/home.css') !!}
{!! Html::script('js/bio/home.js') !!}

    <div class="header-image-section">
        <div class="header-image-section-text container">
            <div class="border">
                <p>
                    BIOENERGOTERAPIA <br>
                    PATRYCJA DOLATA
                </p>
                <p style="font-size: 1.5rem;">Naturalne metody leczenia</p>
            </div>
        </div>
    </div>

    <div class="first-section-header">
        <p>MOŻLIWOŚCI LECZENIA</p>
        <p style="font-size: 1.6rem;">Wyjątkowe, indywidualne podejście</p>
    </div>


    <div class="three-column-section">
        <div class="row">
            <div class="col-sm-12 col-md-6 col-lg-4">
                <div class="box">
                    <div class="box-image">
                        <img class="first-image-in-box" src="/img/woman-lying.jpg">
                    </div>
                    <div class="box-text">
                        <p>
                            SESJA RÓWNOWAŻENIA <br> 
                            CZAKR
                        </p>
                        <p style="color: black;">Starożytna nauka</p>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-md-6 col-lg-4">
                <div class="box">
                    <div class="box-image">
                        <img class="first-image-in-box" src="/img/woman-praying.jpg">
                    </div>
                    <div class="box-text">
                        <p>TRENING RELAKSACYJNY</p>
                        <p style="color: black; padding-top: 2rem;">Naturalne uzdrawianie</p>
                    </div>
                </div>        
            </div>
            <div class="col-sm-12 col-md-6 col-lg-4">
                <div class="box">
                    <div class="box-image">
                        <img class="first-image-in-box" src="/img/head-massage.jpg">
                    </div>
                    <div class="box-text">
                        <p>BIOENERGOTERAPIA</p>
                        <p style="color: black; padding-top: 2rem;">Zrównoważony poziom chi</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="quote-section">
        <div class="row">
            <div class="col-12">
                <p style="font-size: 1.8rem; color: #95a2a2;">„Każdy człowiek jest autorem swojego zdrowia lub choroby”.</p>
                <p>
                    <cite>
                        Budda
                    </cite>
                </p>
            </div>
        </div>
    </div>

    <div class="contact-section">
        <div class="row">
            <div class="col-12">
                <div class="contact-section-header">
                    <p>SKONTAKTUJ SIĘ ZE MNĄ</p>
                    <p style="font-size: 1.3rem;">(+48) 518475207</p>
                </div>
                <div class="row">
                    <div class="col-1"></div>
                    <div class="col-10">
                        <div class="contact-section-form">
                            {{ Form::open(['id' => 'contact-message', 'action' => ['HomeController@bioContactMessage'], 'method' => 'POST']) }}

                                <div class="row">
                                    <div class="col-12 col-sm-6">
                                        <div class="form-group">
                                            <input type="text" name="name" placeholder="Imię" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6">
                                        <div class="form-group">
                                            <input type="text" name="email" placeholder="E-mail" autocomplete="off">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <input type="text" name="topic" placeholder="Temat" autocomplete="off">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <textarea name="description" placeholder="Tutaj wpisz swoją wiadomość..." autocomplete="off"></textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12">
                                        <button type="submit" form="contact-message" value="submit">Wyślij</button>
                                    </div>
                                </div>

                            {{ Form::close() }} 
                        </div>
                    </div>
                    <div class="col-1"></div>
                </div>
            </div>
        </div>
    </div>

    <div id="map">
                <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d2442.396527698532!2d20.99462861579763!3d52.254344079764785!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1spl!2spl!4v1587961912509!5m2!1spl!2spl" width="100%" height="450" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>

    </div>

    <div id="blog-links">
        <div class="row">
            <div class="col-6">
                <div class="blog-post">
                    Miejsce na link do postu na blogu
                </div>
            </div>
            <div class="col-6">
                <div class="blog-post">
                    Miejsce na link do postu na blogu
                </div>
            </div>
        </div>
    </div>

@endsection