# PHP Array Objects

![license mit](https://badgen.net/github/license/krzar/php-array-objects)
![release](https://badgen.net/github/release/krzar/php-array-objects)
![last commit](https://badgen.net/github/last-commit/krzar/php-array-objects)

This package allows you to generate object based on array data.

This can be useful for integrations with some APIs, for example.

## Supports

- PHP 8+

## Installation

```bash
composer require krzar/php-array-objects
```

## Usage

### Simple object

```php
use KrZar\PhpArrayObjects\ArrayObject;

class UserData extends ArrayObject {
    public string $name;
    public string $email;
    public int $age;
    public float $money;
    public bool $isActive = false;
    public array $roles = [];
}
```

To create this object from array call:

```php
$data = [
    'name' => 'Test',
    'email' => 'test@test.com',
    'age' => 99,
    'money' => 1520.50,
    'isActive' => true,
    'roles' => ['ADMIN']
];

UserData::create($data);
```

If any parameter is not passed no value will be assigned, so a default value should be established for such cases.

### Nested object

```php
class CompanyData extends ArrayObject {
    public string $name;
    public string $city;
    public string $street;
}

class UserData extends ArrayObject {
    public string $name;
    public string $email;
    public int $age;
    public float $money;
    public bool $isActive = false;
    public array $roles = [];
    public CompanyData $company;
}
```

To create this object from array call:

```php
$data = [
    'name' => 'Test',
    'email' => 'test@test.com',
    'age' => 99,
    'money' => 1520.50,
    'isActive' => true,
    'roles' => ['ADMIN'],
    'company' => [
        'name' => 'Test Company',
        'city' => 'Test',
        'street' => 'Test Street 1'
    ]   
];

UserData::create($data);
```

### Nested object multidimensional

If you want to create array of objects you need to configure this with `$arrayMap` property.

```php
class UserData extends ArrayObject {
    public string $name;
    public string $email;
    public int $age;
    public float $money;
    public bool $isActive = false;
    public array $roles = [];
    public CompanyData $company;
    public array $children;
    
    protected array $arrayMap = [
        'children' => UserData:class
    ];
}
```

To create this object from array call:

```php
$data = [
    'name' => 'Test',
    'email' => 'test@test.com',
    'age' => 99,
    'money' => 1520.50,
    'isActive' => true,
    'roles' => ['ADMIN'],
    'company' => [
        'name' => 'Test Company',
        'city' => 'Test',
        'street' => 'Test Street 1'
    ],
    'children' => [
        [
            'name' => 'Test 2',
            'email' => 'test2@test.com',
            'age' => 98,
            'money' => 2400,
            'isActive' => true,
            'roles' => ['MODERATOR']
        ]       
    ]       
];

UserData::create($data);
```

### Union types

You can use union types in objects, but with some restrictions:

- built-in types can be combined in any way
- ArrayObject type can be combined only with build-in types, not with other ArrayObject

For example:

```php
public CompanyData|string $company;
```

`CompanyData` will be created if a `company` index in array is array type.

### Names mapping

You can map names of parameters using `$namesMap` array.

```php
class UserData extends ArrayObject {
    public string $name;
    public string $email;
    public int $age;
    public float $money;
    public bool $isActive = false;
    public array $roles = [];
    public CompanyData $company;
    public array $children;
    
    protected array $arrayMap = [
        'children' => UserData:class
    ];
    
    protected array $namesMap = [
        'is_active' => 'isActive'
    ]
}
```
