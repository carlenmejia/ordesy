<?php 
return [
  'denomination' => [
    'name' => [
      'required' => 'Mezhep adı gerekli.',
      'max' => 'Mezhep adı :max karakterden büyük olamaz.',
    ],
    'value' => [
      'required' => 'Mezhep değeri gereklidir.',
      'numeric' => 'Mezhep değeri bir sayı olmalıdır.',
      'min' => 'Mezhep değeri en az :min olmalıdır.',
      'max' => 'Mezhep değeri :max\'den büyük olamaz.',
    ],
    'type' => [
      'required' => 'Mezhep türü gereklidir.',
      'in' => 'Seçilen mezhep türü geçersiz.',
    ],
    'description' => [
      'max' => 'Mezhep açıklaması :max karakterden büyük olamaz.',
    ],
  ],
];