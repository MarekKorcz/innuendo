<style>
    #contact-message {
        padding: 2rem;
        margin: 3rem;
        border: 3px goldenrod dotted;
    }
</style>

<div id="contact-message">
    <h2>Wiadomość od: "{{$name}}" - "{{$email}}"</h2>

    <h3>Tytuł:</h3>
    <p>{{$topic}}</p>

    <h3>Treść:</h3>
    <p>{{$description}}</p>
</div>
