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

    'accepted' => 'El :attribute debe ser aceptado.',
    'accepted_if' => 'El :attribute debe ser aceptado cuando :otro es :valor.',
    'active_url' => 'El :attribute no es una URL válida.',
    'after' => 'El :attribute debe ser una fecha posterior a :date.',
    'after_or_equal' => 'El :attribute debe ser una fecha posterior o igual a :date.',
    'alpha' => 'El :attribute solo debe contener letras.',
    'alpha_dash' => 'El :attribute solo debe contener letras, números, guiones y guiones bajos.',
    'alpha_num' => 'El :attribute solo debe contener letras y números.',
    'array' => 'El :attribute debe ser una matriz.',
    'before' => 'El :attribute debe ser una fecha anterior a :date.',
    'before_or_equal' => 'El :attribute debe ser una fecha anterior o igual a :date.',
    'between' => [
        'array' => 'El :debe tener entre :min y :max items.',
        'file' => 'El :attribute debe estar entre :min y :max kilobytes.',
        'numeric' => 'El :attribute debe estar entre :min y :max.',
        'string' => 'El :attribute debe estar entre :min y :max characters.',
    ],
    'boolean' => 'El campo :attribute fdebe ser verdadero o falso.',
    'confirmed' => 'El :attribute confirmación no coincide.',
    'current_password' => 'La contraseña es incorrecta.',
    'date' => 'El :attribute  no es una fecha válida.',
    'date_equals' => 'El :attribute debe ser una fecha igual a :date.',
    'date_format' => 'El :attribute no coincide con el formato :format.',
    'declined' => 'El :attribute debe ser rechazado.',
    'declined_if' => 'El :attribute  debe rechazarse cuando :other es :value.',
    'different' => 'El :attribute y :other  deben ser diferentes.',
    'digits' => 'El :attribute debe ser :digits dígitos.',
    'digits_between' => 'El :attribute debe estar entre :min y :max dígitos.',
    'dimensions' => 'El :attribute tiene dimensiones de imagen no válidas.',
    'distinct' => 'El campo :attribute tiene un valor duplicado.',
    'doesnt_start_with' => 'El :attribute no puede comenzar con uno de los siguientes: :values.',
    'email' => 'El :attribute debe ser una dirección de correo electrónico válida.',
    'ends_with' => 'El :attribute debe terminar con uno de los siguientes: :values.',
    'enum' => 'El campo :attribute seleccionado no es válido.',
    'exists' => 'El campo :attribute seleccionado no es válido.',
    'file' => 'El :attributedebe ser un archivo.',
    'filled' => 'El campo :attribute debe tener un valor.',
    'gt' => [
        'array' => 'El :attribute debe tener más de :value items.',
        'file' => 'El :attribute debe ser mayor que :value kilobytes.',
        'numeric' => 'El :attribute debe ser mayor que :value.',
        'string' => 'El :attribute debe ser mayor que :value caracteres.',
    ],
    'gte' => [
        'array' => 'El :attribute debe tener :value items o mas.',
        'file' => 'El :attribute debe ser mayor o igual que :value kilobytes.',
        'numeric' => 'El :attribute debe ser mayor o igual que :value.',
        'string' => 'El :attribute debe ser mayor o igual que :value caracteres.',
    ],
    'image' => 'El :attribute debe ser una imagen.',
    'in' => 'El campo :attribute seleccionado no es válido.',
    'in_array' => 'El campo :attribute no existe en :other.',
    'integer' => ':attribute debe ser un número entero.',
    'ip' => ' :attribute debe ser una dirección IP válida.',
    'ipv4' => ' :attribute debe ser una dirección IPv4 válida.',
    'ipv6' => ' :attribute debe ser una dirección IPv6 válida.',
    'json' => ' :attribute debe ser una cadena JSON válida.',
    'lt' => [
        'array' => ' :attribute debe tener menos de :value items.',
        'file' => ' :attribute debe ser menor que :value kilobytes.',
        'numeric' => ' :attribute debe ser menor que than :value.',
        'string' => ' :attribute debe ser menor que than :value caracteres.',
    ],
    'lte' => [
        'array' => ' :attribute no debe tener más de :value items.',
        'file' => ' :attribute debe ser menor o igual que :value kilobytes.',
        'numeric' => ' :attribute debe ser menor o igual que :value.',
        'string' => ' :attribute debe ser menor o igual que :value caracteres.',
    ],
    'mac_address' => ' :attribute debe ser una dirección MAC válida.',
    'max' => [
        'array' => ' :attribute no debe tener más de :max elementos.',
        'file' => ' :attribute no debe ser mayor que :max kilobytes.',
        'numeric' => ' :attribute no debe ser mayor que :max.',
        'string' => ' :attribute no debe ser mayor que :max characters.',
    ],
    'mimes' => ' :attribute debe ser un archivo de tipo: :values.',
    'mimetypes' => ' :attribute debe ser un archivo de tipo: :values.',
    'min' => [
        'array' => ' :attribute debe tener al menos :min elementos.',
        'file' => ' :attribute debe tener al menos :min kilobytes.',
        'numeric' => ' :attribute debe tener al menos :min.',
        'string' => ' :attribute debe tener al menos :min characters.',
    ],
    'multiple_of' => ' :attribute debe ser un múltiplo de :value.',
    'not_in' => ' El campo :attribute no es válido.',
    'not_regex' => ' :attribute format no es válido.',
    'numeric' => ' :attribute debe ser un número.',
    'password' => [
        'letters' => ' :attribute debe contener al menos una letra.',
        'mixed' => ' :attribute debe contener al menos una letra mayúscula y una minúscula.',
        'numbers' => ' :attribute debe contener al menos un número.',
        'symbols' => ' :attribute debe contener al menos un símbolo.',
        'uncompromised' => 'En el campo :attribute ha aparecido en una filtración de datos. Por favor, elija otro :attribute.',
    ],
    'present' => ' :attribute debe estar presente.',
    'prohibited' => ' :attribute campo está prohibido.',
    'prohibited_if' => ' :attribute está prohibido cuando :other es :value.',
    'prohibited_unless' => ' :attribute campo está prohibido a menos que :other está en :values.',
    'prohibits' => 'El campo :attribute prohíbe :other de estar presente.',
    'regex' => ' :attribute format no es válido.',
    'required' => ' campo :attribute es obligatorio.',
    'required_array_keys' => ' :attribute debe contener entradas para: :values.',
    'required_if' => ' :attribute es necesario cuando :other es :value.',
    'required_unless' => ' :attribute es obligatorio a menos que :other está en :values.',
    'required_with' => ' :attribute es necesario cuando :values está presente.',
    'required_with_all' => ' :attribute es necesario cuando :values están presentes.',
    'required_without' => ' :attribute es necesario cuando :values no está presente.',
    'required_without_all' => ' :attribute es necesario cuando ninguna de :values están presentes.',
    'same' => ' :attribute y :other debe coincidir.',
    'size' => [
        'array' => ' :attribute debe contener :size elementos.',
        'file' => ' :attribute debe ser :size kilobytes.',
        'numeric' => ' :attribute debe ser :size.',
        'string' => ' :attribute debe ser :size characters.',
    ],
    'starts_with' => ' :attribute debe comenzar con uno de los siguientes: :values.',
    'string' => ' :attribute debe ser una cadena de texto.',
    'timezone' => ' :attribute debe ser una zona horaria válida.',
    'unique' => ' :attribute ya ha sido tomada.',
    'uploaded' => ' :attribute no se ha podido cargar.',
    'url' => ' :attribute debe ser una URL válida.',
    'uuid' => ' :attribute debe ser un UUID.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name El lines. This makes it quick to
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
    | El following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [],

];
