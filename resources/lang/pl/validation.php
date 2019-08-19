<?php

return [
    
    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => ':attribute musi być zaakceptowany.',
    'active_url' => ':attribute nie jest poprawnym URL.',
    'after' => ':attribute musi być datą po :date.',
    'after_or_equal' => ':attribute musi być datą większą lub równą :date.',
    'alpha' => ':attribute może zawierać tylko litery.',
    'alpha_dash' => ':attribute może zawierać tylko litery, cyfry, myślniki i podkreślniki.',
    'alpha_num' => ':attribute może zawierać tylko litery i cyfry.',
    'array' => 'The :attribute must be an array.',
    'before' => ':attribute musi być datą przed :date.',
    'before_or_equal' => ':attribute musi być datą mniejszą lub równą :date.',
    'between' => [
        'numeric' => ':attribute musi być pomiędzy :min i :max.',
        'file' => ':attribute musi być pomiędzy :min i :max kilobajtów.',
        'array' => 'The :attribute must have between :min and :max items.',
        'string' => ':attribute musi być pomiędzy :min i :max literami.',
    ],
    'boolean' => 'Pole :attribute musi być prawdą lub fałszem.',
    'confirmed' => 'Potwierdzenie :attribute nie pasuję.',
    'date' => ':attribute nie jest poprawną datą.',
    'date_equals' => ':attribute musi być datą równą :date.',
    'date_format' => ':attribute nie pasuję do formatu :format.',
    'different' => ':attribute i :other muszą być rózne.',
    'digits' => ':attribute musi być :digits liczbowy.',
    'digits_between' => ':attribute musi być pomiędzy :min i :max liczbami.',
    'dimensions' => ':attribute ma nieprawidłowe wymiary obrazu.',
    'distinct' => 'Polę :attribute ma zduplikowaną wartość.',
    'email' => ':attribute musi być poprawnym adresem e-mail.',
    'exists' => 'Wybrany :attribute jest nieważny.',
    'file' => ':attribute musi być plikiem.',
    'filled' => 'Pole :attribute musi mieć wartość.',
    'gt' => [
        'numeric' => ':attribute musi być większe niż :value.',
        'file' => ':attribute musi być większe niż :value kilobajtów.',
        'string' => ':attribute musi być dłuższy niż :value liter.',
        'array' => ':attribute musi mieć więcej niż :value element.',
    ],
    'gte' => [
        'numeric' => ':attribute musi być większy niż lub równy :value.',
        'file' => ':attribute musi być większy niż lub równy :value kilobajtów.',
        'string' => ':attribute musi być dłuższy niż lub równy :value liter.',
        'array' => ':attribute musi mieć :value elementów lub więcej.',
    ],
    'image' => ':attribute musi być obrazem.',
    'in' => 'Wybrany :attribute jest nieważny.',
    'in_array' => 'Pole :attribute nie istnieje w :other.',
    'integer' => ':attribute musi być liczbą całkowitą.',
    'ip' => ':attribute musi być poprawnym adresem IP.',
    
    'ipv4' => ':attribute musi być poprawnym adresem IPv4.',
    'ipv6' => ':attribute musi być poprawnym adresem IPv6.',
    'json' => ':attribute musi być w formacie JSON.',
    'lt' => [
        'numeric' => ':attribute musi być mniejszy niż :value.',
        'file' => ':attribute musi być mniejszy niż :value kilobajtów.',
        'string' => ':attribute musi być krótszy niż :value znaków.',
        'array' => ':attribute musi mieć mniej niż :value element..',
    ],
    'lte' => [
        'numeric' => ':attribute musi być mniejszy niż lub równy :value.',
        'file' => ':attribute musi mieć mniej niż lub tyle samo :value kilobajtów.',
        'string' => ':attribute musi być krótszy niż :value znaków.',
        'array' => ':attribute nie może mieć więcej niż :value elementów.',
    ],
    'max' => [
        'numeric' => ':attribute nie może być większy niż :max.',
        'file' => ':attribute nie może być większy niż :max kilobajtów.',
        'string' => ':attribute nie może być dłuższy niż :max znaków.',
        'array' => ':attribute nie może być większy niż :max elementów.',
    ],
    'mimes' => ':attribute musi być plikiem typu: :values.',
    'mimetypes' => ':attribute musi być plikiem typu: :values.',
    'min' => [
        'numeric' => ':attribute musi być przynajmniej :min.',
        'file' => ':attribute musi mieć przynajmniej :min kilobajtów.',
        'string' => ':attribute musi mieć przynajmniej :min znaków.',
        'array' => ':attribute musi mieć przynajmniej :min elementów.',
    ],
    'not_in' => 'Wybrany :attribute jest nieważny.',
    'not_regex' => 'Format :attribute jest nieprawidłowy.',
    'numeric' => ':attribute musi być numerem.',
    'present' => 'Pole :attribute musi być obecne.',
    'regex' => 'Format :attribute jest niewłaściwy.',
    'required' => 'Pole :attribute jest wymagane.',
    'required_if' => 'Pole :attribute jest wymagane gdy :other jest :value.',
    'required_unless' => 'Pole :attribute jest wymagane chyba, że :other jest w :values.',
    'required_with' => 'Pole :attribute jest wymagane kiedy :values jest obecne.',
    'required_with_all' => 'Pole :attribute jest wymagane kiedy :values są obecne.',
    'required_without' => 'Pole :attribute jest wymagane kiedy :values nie jest obecny.',
    'required_without_all' => 'Pole :attribute jest wymagane kiedy żadne z :values nie są obecne.',
    'same' => ':attribute i :other muszą do siebie pasować.',
    'size' => [
        'numeric' => ':attribute musi być :size.',
        'file' => ':attribute musi mieć :size kilobajtów.',
        'string' => ':attribute musi mieć :size znaków.',
        'array' => ':attribute musi posiadać :size elementów.',
    ],
    'starts_with' => ':attribute musi zaczynać się od jednego z wymienionych: :values',
    'string' => ':attribute musi być tekstem.',
    'timezone' => ':attribute musi być poprawną strefą czasową.',
    'unique' => ':attribute został już zajęty.',
    'uploaded' => 'Nie udało się przesłać :attribute.',
    'url' => ':attribute posiada nieprawidłowy format.',
    'uuid' => ':attribute musi posiadać poprawny UUID.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [],

];
