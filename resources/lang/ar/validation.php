<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages.
    |
    */
    'phone'                => 'الهاتف موجود مسبقا',
    'accepted'             => 'يجب قبول :attribute .',
    'active_url'           => ':attribute ليس عنوان صحيح.',
    'after'                => ':attribute يجب أن يكون تاريخ بعد :date',
    'after_or_equal'       => ':attribute يجب لأن يكون تاريخ بعد :date أو يساوي :date sein.',
    'alpha'                => ':attribute يجب أن تحوي فقط على أحرف و أرقام.',
    'alpha_dash'           => ':attribute يجب أن تحوي فقط على أحرف و أرقام',
    'alpha_num'            => ':attribute يجب أن تحوي فقط على أحرف و أرقام',
    'array'                => ':attribute يجب لأن تكون مصفوفة',
    'before'               => ':attribute يجب أن تكون تاريخ :date ',
    'before_or_equal'      => ':attribute يجب أن تكون تاريخ :date أو يساوي :date sein.',
    'between'              => [
        'numeric' => ':attribute يجب أن يكون بين :min & :max',
        'file'    => ':attribute يجب أن يكون بين  :min & :max كيلوبايت.',
        'string'  => ':attribute يجب أن يكون بين  :min & :max محارف.',
        'array'   => ':attribute يجب أن يكون بين  :min & :max عناصر.',
    ],
    'boolean'              => ":attribute الحقل يجب أن يكون 'true' أو 'false' ",
    'confirmed'            => ':attribute التأكيد ليس متطابق',
    'date'                 => ':attribute ليس تاريخ صحيح',
    'date_equals'          => ':attribute يجب أن يكون تاريخ أو يساوي :date.',
    'date_format'          => ':attribute لا تطابق الصيغة :format.',
    'different'            => ':attribute و :other يجب لأن يكونا مختلفين.',
    'digits'               => ':attribute :digits يجب أن يكون من الأرقام..',
    'digits_between'       => ':attribute على الأقل :min و :max على الأكثر.',
    'dimensions'           => ':attribute الصورة بابعاد غير صحيحة.',
    'distinct'             => ':attribute قيمة مكررة.',
    'email'                => 'البريد الالكتروني موجود مسبقا',
    'exists'               => ' :attribute ليست صحيحة.',
    'file'                 => ':attribute يجب لأن يكون ملف',
    'filled'               => ':attribute يجب أن تملك قيمة',
    'gt'                   => [
        'numeric' => ':attribute يجب أن يكون أكبر من :value',
        'file'    => ':attribute يجب أن يكون أكبر من :value كيلوبايت.',
        'string'  => ':attribute يجب أن يكون أكبر من :value محارف',
        'array'   => ':attribute يجب أن يكون أكبر من :value عناصر',
    ],
    'gte'                  => [
        'numeric' => ':attribute يجب أن يكون أكبر أو يساوي :value ',
        'file'    => ':attribute يجب أن يكون أكبر أو يساوي :value كيلوبايت',
        'string'  => ':attribute يجب أن يكون أكبر أو يساوي :value محارف',
        'array'   => ':attribute يجب أن يكون أكبر أو يساوي :value عناصر',
    ],
    'image'                => ':attribute يجب أن تكون صورة',
    'in'                   => ' :attribute ليست صحيحة',
    'in_array'             => ' :attribute لا توجد في :other vor.',
    'integer'              => ':attribute يجب أن يكون عدد صحيح.',
    'ip'                   => ':attribute يجب أن يكون عنوان صحيح',
    'ipv4'                 => ':attribute يجب أن يكون عنوان صحيح',
    'ipv6'                 => ':attribute يجب أن يكون عنوان صحيح',
    'json'                 => ':attribute يجب أن يكون ملف json صحيح',
    'lt'                   => [
        'numeric' => ':attribute أصغر من :value .',
        'file'    => ':attribute أصغر من :value كيلوبايت.',
        'string'  => ':attribute أصغر من :value محارف',
        'array'   => ':attribute أصغر من :value عناصر',
    ],
    'lte'                  => [
        'numeric' => ':attribute أصغر من أو يساوي :value',
        'file'    => ':attribute أصغر من أو يساوي :value كيلوبايت',
        'string'  => ':attribute أصغر من أو يساوي :value محارف',
        'array'   => ':attribute أصغر من أو يساوي :value عناصر',
    ],
    'max'                  => [
        'numeric' => ':attribute يجب أن لا تكون أكبر من :max ',
        'file'    => ':attribute يجب أن لا تكون أكبر من :max كيلوبايت.',
        'string'  => ':attribute يجب أن لا تكون أكبر من :max محارف.',
        'array'   => ':attribute يجب أن لا تكون أكبر من :max عناصر.',
    ],
    'mimes'                => ':attribute يجب أن يكون ملف من نوع :values ',
    'mimetypes'            => ':attribute يجب أن يكون ملف من نوع :values haben.',
    'min'                  => [
        'numeric' => ':attribute  لا بد أن يكون على الأقل :min ',
        'file'    => ':attribute لا بد أن يكون على الأقل :min كيلوبايت',
        'string'  => ':attribute لا بد أن يكون على الأقل :min محارف',
        'array'   => ':attribute لا بد أن يكون على الأقل :min عناصر',
    ],
    'not_in'               => ' :attribute ليست صحيحة',
    'not_regex'            => ':attribute الصيغة لبست صحيحة',
    'numeric'              => ':attribute يجب أن يكون رقما.',
    'present'              => ':attribute muss vorhanden sein.',
    'regex'                => ':attribute الصيغة لبست صحيحة',
    'required'             => ':attribute الحقل مطلوب.',
    'required_if'          => ':attribute الحقل مطلوب عندما  :other :value هو.',
    'required_unless'      => ':attribute الحقل مطلوب عندما :other  هو موجود في:values .',
    'required_with'        => ':attribute الحقل مطلوب عندما :values .',
    'required_with_all'    => ':attribute الحقل مطلوب عندما :values',
    'required_without'     => ':attribute الحقل مطلوب عندما :values ',
    'required_without_all' => ':attribute الحقل مطلوب عندما :values ',
    'same'                 => ':attribute و :other يجب أن يتطابقو.',
    'size'                 => [
        'numeric' => ':attribute يجب أن يكون من الحجم :size ',
        'file'    => ':attribute يجب أن يكون من الحجم :size كيلوبايت',
        'string'  => ':attribute يجب أن يكون من العدد :size محارف.',
        'array'   => ':attribute يجب أن يحوي :size العناصر',
    ],
    'starts_with'          => ' :attribute يجب أن يبدأ بإحدى: :values',
    'string'               => ':attribute يجب أن يكون نص.',
    'timezone'             => ':attribute يجب أن يكون النطلق صحيح.',
    'unique'               => ':attribute موجود مسبقا.',
    'uploaded'             => ':attribute فشل في الترفيع.',
    'url'                  => ':attribute الصيغة ليست صحيحة.',
    'uuid'                 => ':attribute يجب أن يكون UUID صحيح',

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
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [
        'name'                  => 'الأسم',
        'username'              => 'أسم المستخدم',
        'email'                 => 'البريد الألكتروني',
        'first_name'            => 'الأسم الأول',
        'last_name'             => 'الكنية',
        'password'              => 'كلمة المرور',
        'password_confirmation' => 'تأكيد كلمة المرور',
        'city'                  => 'المدينة',
        'country'               => 'الدولة',
        'address'               => 'العنوان',
        'phone'                 => 'الهاتف',
        'mobile'                => 'الهاتف',
        'age'                   => 'العمر',
        'sex'                   => 'الجنس',
        'gender'                => 'الجنس',
        'day'                   => 'اليوم',
        'month'                 => 'شهر',
        'year'                  => 'سنة',
        'hour'                  => 'ساعة',
        'minute'                => 'دقيقة',
        'second'                => 'ثانية',
        'title'                 => 'العنوان',
        'content'               => 'المحتوى',
        'description'           => 'الوصف',
        'excerpt'               => 'مقتطف',
        'date'                  => 'التاريخ',
        'time'                  => 'التوقيت',
        'available'             => 'متاح',
        'size'                  => 'الحجم',
    ],
];
